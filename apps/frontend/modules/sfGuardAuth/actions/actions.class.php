<?php

require_once (sfConfig::get('sf_lib_dir') . '/vendor/sendgrid-php/SendGrid_loader.php');
require_once (sfConfig::get('sf_plugins_dir') . '/sfDoctrineGuardPlugin/modules/sfGuardAuth/lib/BasesfGuardAuthActions.class.php');
// Include the Tera-WURFL file
require_once (sfConfig::get('sf_lib_dir') . '/vendor/terawurfl/TeraWurfl.php');

/**
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23319 2009-10-25 12:22:23Z Kris.Wallsmith $
 */
class sfGuardAuthActions extends BasesfGuardAuthActions {

    public function executeLogin(sfWebRequest $request) {
        // error_log("HOOLLLLAAA\n",3,"/var/tmp/error-custom-lt.log");
        if ($viewTraditional = $request->getParameter('vt', false)) {
            $this->getUser()->setAttribute('view_traditional', true);
        }

        if ($wurflObj = $this->isMobileDevice() && !$this->getUser()->getAttribute('view_traditional', false)) {
            $this->setLayout('mobileLayout');
        }
        
        $user = $this->getUser();
        
        if ($user->isAuthenticated()) {
            return $this->redirect(sfConfig::get('app_sf_guard_plugin_success_signin_url','user_prizes'));
        }

        $class = sfConfig::get('app_sf_guard_plugin_login_form', 'sfGuardFormSignin');
        $this->form = new $class();

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $values = $this->form->getValues();

                $buhoValues = array();
                $buhoValues['email'] = $values['username'];
                $buhoValues['password'] = $values['password'];
                $buho = new epBuhoApi();
		
                $result = array('success' => 0);
                # $result = $buho->buhoLogin($buhoValues);
                
                $email = $values['username'];
                $password = $values['password'];
                
                $this->email = $email;

                // CODIGO PARA SUSTITUIR AL BUHO
                $userObj = Doctrine::getTable('sfGuardUser')->findOneBy('email_address', $email);
                if ($userObj) {
                    #var_dump(sha1($userObj->getSalt().$password) == $userObj->getPassword());die();
                    if (sha1($userObj->getSalt().$password) == $userObj->getPassword()){
                        $result['success'] = 1;
                        $result = array(
                            "success" => 1,
                            "user" => array(
                                "email"=>$userObj->getEmail(), 
                                "hash"=>$userObj->getHash(),
                                "verified"=>$userObj->getIsActive(),
                                "info" => array()
                            ));
                        if ( !$userObj->getIsActive() ) {
                            $result = array('success' => 0, 'error' => array('code' => '', 'message' => ''));
                            $result['error']['code'] = '10608';    
                        }
                    }else{
                        $result = array('success' => 0, 'error' => array('code' => '', 'message' => ''));
                        $result['error']['code'] = '00000';
                    }
                }else{
                    $result = array('success' => 0, 'error' => array('code' => '', 'message' => ''));
                    $result['error']['code'] = '00000'; 
                }
                // FIN DE CODIGO PARA SUSTITUIR AL BUHO

                if (!$result['success']) {
                    #var_dump($result['success']);die;
                    $userObj = Doctrine::getTable('sfGuardUser')->findOneBy('email_address', $email);
                    
                    $errorCode = $result['error']['code'];

                    if (strcasecmp($errorCode, '00000') == 0) { /* Invalid email or password */
                        if ($userObj && $userObj->getPreRegistered()) {
                            try {
                                $this->sendPreRegisteredMail($userObj->getUserProfile());
                                $user->setFlash('error', 'Aún no has completado tu registro, te hemos enviado un correo para que puedas hacerlo.');
                                return 'Success';
                            } catch (Exception $e) {
                                $user->setFlash('error', 'Ha ocurrido un error al intentar enviarte un correo. Por favor intenta nuevamente');
                                return 'Success';
                            }
                        }
                        
                        $this->getUser()->setFlash('error', 'Tu correo o contraseña son inválidos');
                        return 'Success';
                    }

                    if (strcasecmp($errorCode, '10608') == 0) { /* Not verified */
                        if ($userObj) { /* Check if the user exists in LT db */
                            try { /* Re-send verification Email */
                                $this->sendVerificationMail($userObj->getUserProfile());
                                $user->setFlash('error', 'Aún no has verificado tu cuenta, te hemos enviado un correo para que puedas verificarla');
                                return 'Success';
                            } 
                            catch (Exception $e) {
                                $user->setFlash('error', 'Ha ocurrido un error al intentar enviarte un correo. Por favor intenta nuevamente');
                                return 'Success';
                            }
                        } 
                        else {
                            try { /* Send verification email for an user no registered in LT db and return an error message */
                                $buhoValues = array('user' => $email);
                                #$user_data = $buho->buhoGetUser($buhoValues);
                                $user_data = array("user"=>array("email"=>$email, "hash"=>$userObj->getHash(), "validator"=>$userObj->getUserProfile()->getValidate()));
                                
                                $this->sendVerificationMailUnregistered($user_data['user']);
                                
                                $user->setFlash('error', 'Aún no has verificado tu cuenta de TuDescuenton.com, te enviamos un correo para que puedas verificarla');
                                
                                return 'Success';
                            } 
                            catch (Exception $e) {
                                $user->setFlash('error', 'Ha ocurrido un error al intentar enviarte un correo a tu cuenta de TuDescuenton.com. Por favor intenta nuevamente');
                                
                                return 'Success';
                            }
                        }
                    }
                    
                    return 'BuhoError';
                }

                if (!$userObj = Doctrine::getTable('sfGuardUser')->findOneBy('email_address', $email)) {
                    $result['user']['password'] = $password; /* set the password value for the new user */

                    if (!$userObj = $this->createUser($result['user'])) {
                        return 'BuhoError';
                    }
                } else {
                    # var_dump($userObj->getIsActive()); die;
                    $userObj = $this->updateUser($result['user'], $userObj);
                }

                $user->signin($userObj, array_key_exists('remember', $values) ? $values['remember'] : false);

                if (!$user->getGuardUser()->dataComplete()) {
                    return $this->redirect('user_complete_info');
                }

                // always redirect to a URL set in app.yml or to the referer or to the homepage
                $signinUrl = $user->getReferer(sfConfig::get('app_sf_guard_plugin_success_signin_url', 'user_prizes'));
                
                return $this->redirect($signinUrl);
                
                //return $this->redirect('' != $signinUrl ? $signinUrl : sfConfig::get('app_sf_guard_plugin_success_signin_url', 'user_prizes'));
            }
        } 
        else {
            if ($request->isXmlHttpRequest()) {
                $this->getResponse()->setHeaderOnly(true);
                $this->getResponse()->setStatusCode(401);

                return sfView::NONE;
            }

            // if we have been forwarded, then the referer is the current URL
            // if not, this is the referer of the current request
            $user->setReferer($this->getContext()->getActionStack()->getSize() > 1 ? $request->getUri() : $request->getReferer());
            
            $module = sfConfig::get('sf_login_module');
            if ($this->getModuleName() != $module) {
                return $this->redirect($module . '/' . sfConfig::get('sf_login_action'));
            }

            $this->getResponse()->setStatusCode(401);
        }
    }

    protected function updateUser($userData, sfGuardUser $user) {
        return $this->updateUserWithBuhoData($userData, $user);
    }

    protected function createUser($userData) {
        $user = new sfGuardUser();
        $user->addGroupByName('licoteca_users');

        $user = $this->updateUserWithBuhoData($userData, $user);
        try {
            $this->sendWelcomeMail($user->getUserProfile());
            return $user;
        } catch (Exception $e) {
            throw $e;
            $user->delete();
            return false;
        }
    }

    protected function updateUserWithBuhoData($userData, sfGuardUser $user) {
        /**
         * Setting sfGuardUser related data
         */
        $user->setEmailAddress($userData['email']);
        $user->setHash($userData['hash']);
        $user->setIsActive($userData['verified'] == 1 ? true : false);

        /**
         * Setting UserProfile related data
         */
        $user->getUserProfile()->setEmail($userData['email']);

        if (array_key_exists('fullname', $userData['info'])) {
            $user->getUserProfile()->setFullname($userData['info']['fullname']);
        }

        if (array_key_exists('birthday', $userData['info'])) {
            $user->getUserProfile()->setBirthdate($userData['info']['birthday']);
        }

        if (array_key_exists('identifier', $userData['info'])) {
            $user->getUserProfile()->setIdNumber($userData['info']['identifier']);
        }

        if (array_key_exists('mobile_phone', $userData['info'])) {
            $user->getUserProfile()->setPhone($userData['info']['mobile_phone']);
        }

        if (array_key_exists('gender', $userData['info'])) {
            $user->getUserProfile()->setGender($userData['info']['gender']);
        }
        
        if (array_key_exists('validator', $userData)) {
            $user->getUserProfile()->setValidate($user->getIsActive() ? ($userData['validator'] ? 'r'.$userData['validator'] : '') : 'n'.$userData['validator']);
        }

        /**
         * This will save both sfGuardUser and UserProfile objects at once
         */
        $user->save();

        return $user;
    }

    protected function sendWelcomeMail($profile) {
        $this->mail(array(
            'subject'       => 'Bienvenido a Licoteca',
            'teaser'        => 'Felicidades, ahora tienes una cuenta en Licoteca',
            'to'            => $profile->getEmail(),
            'html'          => 'email/sendWelcome',
            'text'          => 'email/sendWelcomeText',
            'substitutions' => array('%FULLNAME%' => array(($profile->getFullname() ? $profile->getFullname() : $profile->getEmail()))),
            'category'      => array('transactional', 'welcome', 'frontend'),
        ));
    }
    
    protected function sendVerificationMail($profile) {
        $route = $this->getContext()->getRouting()->generate('validate', array('validate' => $profile->getValidate()), true);
        
        $this->mail(array(
            'subject'       => 'Bienvenido a Licoteca - Verifica tu cuenta',
            'teaser'        => 'Felicidades, solo falta un paso más para activar tu cuenta en Licoteca',
            'to'            => $profile->getEmail(),
            'html'          => 'email/sendValidateNew',
            'text'          => 'email/sendValidateNewText',
            'substitutions' => array(
                    '%FULLNAME%'    => array(($profile->getFullname() ? $profile->getFullname() : $profile->getEmail())),
                    '%WELCOME%'     => array((strcasecmp($profile->getGender(), 'female') == 0 ? 'Bienvenida' : 'Bienvenido')),
                    '%ROUTE%'       => array($route)
                ),
            'category'      => array('transactional', 'verification', 'frontend'),
        ));
    }
    
    protected function sendPreRegisteredMail(UserProfile $profile) {
        $route = $this->getContext()->getRouting()->generate('user_complete_register', array('validate' => $profile->getValidate()), true);
        
        $this->mail(array(
            'subject'       => 'Completa tu registro para ser premiado',
            'teaser'        => 'Felicidades, solo falta un paso más para activar tu cuenta en Licoteca',
            'to'            => $profile->getEmail(),
            'html'          => 'email/sendValidatePreregistered',
            'text'          => 'email/sendValidatePreregisteredText',
            'substitutions' => array('%ROUTE%' => array($route)),
            'category'      => array('transactional', 'verification', 'frontend', 'buho-unverified'),
        ));
    }
    
    protected function sendVerificationMailUnregistered($userData) {
        $route = $this->getContext()->getRouting()->generate('validate', array('validate' => $userData['validator']), true);
        
        $this->mail(array(
            'subject'       => 'Verifica tu cuenta para ser premiado',
            'teaser'        => 'Felicidades, solo falta un paso más para activar tu cuenta en Licoteca',
            'to'            => $userData['email'],
            'html'          => 'email/sendValidatePreregistered',
            'text'          => 'email/sendValidatePreregisteredText',
            'substitutions' => array('%ROUTE%' => array($route."?u=".$userData['hash'])),
            'category'      => array('transactional', 'verification', 'frontend', 'buho-unverified'),
        ));
    }
    
    protected function mail($options) {
        $required = array('subject', 'to', 'html');

        foreach ($required as $option) {
            if (!isset($options[$option])) {
                throw new sfException("Required option $option not supplied to sfApplyActions::mail");
            }
        }

        $sendgrid = new SendGrid(sfConfig::get('app_sendgrid_username'), sfConfig::get('app_sendgrid_password'));

        $mail = new SendGrid\Mail();
        
        $layout = $this->getPartial('email/layout');
        
        $body = $this->getPartial($options['html']);
        
        $teaser = !empty($options['teaser']) ? $options['teaser'] : '';

        $mail->setFrom(sfConfig::get('app_sendgrid_email'))->
                setFromName(sfConfig::get('app_sendgrid_name'))->
                setSubject($options['subject'])->
                setHtml(str_replace(array('%EMAIL_TEASER%','%EMAIL_BODY%'), array($teaser,$body), $layout));
        
        if (!empty($options['text'])) {
            $text = $this->getPartial('email/layoutText', array('teaser' => $teaser, 'body' => $this->getPartial($options['text'])));
            
            $mail->setText($text);
        }

        if (is_array($options['to'])) {
            if (count($options['to']) > 1000) {
                throw new sfException("the maximun number of recipients is 1000 - sfApplyActions::mail");
            }
            
            $mail->setTos($options['to']);
        } else {
            $mail->setTo($options['to']);
        }
        
        if (isset($options['substitutions'])) {
            $mail->setSubstitutions($options['substitutions']);
        }
        
        if (isset($options['sections'])) {
            $mail->setSections($options['sections']);
        }
        

        if (isset($options['category'])) {
            if (is_array($options['category'])) {
                if (count($options['category']) > 10) {
                    throw new sfException("the maximun number of categories that can be set is 10 - sfApplyActions::mail");
                }

                $mail->setCategories($options['category']);
            } else {
                $mail->setCategory($options['category']);
            }
        }

        $sendgrid->smtp->send($mail);
    }

    protected function getFromAddress() {
        $from = sfConfig::get('app_sfApplyPlugin_from', false);
        if (!$from) {
            throw new Exception('app_sfApplyPlugin_from is not set');
        }
        // i18n the full name
        return array('email' => $from['email'], 'fullname' => sfContext::getInstance()->getI18N()->__($from['fullname']));
    }
    
    protected function isMobileDevice() {
        // instantiate the Tera-WURFL object
        $wurflObj = new TeraWurfl();

        // Get the capabilities of the current client.
        $matched = $wurflObj->getDeviceCapabilitiesFromAgent();

        // see if this client is on a wireless device (or if they can't be identified)
        if (!$matched || !$wurflObj->getDeviceCapability("is_wireless_device")) {
            return false;
        }

        return $wurflObj;
    }
}
