<?php

require_once sfConfig::get('sf_lib_dir').'/vendor/sendgrid-php/SendGrid_loader.php';

/**
 * tag actions.
 *
 * @package    elperro
 * @subpackage tag
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tagActions extends sfActions {

    /**
     * Executes tag action
     *
     * @param sfRequest $request A request object
     */
    public function executeTag(sfWebRequest $request) {
        $user = $this->getUser()->getGuardUser();
        $this->promo = $user->getAsset()->getPromos()->getFirst();

        $this->form = new epTagForm(array(), array('asset' => $user->getAssetId()));

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $values = $this->form->getValues();

                $newUser = false;

                if (!$tagUser = $values['user']) {
                    if (!$tagUser = $this->createUserByEmail($values['user_identifier'], $values['promocode']->getAsset())) {
                        return; /* Esto es para retornar a la misma vista (Success) mostrando el mensaje de error. */
                    }
                    $newUser = true;
                }

                if (!$promo = $this->validateTag($tagUser, $values['promocode'])) {
                    return; /* Esto es para retornar a la misma vista (Success) mostrando el mensaje de error. */
                }

                $this->registerTag($tagUser, $promo, $values['promocode'], null, $values['via'], false);

                $this->manageSubscription($tagUser, $promo->getAffiliateId(), $values['promocode']->getAssetId());

                $this->awardPoints('tag', $tagUser);

                $this->setConfirmationMessage($tagUser, $values['user_identifier']);

                $this->sendTagNotification($tagUser->getUserProfile(), $promo, $values['promocode']->getAsset());

                $this->redirect('give_tag');
            }
        }
    }

    protected function setConfirmationMessage(sfGuardUser $user, $identifier) {
        $msg = 'Se ha asignado exitosamente la visita al usuario: ';
        $first = $user->getFirstName();
        $last = $user->getLastName();

        if ($first && $last) {
            $msg = $msg . $user->getFullname() . ' (' . $identifier . ')';
        } else {
            $msg = $msg . $identifier;
        }

        $this->getUser()->setFlash('tag_success', $msg);
    }

    protected function setErrorMessage($msg) {
        $this->getUser()->setFlash('tag_error', $msg);
    }

    protected function validateTag(sfGuardUser $user, PromoCode $promocode) {
        $promo = $promocode->getPromo();

        if (!$promo->isActive()) {
            $this->setErrorMessage('La promoción indicada no está activa');
            return false;
        }

        if ($promo->getMaxUses() > 0 && $user->getCompleteParticipationsNumber($promo->getId()) == $promo->getMaxUses()) {
            $this->setErrorMessage('El usuario ya ha alcanzado el máximo de participaciones permitidas para la promoción');
            return false;
        }

        if ($promo->getMaxDailyTags() > 0 && $user->countTodayTickets($promo->getId()) == $promo->getMaxDailyTags()) {
            $this->setErrorMessage('El usuario ya ha alcanzado el máximo de visitas diarios permitidos para la promoción');
            return false;
        }

        return $promo;
    }

    protected function registerTag(sfGuardUser $user, Promo $promo, PromoCode $pcode, ValidationCode $vcode = null, $source = 'other', $cache = false) {
        if (!$card = $user->hasActiveCard($promo->getId())) {
            $card = new Card();
            $card->setUser($user);
            $card->setPromo($promo);
            $card->setStatus('active');
//            $card->setTotalTags(0);
//            $card->setActiveTags(0);
        }

        $ticket = new Ticket();

        $ticket->setUser($user);
        $ticket->setPromo($promo);
        $ticket->setPromoCode($pcode);
        $ticket->setAsset($pcode->getAsset());
        $ticket->setCache($cache);
        $ticket->setVia($source);

        if (!is_null($vcode)) {
            $vcode->setUsed(true);
            $vcode->setUser($user);
            $vcode->setUsedAt(date(DateTime::W3C));

            $ticket->setValidationCode($vcode);
        }

        $card->getTickets()->add($ticket);
//        $card->setTotalTags($card->getTotalTags()+1);
//        $card->setActiveTags($card->getActiveTags()+1);

        $card->save();

        return $card;
    }

    protected function manageSubscription(sfGuardUser $user, $affiliate, $asset) {
        $subscription = $user->isSubscribedTo(array('affiliate' => $affiliate, 'asset' => $asset));

        if (!$subscription) {
            $subscription = $user->isSubscribedTo(array('affiliate' => $affiliate), 'affiliate');
            if (!$subscription || !is_null($subscription->getAssetId())) {
                $subscription = new Subscription();
                $subscription->setUser($user);
                $subscription->setAffiliateId($affiliate);
                $subscription->setAssetId($asset);
            } else {
                if (is_null($subscription->getAssetId())) {
                    $subscription->setAssetId($asset);
                }
            }
        }

        $subscription->setStatus('active');
        $subscription->setLastInteraction(date(DateTime::W3C));

        $subscription->save();
    }

    protected function awardPoints($action, sfGuardUser $user) {
        $mainVpa = Doctrine::getTable('ValuePerAction')->retrieveByAction($action);
        $mainCurrency = Doctrine::getTable('Currency')->retrieveMain();
        $mainPocket = $user->getMainPocket();

        $this->registerFlow('income', $action, $user, $mainVpa, $mainCurrency, $mainPocket);

        return $mainVpa->getValue();
    }

    protected function registerFlow($direction, $description, sfGuardUser $user, ValuePerAction $vpa, Currency $currency, Pocket $pocket) {
        $flow = new Flow();

        $flow->setDirection($direction);
        $flow->setDescription($description);
        $flow->setUser($user);
        $flow->setAction($vpa);
        $flow->setCurrency($currency);
        $flow->setPocket($pocket);
        $flow->setAmount($vpa->getValue());

        $balance = strcasecmp('income', $direction) == 0 ? $pocket->getBalance() + $vpa->getValue() : $pocket->getBalance() - $vpa->getValue();

        $flow->setBalance($balance);

        $flow->save();
    }

    protected function createUserByEmail($email, $asset) {
        $buho = new epBuhoApi();
        $result = $buho->buhoGetUser(array('user' => $email));

        if (!$result['success']) { //Pre-Register
            $user = new sfGuardUser();
            $user->setEmailAddress($email);
            $user->setPreRegistered(true);
            $user->setIsActive(false);
            $user->addGroupByName('licoteca_users');
            $user->save();

            $profile = new UserProfile();
            $profile->setEmail($email);
            $profile->setValidate('pr' . self::createGuid());
            $profile->setUser($user);
            $profile->save();
            
            try {
                $this->sendUserVerificationAndCompleteDataMail($profile, $asset->getName());
                return $user;
            } catch (Exception $e) {
                $user->delete();
                //throw $e;
                $this->setErrorMessage('Ha ocurrido un error al intentar acreditar la visita, por favor intente nuevamente');
                return false;
            }
        } else {
            $user = $this->createUser($result['user'], $asset);
        }
        return $user;
    }

    protected function createUser($data, Asset $asset) {
        $user = new sfGuardUser();
        $user->addGroupByName('licoteca_users');

        $user = $this->updateUserWithBuhoData($data, $user);

        try {
            if (!$data['verified']) {
                $this->sendVerificationMail($user->getUserProfile());
            } else {
                $this->sendUserWelcome($user->getUserProfile(), $asset->getName());
            }
            return $user;
        } catch (Exception $e) {
            $user->delete();
            $this->setErrorMessage('Ha ocurrido un error al intentar registar al nuevo usuario, por favor intente nuevamente más tarde');
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

    protected function createParticipationRequest(sfGuardUser $user, Promo $promo, Asset $asset, $action, $withFeedback, $withSurveys) {
        $pr = new ParticipationRequest();

        $pr->setUser($user);
        $pr->setPromo($promo);
        $pr->setAsset($asset);
        $pr->setAction($action);
        $pr->setWithFeedback($withFeedback);

        if ($withSurveys) {
            foreach ($promo->getActiveSurveys() as $survey) {
                $pr->getSurveys()->add($survey);
            }
        }

        $pr->save();

        return $pr;
    }

    protected function sendUserVerificationAndCompleteDataMail($profile, $asset) {
        $routing = $this->getContext()->getConfiguration()->getRouting('frontend', array(
            'prefix' => '', // '' in case you want no script name displayed
            'host' => sfConfig::get('app_ep_domain', 'club.licoteca.com.ve'),
                ));

        $route = $routing->generate('user_complete_register', array('validate' => $profile->getValidate()), true);

        $this->mail(array(
            'subject'       => 'Bienvenido a Licoteca, verifica tu cuenta y completa tus datos',
            'teaser'        => 'Felicidades, solo falta un paso más para activar tu cuenta en Licoteca',
            'to'            => $profile->getEmail(),
            'html'          => 'email/sendCompleteData',
            'text'          => 'email/sendCompleteDataText',
            'substitutions' => array(
                    '%FULLNAME%'    => array(($profile->getFullname() ? $profile->getFullname() : $profile->getEmail())),
                    '%ASSET%'       => array($asset),
                    '%ROUTE%'       => array($route)
                ),
            'category'      => array('transactional', 'complete-register', 'biz'),
        ));
    }

    protected function sendUserWelcome($profile, $asset) {
        $this->mail(array(
            'subject'       => 'Bienvenido a Licoteca',
            'teaser'        => 'Felicidades, ahora tienes una cuenta en Licoteca',
            'to'            => $profile->getEmail(),
            'html'          => 'email/sendWelcome',
            'text'          => 'email/sendWelcomeText',
            'substitutions' => array('%FULLNAME%' => array(($profile->getFullname() ? $profile->getFullname() : $profile->getEmail()))),
            'category'      => array('transactional', 'welcome', 'biz'),
        ));
    }

    protected function sendTagNotification(UserProfile $profile, Promo $promo, Asset $asset, $withSurveys = true) {
        $routing = $this->getContext()->getConfiguration()->getRouting('frontend', array(
            'prefix' => '', // '' in case you want no script name displayed  
            'host' => sfConfig::get('app_domain','club.licoteca.com.ve'),
                ));
        
        $pr = $this->createParticipationRequest($profile->getUser(), $promo, $asset, 'tag', true, $withSurveys);
        
        $route = $routing->generate('survey_feedback', array(), true).'?h='.$pr->getHash();
        
        $this->mail(array(
            'subject'       => 'Has recibido una visita por ser cliente fiel en '.$asset->getName(),
            'teaser'        => 'Gracias por ser un cliente fiel en ' . $asset->getName(),
            'to'            => $profile->getEmail(),
            'html'          => 'email/sendTagNotification',
            'text'          => 'email/sendTagNotificationText',
            'substitutions' => array(
                    '%FULLNAME%'    => array(($profile->getFullname() ? $profile->getFullname() : $profile->getEmail())),
                    '%ASSET%'       => array($asset->getName()),
                    '%ROUTE%'       => array($route),
                ),
            'category'      => array('transactional', 'tag-notification', 'biz', 'biz-tag', 'tag'),
        ));
    }

    protected function sendVerificationMail($profile) {
        $routing = $this->getContext()->getConfiguration()->getRouting('frontend', array(
            'prefix' => '', // '' in case you want no script name displayed  
            'host' => sfConfig::get('app_domain', 'club.licoteca.com.ve'),
                ));

        $route = $routing->generate('validate', array('validate' => $profile->getValidate()), true);
        
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
            'category'      => array('transactional', 'verification', 'biz'),
        ));
    }

    protected function mail($options) {
        $required = array('subject', 'to', 'html');

        foreach ($required as $option) {
            if (!isset($options[$option])) {
                throw new sfException("Required option $option not supplied to tagActions::mail");
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
                throw new sfException("the maximun number of recipients is 1000 - tagActions::mail");
            }
            
            $mail->setTos($options['to']);
        } else {
            $mail->setTo($options['to']);
        }
        
        if (isset($options['substitutions'])) {
            foreach ($options['substitutions'] as $tag => $values) {
                $mail->addSubstitution($tag, $values);
            }
        }
        
        if (isset($options['sections'])) {
            foreach ($options['sections'] as $tag => $value) {
                $mail->addSection($tag, $value);
            }
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
        $from = sfConfig::get('app_mailing_config_from', false);

        if (!$from) {
            throw new Exception('app_mailing_config_from is not set');
        }
        // i18n the full name
        return array('email' => $from['email'], 'fullname' => sfContext::getInstance()->getI18N()->__($from['fullname']));
    }

    static protected function createGuid() {
        $guid = "";

        for ($i = 0; ($i < 8); $i++) {
            $guid .= sprintf("%02x", mt_rand(0, 255));
        }

        return $guid;
    }

}
