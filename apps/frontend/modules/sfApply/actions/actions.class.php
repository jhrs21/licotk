<?php

/**
 * sfApply actions.
 *
 * @package    5seven5
 * @subpackage sfApply
 * @author     Tom Boutell, tom@punkave.com
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

require_once (sfConfig::get('sf_lib_dir') . '/vendor/sendgrid-php/SendGrid_loader.php');
require_once (sfConfig::get('sf_lib_dir') . '/vendor/terawurfl/TeraWurfl.php');

// Necessary due to a bug in the Symfony autoloader
require_once(sfConfig::get('sf_plugins_dir') . '/sfDoctrineApplyPlugin/modules/sfApply/lib/BasesfApplyActions.class.php');

class sfApplyActions extends BasesfApplyActions {

    public function executeApply(sfRequest $request) {

	// Agregado porque para Licoteca esta es la pagina principal
	$user = $this->getUser()->getGuardUser();
	if($user){
		$this->redirect($this->getController()->genUrl(array('sf_route' => 'user_prizes')));
	}

        // error_log("\nentrando"." - ".date("Y-m-d H:i:s")."\n",3, "/var/tmp/debug-lt.log");
        $this->form = $this->newForm('sfApplyApplyForm');
        if ($viewTraditional = $request->getParameter('vt', false)) {
            $this->getUser()->setAttribute('view_traditional', true);
        }

        if ($wurflObj = $this->isMobileDevice() && !$this->getUser()->getAttribute('view_traditional', false)) {
            $this->setLayout('mobileLayout');
            $this->setTemplate('webMobileApply');
            $this->form = new epUserApplyWebmobileForm;
            $this->showTermCheck = true;
        }

        $this->form->getWidgetSchema()->setFormFormatterName('epWeb');

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()));

            //  Verificar si el formulario es válido, incluyendo si el usuario
            //  ya está registrado en la bd local del sistema
            if ($this->form->isValid()) {
                //  Se preparan los valores que deben ser pasados al Buho para el
                //  registro único de usuarios.
                $values = $this->form->getValues();

                $buhoValues = array();
                $buhoValues['fullname'] = $values['first_name'] . ' ' . $values['last_name'];
                $buhoValues['email'] = $values['email'];
                $buhoValues['password'] = $values['password'];
                $buhoValues['identifier'] = is_null($values['id_number']) ? '' : $values['id_number'];
                $buhoValues['mobile_phone'] = array_key_exists('phone', $values) ? $values['phone'] : '';
                $buhoValues['birthday'] = $values['birthdate'];
                $buhoValues['gender'] = $values['gender'];

                $buho = new epBuhoApi();

                #$result = $buho->buhoCreateUser($buhoValues);
                $validator = Util::GenSecret(16,0);
                $hash = Util::GenSecret(8,0);
                $result = array("success" => 1, "user" => array("validator"=>$validator, "hash" => $hash));
                
                //  Verificar si el usuario es registrado en El Buho sin inconvenientes
                if ($result['success']) {   //  Registrar el nuevo usuario de forma local y asignar el hash retornado por El Buho
                    $guid = "n" . $result['user']['validator'];
                    $this->form->setValidate($guid);
                    $this->form->setUserHash($result['user']['hash']);
                    $this->form->save();
                    try {
                        $profile = $this->form->getObject();
                        $this->sendVerificationMail($profile);
                        return 'After';
                    } catch (Exception $e) {
                        $profile = $this->form->getObject();
                        $user = $profile->getUser();
                        $user->delete();
                        return 'MailerError';
                    }
                } else {
                    if ($result['error']['code'] == '10206') {
                        return 'BuhoUserExistsError';
                    }

                    $this->error = $result['error'];
                    $this->email = $user->getEmailAddress();

                    return 'BuhoError';
                }
            }
        }
    }

    /**
     * Función para completar el ciclo de registro de un usuario Pre-Registrado
     * @param sfWebRequest $request
     * @return string 
     */
    public function executeCompleteRegister(sfWebRequest $request) {
        $route_params = $this->getRoute()->getParameters();

        if (isset($route_params['validate'])) {
            $this->validate = $route_params['validate'];
        }
        else {
            $this->validate = $request->getParameter('v', false);
        }

        if (!strlen($this->validate) || !(substr($this->validate, 0, 2) == 'pr')) {
            return 'Invalid';
        }

        $user = Doctrine_Query::create()->from("sfGuardUser u")
                ->innerJoin("u.UserProfile p with p.validate = ?", $this->validate)
                ->fetchOne();

        if (!$user) {
            return 'Invalid';
        }

        $this->form = $this->newForm('sfApplyCompleteApplicationForm', $user->getUserProfile());
        
        if ($viewTraditional = $request->getParameter('vt', false)) {
            $this->getUser()->setAttribute('view_traditional', true);
        }

        if ($wurflObj = $this->isMobileDevice() && !$this->getUser()->getAttribute('view_traditional', false)) {
            $this->setLayout('mobileLayout');
            $this->setTemplate('webMobileCompleteRegister');
            $this->showTermCheck = true;
        }
        
        $this->form->setDefault('birthdate', '');
        $this->form->getWidgetSchema()->setFormFormatterName('epWeb');

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {
                $values = $this->form->getValues();

                $buhoValues = array();
                $buhoValues['fullname'] = $values['first_name'] . ' ' . $values['last_name'];
                $buhoValues['email'] = $values['email'];
                $buhoValues['password'] = $values['password'];
                $buhoValues['identifier'] = is_null($values['id_number']) ? '' : $values['id_number'];
                $buhoValues['mobile_phone'] = $values['phone'];
                $buhoValues['birthday'] = $values['birthdate'];
                $buhoValues['gender'] = $values['gender'];

                $buho = new epBuhoApi();

                #$result = $buho->buhoCreateUser($buhoValues);
                $validator = Util::GenSecret(16,0);
                $hash = Util::GenSecret(8,0);
                $result = array("success" => 1, "user" => array("validator"=>$validator, "hash" => $hash));

                // Verificar si el usuario es registrado en El Buho sin inconvenientes
                if ($result['success']) { // Registrar el nuevo usuario de forma local y asignar el hash retornado por El Buho
                    $this->form->setUserHash($result['user']['hash']);
                    
                    $result = $buho->buhoVerify(array('user' => $result['user']['hash'], 'validator' => $result['user']['validator']));
                    
                    $this->form->save();
                } 
                else { //NOTA: DISCUTIR ESTE CASO CON OCTAVIO!!!
                    if ($result['error']['code'] == '10206') {
                        return 'BuhoUserExistsError';
                    }

                    $this->error = $result['error'];
                    $this->email = $user->getEmailAddress();

                    return 'BuhoError';
                }

                $user->setIsActive(true);
                $user->getUserProfile()->setValidate(null);
                $user->save();

                $this->getUser()->signIn($user);
                $this->getUser()->setFlash('profile_update_succeeded', '¡Tus datos se han actualizado exitosamente!');

                $this->redirect($this->getController()->genUrl(array('sf_route' => 'user_prizes')));
            }
        }
    }

    public function executeUpdate(sfRequest $request) {
        $user = $this->getUser()->getGuardUser();

        $this->form = $this->newForm('sfApplyApplyForm', $user->getUserProfile());

        $this->form->getWidgetSchema()->setFormFormatterName('epWeb');

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {
                $values = $this->form->getValues();

                $buhoValues = array();
                $buhoValues['user'] = $user->getHash();
                //$buhoValues['user'] = $user->getEmailAddress();
                $buhoValues['fullname'] = $values['first_name'] . ' ' . $values['last_name'];
                $buhoValues['identifier'] = array_key_exists('id_number', $values) ? (is_null($values['id_number']) ? '' : $values['id_number']) : '';
                $buhoValues['mobile_phone'] = array_key_exists('phone', $values) ? (is_null($values['phone']) ? '' : $values['phone']) : '';
                $buhoValues['birthday'] = $values['birthdate'];
                $buhoValues['gender'] = $values['gender'];

                $buho = new epBuhoApi();

                #$result = $buho->buhoUpdateUser($buhoValues);
                $result = array("success" => 1);

                if (!$result['success']) {
                    $this->error = $result['error'];
                    $this->email = $user->getEmailAddress();

                    return 'BuhoError';
                }

                $this->form->save();

                $this->getUser()->setFlash('profile_update_succeeded', '¡Tus datos se han actualizado exitosamente!');

                $this->redirect($this->getController()->genUrl(array('sf_route' => 'user_prizes')));
            }
        }
    }

    /**
     * Función para permitir a un usuario (registrado a través del buho) completar los datos de su cuenta
     * OJO no confundir con executeCompleteRegister
     * @param sfRequest $request
     * @return string 
     */
    public function executeComplete(sfRequest $request) {
        $user = $this->getUser()->getGuardUser();

        $this->form = $this->newForm('sfApplyApplyForm', $user->getUserProfile());

        $this->form->getWidgetSchema()->setFormFormatterName('epWeb');

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {
                $values = $this->form->getValues();

                $buhoValues = array();
                $buhoValues['user'] = $user->getHash();
                //$buhoValues['email'] = $user->getEmailAddress();
                $buhoValues['fullname'] = $values['first_name'] . ' ' . $values['last_name'];
                $buhoValues['identifier'] = array_key_exists('id_number', $values) ? (is_null($values['id_number']) ? '' : $values['id_number']) : '';
                $buhoValues['mobile_phone'] = array_key_exists('phone', $values) ? (is_null($values['phone']) ? '' : $values['phone']) : '';
                $buhoValues['birthday'] = $values['birthdate'];
                $buhoValues['gender'] = $values['gender'];

                $buho = new epBuhoApi();
                #$result = $buho->buhoUpdateUser($buhoValues);
                $result = array("success" => 1);

                if (!$result['success']) {
                    $this->errors = $result['error']['message'];
                    $this->email = $user->getEmailAddress();

                    return 'BuhoError';
                }

                $this->form->save();

                $this->getUser()->setFlash('profile_update_succeeded', '¡Tus datos se han actualizado exitosamente!');

                $this->redirect($this->getController()->genUrl(array('sf_route' => 'user_prizes')));
            }
        }
    }

    public function executeResendVerificationMail(sfRequest $request) {
        $parameters = $this->getRoute()->getParameters();
        
        $this->userId = $parameters['user_alpha'];

        $user = Doctrine::getTable('sfGuardUser')->findOneBy('alpha_id', $this->userId);
        
        if (!$user) {
            return 'Invalid';
        }

        $user->getUserProfile()->setValidate("n" . parent::createGuid());
        $user->getUserProfile()->save();

        try {
            $this->sendVerificationMail($user->getUserProfile());

            return 'After';
        } catch (Exception $e) {
            return 'MailerError';
        }
    }

    public function executeConfirm(sfRequest $request) {
        $validate = $this->request->getParameter('validate');
        $hash = $this->request->getParameter('u');

        if (!strlen($validate)) {
            return 'Invalid';
        }
        
        $user = Doctrine_Query::create()->from("sfGuardUser u")
                    ->innerJoin("u.UserProfile p with p.validate = ?", $validate)
                    ->fetchOne();
        
        $type = self::getValidationType($validate);

        if ($type == 'New') {
            if (!$user) {
                return 'Invalid';
            }
            
            $values = array();
            $values['user'] = $user->getHash();
            $values['validator'] = substr($validate, 1);

            $buho = new epBuhoApi();

            #$result = $buho->buhoVerify($values);
            $result = array("success" => 1);

            if (!$result['success']) {
                $this->error = $result['error'];
                $this->email = $user->getEmailAddress();

                return 'BuhoError';
            }

            $user->setIsActive(true);
            $user->save();
            $this->getUser()->signIn($user);
        }
        
        if ($type == 'Reset') {
            if (!$user) {
                return 'Invalid';
            }
            
            $this->getUser()->setAttribute('sfApplyReset', $user->getId());
            $this->getUser()->setAttribute('validator', substr($validate, 1));

            return $this->redirect('reset');
        }

        if ($type == 'None' && !$user) {
            $values['user'] = $hash;
            $values['validator'] = $validate;

            $buho = new epBuhoApi();

            #$result = $buho->buhoVerify($values);
            $result = array("success" => 1);

            if (!$result['success']) { // Error porque falló el buhoVerify
                if (strcasecmp($result['error']['code'], '10609') == 0) { /* User alredy verified */
                    $this->getUser()->setFlash('error', 'Ya has sido verificado');
                    return 'Success';
                }

                $this->error = $result['error'];
                return 'BuhoError';
            }

            #$result = $buho->buhoGetUser($values);
            $userObj = Doctrine::getTable('sfGuardUser')->findOneBy('hash', $hash);
            $result = array(
                            "success" => 1,
                            "user" => array(
                                "email"=>$userObj->getEmail(), 
                                "hash"=>$userObj->getHash(),
                                "verified"=>$userObj->getIsActive(),
                                "info" => array()
                            ));

            if (!$result['success']) { // Error porque falló el buhoGetUser
                $this->error = $result['error'];
                $this->email = $user->getEmailAddress();

                return 'BuhoError';
            }
            
            $user = $this->createUser($result['user']);
        }
        
        $this->getUser()->signIn($user);
    }

    public function executeReset(sfRequest $request) {
        $this->form = $this->newForm('sfApplyResetForm');
        
        if ($viewTraditional = $request->getParameter('vt', false)) {
            $this->getUser()->setAttribute('view_traditional', true);
        }

        if ($wurflObj = $this->isMobileDevice() && !$this->getUser()->getAttribute('view_traditional', false)) {
            $this->setLayout('mobileLayout');
            $this->setTemplate('webMobileReset');
            $this->form = $this->newForm('sfApplyResetForm');
            $this->form->setWidget('password', new sfWidgetFormInput(array('label' => 'Nueva Contraseña'), array('maxlength' => 128)));
            $this->form->setWidget('password2', new sfWidgetFormInput(array('label' => 'Confirmar Nueva Contraseña'), array('maxlength' => 128)));
        }

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter('sfApplyReset'));

            if ($this->form->isValid()) {
                $this->id = $this->getUser()->getAttribute('sfApplyReset', false);

                $this->forward404Unless($this->id);

                $this->sfGuardUser = Doctrine::getTable('sfGuardUser')->find($this->id);

                $this->forward404Unless($this->sfGuardUser);

                $sfGuardUser = $this->sfGuardUser;

                $buhoValues = array();
                $buhoValues['validator'] = $this->getUser()->getAttribute('validator', false);
                //$buhoValues['user'] = $sfGuardUser->getEmailAddress();
                $buhoValues['user'] = $sfGuardUser->getHash();
                $buhoValues['new_password'] = $this->form->getValue('password');

                $buho = new epBuhoApi();

                #$result = $buho->buhoUpdatePassword($buhoValues);
                $result = array("success" => 1);

                if (!$result['success']) {
                    $this->error = $result['error'];                    
                    $this->email = $sfGuardUser->getEmailAddress();

                    return 'BuhoError';
                }

                $sfGuardUser->setPassword($this->form->getValue('password'));
                $sfGuardUser->save();

                $this->getUser()->signIn($sfGuardUser);
                $this->getUser()->setAttribute('sfApplyReset', null);

                return 'After';
            }
        }
    }

    public function executeResetRequest(sfRequest $request) {
        $user = $this->getUser();
        
        if ($viewTraditional = $request->getParameter('vt', false)) {
            $this->getUser()->setAttribute('view_traditional', true);
        }

        if ($wurflObj = $this->isMobileDevice() && !$this->getUser()->getAttribute('view_traditional', false)) {
            $this->setLayout('mobileLayout');
            $this->setTemplate('webMobileResetRequest');
            $this->form = new epUserApplyWebmobileForm;
            $this->showTermCheck = true;
        }

        if ($user->isAuthenticated()) {
            $guardUser = $this->getUser()->getGuardUser();

            $this->forward404Unless($guardUser);
            //error_log("\nPASO POR AQUI"." - ".date("Y-m-d H:i:s")."\n",3, "/var/tmp/debug-lt.log");die;
            return $this->resetRequestBody($guardUser);
        } else {
            $this->form = $this->newForm('sfApplyResetRequestForm');

            if ($request->isMethod('post')) {
                $this->form->bind($request->getParameter('sfApplyResetRequest'));

                if ($this->form->isValid()) {
                    // The form matches unverified users, but retrieveByUsername does not, so
                    // use an explicit query. We'll special-case the unverified users in
                    // resetRequestBody

                    $username_or_email = $this->form->getValue('username_or_email');

                    if (strpos($username_or_email, '@') !== false) { //email
                        $user = Doctrine::getTable('sfGuardUser')->createQuery('u')
                                //->innerJoin('u.UserProfile p')
                                ->where('u.email_address = ?', $username_or_email)
                                ->fetchOne();
                        
                        $email = $username_or_email;
                        
                        if($user && $user->getPreRegistered()){
                            $this->sendPreRegisteredMail($user->getUserProfile());
                            
                            return 'Preregistered';
                        }
                    } else { //username
                        $user = Doctrine::getTable('sfGuardUser')->createQuery('u')
                                ->where('u.username = ?', $username_or_email)
                                ->fetchOne();
                        $email = '';
                    }

                    return $this->resetRequestBody($user, $email);
                }
            }
        }
    }

    public function resetRequestBody($user, $email = '') {
        if (!$user) {//user is not local, so I might look for in ElBuho
            $buho = new epBuhoApi();
            #$result = $buho->buhoGetUser(array('user' => $email));
            $result = array("success" => 0); #Esto nunca deberia pasar
            
            if($result['success']){
                $user = new sfGuardUser();
                $user->addGroupByName('licoteca_users');
                
                $user = $this->updateUserWithBuhoData($result['user'], $user);
            } else {
                return 'NoSuchUser';
            }
        }
        
        $profile = $user->getUserProfile();
        
        if (!$user->getIsActive()) {
            $type = self::getValidationType($profile->getValidate());
            
            if ($type === 'New') {
                try {
                    $this->sendVerificationMail($profile);
                } catch (Exception $e) {
                    return 'UnverifiedMailerError';
                }

                return 'Unverified';
            } elseif ($type === 'Reset') {
                // They lost their first password reset email. That's OK. let them try again
            } else {
                return 'Locked';
            }
        }

        $buhoValues = array();

        $buhoValues['user'] = $user->getHash();
        //$buhoValues['email'] = $user->getEmailAddress();
        
        $buho = new epBuhoApi();
        #$result = $buho->buhoResetPassword($buhoValues);
        $validator = strtolower(Util::GenSecret(32,0));
        $result = array("success"=>1, "user" => array("validator"=>$validator));

        if (!$result['success']) {
            $this->error = $result['error']; //errors debe ser un array
            $this->email = $user->getEmailAddress();

            return 'BuhoError';
        }

        $profile->setValidate('r' . $result['user']['validator']);
        $profile->save();

        try {
            $route = $this->getContext()->getRouting()->generate('validate', array('validate' => $profile->getValidate()), true);

            $this->mail(array(
                'subject'       => sfConfig::get('app_sfApplyPlugin_reset_subject'),
                'teaser'        => 'Hemos recibido una solicitud para modificar tu contraseña',
                'to'            => $profile->getEmail(),
                'html'          => 'email/sendValidateReset',
                'text'          => 'email/sendValidateResetText',
                'substitutions' => array(
                        '%FULLNAME%'    => array(($profile->getFullname() ? $profile->getFullname() : $profile->getEmail())),
                        '%ROUTE%'       => array($route)
                    ),
                'category'      => array('transactional', 'password-reset', 'frontend'),
            ));
        } catch (Exception $e) {
            throw $e;
            return 'MailerError';
        }

        return 'After';
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
            'teaser'        => 'Felicidades, ahora tienes una cuenta en Club Licoteca',
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
            'teaser'        => 'Felicidades, solo falta un paso más para activar tu cuenta en Club Licoteca',
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

    protected function sendVerificationMailUnregistered($userData) {
        $route = $this->getContext()->getRouting()->generate('validate', array('validate' => $userData['validator']), true);
        
        $this->mail(array(
            'subject'       => 'Verifica tu cuenta para ser premiado',
            'teaser'        => 'Felicidades, solo falta un paso más para activar tu cuenta en Club Licoteca',
            'to'            => $userData['email'],
            'html'          => 'email/sendValidatePreregistered',
            'text'          => 'email/sendValidatePreregisteredText',
            'substitutions' => array('%ROUTE%' => array($route."?u=".$userData['hash'])),
            'category'      => array('transactional', 'verification', 'frontend', 'buho-unverified'),
        ));
    }
    
    protected function sendPreRegisteredMail(UserProfile $profile) {
        $route = $this->getContext()->getRouting()->generate('user_complete_register', array('validate' => $profile->getValidate()), true);
        
        $this->mail(array(
            'subject'       => 'Completa tu registro para ser premiado',
            'teaser'        => 'Felicidades, solo falta un paso más para activar tu cuenta en Club Licoteca',
            'to'            => $profile->getEmail(),
            'html'          => 'email/sendValidatePreregistered',
            'text'          => 'email/sendValidatePreregisteredText',
            'substitutions' => array('%ROUTE%' => array($route)),
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

    // A convenience method to instantiate a form of the
    // specified class... unless the user has specified a
    // replacement class in app.yml. Sweet, no?
    protected function newForm($className, $object = null, $options = array()) {
        $key = "app_sfApplyPlugin_" . $className . "_class";

        $class = sfConfig::get($key, $className);

        if ($object !== null) {
            return new $class($object, $options);
        }

        return new $class;
    }

    // FUNCION QUE ESTA APUNTA A $HOST/webmobile y que sirve de prueba
    public function executeWebMobile(sfRequest $request) {
        $this->setLayout('mobileLayout');
        $this->form = $this->newForm('sfApplyCompleteApplicationForm');
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
