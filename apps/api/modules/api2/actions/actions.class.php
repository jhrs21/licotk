<?php

/**
 * api2 actions.
 *
 * @package    elperro
 * @subpackage api2
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */

class api2Actions extends baseEpApiActions {

    public function executeGetCodes(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;

        $route_params = $this->getRoute()->getParameters();

        $apiCredentials = array('apikey' => $route_params['apikey'], 'name' => $route_params['name']);

        $requiredParams = array();

        if (!$this->validateApiRules($apiCredentials, $requiredParams, $request)) {
            return 'Error';
        }

        $asset_alpha_id = $route_params['asset'];
        $promo_alpha_id = $route_params['promo'];
        $quantity = $request->getParameter('quantity', 10);

        $promo_code = Doctrine_Query::create()
                ->from('PromoCode pc')
                ->leftJoin('pc.Promo p')
                ->leftJoin('pc.Asset a')
                ->where('p.alpha_id=?', $promo_alpha_id)
                ->andWhere('a.alpha_id=?', $asset_alpha_id)
                ->andWhere('pc.digital=1')
                ->andWhere('pc.type="validation_required"')
                ->fetchOne();
                
        if (!$promo_code) {
            $this->result['error'] = $this->getErrorFromList('api311');
            return 'Error';
        }

        $this->result['promo_code'] = $promo_code->getAlphaId();

        $this->result['vcodes'] = array();

        $collection = new Doctrine_Collection('ValidationCode');

        for ($i = 0; $i < $quantity; $i++) {
            $validation_code = new ValidationCode();
            $validation_code->setActive(true);
            $validation_code->setPromoCodeId($promo_code->getId());
            $collection->add($validation_code);
        }

        $collection->save();

        foreach ($collection as $validation_code) {
            $this->result['vcodes'][] = $validation_code->getCode();
        }

        $this->result['success'] = 1;
    }

    public function executeGetAsset(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;

        $route_params = $this->getRoute()->getParameters();

        $apiCredentials = array('apikey' => $route_params['apikey'], 'name' => $route_params['name']);

        $requiredParams = array();

        if (!$this->validateApiRules($apiCredentials, $requiredParams, $request)) {
            return 'Error';
        }

        if (!$asset = $this->validateAsset($route_params['asset'])) {
            return 'Error';
        }

        $this->result['asset'] = $asset->asArray(true);
        $this->result['asset']['refresh_time'] = sfConfig::get('app_ep_refresh_time',60);

        $this->result['success'] = 1;
    }

    public function executeUserSignUp(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;
        
        $route_params = $this->getRoute()->getParameters();

        $apiCredentials = array('apikey' => $route_params['apikey'], 'name' => $route_params['name']);

        $requiredParams = array('first_name', 'last_name', 'email', 'password', 'gender', 'birthdate', 'municipality_id', 'phone');

        if (!$this->validateApiRules($apiCredentials, $requiredParams, $request)) {
            return 'Error';
        }
        
        $values = array();

        $values['first_name'] = $request->getParameter('first_name');
        $values['last_name'] = $request->getParameter('last_name');
        $values['gender'] = $request->getParameter('gender');
        $values['birthdate'] = $request->getParameter('birthdate');
        $values['id_number'] = $request->getParameter('id_number');
        $values['email'] = $request->getParameter('email');
        $values['password'] = $request->getParameter('password');
        $values['municipality_id'] = $request->getParameter('municipality_id');
        $values['phone'] = $request->getParameter('phone');

        $form = new epUserApplyApiForm(array(), array(), false);

        $form->bind($values);
        
        if ($form->isValid()) {
            $values = $form->getValues();
            
            $buhoValues = array();
            $buhoValues['fullname'] = $values['first_name'] . ' ' . $values['last_name'];
            $buhoValues['email'] = $values['email'];
            $buhoValues['password'] = $values['password'];
            $buhoValues['birthday'] = $values['birthdate'];
            $buhoValues['identifier'] = $values['id_number'];
            $buhoValues['gender'] = $values['gender'];
            $buhoValues['municipality_id'] = $values['municipality_id'];
            $buhoValues['phone'] = $values['phone'];

            //$buho = new epBuhoApi();
            //$result = $buho->buhoCreateUser($buhoValues);
	    $validator = Util::GenSecret(16,0);
	    $hash = strtolower(Util::GenSecret(32,0));
            $result = array("success" => 1, "user" => array("validator"=>$validator, "hash"=>$hash));

            //  Verificar si el usuario es registrado en El Buho sin inconvenientes
            if (!$result['success']) {
                $errorCode = $result['error']['code'];
                if (strcasecmp($errorCode, '10206') == 0) { /* The user is already registered at tudescuenton.com */
                    $this->result['error'] = $this->getErrorFromList('api205');
                    return 'Error';
                }

                $this->result['error'] = $this->getErrorFromList('api900');
                return 'Error';                
            }
            
            //  Registrar el nuevo usuario de forma local y 
            //  asignar el hash retornado por El Buho
            $guid = "n" . $result['user']['validator'];
            $form->setValidate($guid);
            $form->setUserHash($result['user']['hash']);
            $form->save();

            try {
                $profile = $form->getObject();
                $this->sendVerificationMail($profile);

                $this->result['success'] = 1;
                $this->result['user'] = $profile->getUser()->asArray();
                $this->result['user']['municipality'] = $profile->getUser()->getUserProfile()->getMunicipality()->getName();
            } 
            catch (Exception $e) {
                $profile = $form->getObject();
                $user = $profile->getUser();
                $user->delete();

                $this->result['error'] = $this->getErrorFromList('api100');
            }
        } 
        else {
            foreach ($form->getErrorSchema()->getErrors() as $e) {
                $this->result['error'] = array('code' => 'api201', 'type' => 'UserError', 'message' => $e->__toString());
                return 'Error';
            }
        }
    }

    public function executeUserLogin(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;

        $route_params = $this->getRoute()->getParameters();

        $apiCredentials = array('apikey' => $route_params['apikey'], 'name' => $route_params['name']);

        $requiredParams = array('email', 'password');
        if (!$this->validateApiRules($apiCredentials, $requiredParams, $request)) {
            return 'Error';
        }

        $email = $request->getParameter('email');
        $password = $request->getParameter('password');

        $buhoValues = array('email' => $email, 'password' => $password);
        $buho = new epBuhoApi();

        // $result = $buho->buhoLogin($buhoValues);
        // CODIGO PARA SUSTITUIR AL BUHO
        $userObj = Doctrine::getTable('sfGuardUser')->findOneBy('email_address', $email);
        if ($userObj) {
            //var_dump(sha1($userObj->getSalt().$password) == $userObj->getPassword());die();
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
                    error_log("\n IsActive()"."\n",3, "/var/tmp/bug-lt.log");
                    $result = array('success' => 0, 'error' => array('code' => '', 'message' => ''));
                    $result['error']['code'] = '10608';    
                }
            }else{
                error_log("ENTRANDO AL OTRO ELSE"."\n",3, "/var/tmp/bug-lt.log");
                $result = array('success' => 0, 'error' => array('code' => '', 'message' => ''));
                $result['error']['code'] = '00000';
            }
        }
        // var_dump($result);die;
        // FIN DE CODIGO PARA SUSTITUIR AL BUHO
        // var_dump($result['success']);die;
        if (!$result['success']) {
            $user = Doctrine::getTable('sfGuardUser')->findOneBy('email_address', $email);
            
            $errorCode = $result['error']['code'];
            
            if (strcasecmp($errorCode, '00000') == 0) { /* Invalid email or password */
                if ($user && $user->getPreRegistered()) {
                    try {
                        $this->sendPreRegisteredVerificationRemainderMail($user->getUserProfile());
                        $this->result['error'] = $this->getErrorFromList('api213');
                        return 'Error';
                    } catch (Exception $e) {
                        $this->result['error'] = $this->getErrorFromList('api100');
                        return 'Error';
                    }
                }
                
                $this->result['error'] = $this->getErrorFromList('api202');
                return 'Error';
            }
            
            if (strcasecmp($errorCode, '10608') == 0) { /* Not verified */
                if ($user) { /* Check if the user exists in LT db */
                    try {
                        $this->sendVerificationMail($user->getUserProfile());

                        $this->result['success'] = 1;
                        $this->result['user'] = $user->asArray();
                    } 
                    catch (Exception $e) {
                        $this->result['error'] = $this->getErrorFromList('api100');
                    }
                }
                else {
                    /* Send verification email for an user no registered in LT db and return an error message */
                    // $result = $buho->buhoGetUser(array('user' => $email));
                    $result = array("success" => 0); // ESTO YA NO DEBERIA PASAR.

                    if (!$result['success']) {
                        $this->result['error'] = $this->getErrorFromList('api202');
                        return 'Error';
                    }

                    try {
                        $this->sendVerificationMailUnregistered($result['user']);
                    } 
                    catch (Exception $e) {
                        $this->result['error'] = $this->getErrorFromList('api100');
                    }

                    $this->result['error'] = $this->getErrorFromList('api204');
                    return 'Error';
                }
            }
            
            $this->result['error'] = $this->getErrorFromList('api901');
            return 'Error';
        }
        
        $result['user']['password'] = $password; /* set the password value for the new user */
        
        if (!$user = Doctrine::getTable('sfGuardUser')->findOneBy('email_address', $email)) {            
            if (!$user = $this->createUser($result['user'])) {
                $this->result['error'] = $this->getErrorFromList('api900');
                return 'Error';
            }
        } 
        else {
            $user = $this->updateUser($result['user'], $user);
        }

        $this->result['success'] = 1;
        $this->result['user'] = $user->asArray();
        $this->result['user']['member_since'] = $user->getCreatedAt();
        $this->result['user']['municipality'] = $user->getUserProfile()->getMunicipality()->getName();
        $this->result['user']['phone'] = $user->getUserProfile()->getPhone();
    }

    public function executeUserUpdate(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;

        $route_params = $this->getRoute()->getParameters();

        $apiCredentials = array('apikey' => $route_params['apikey'], 'name' => $route_params['name']);

        $requiredParams = array('password');

        if (!$this->validateApiRules($apiCredentials, $requiredParams, $request)) {
            return 'Error';
        }

        if (!$user = Doctrine::getTable('sfGuardUser')->findOneByAlphaId($route_params['user_id'])) {
            $this->result['error'] = $this->getErrorFromList('api200');
            return 'Error';
        }
        
        //$buho = new epBuhoApi();
        //$result = $buho->buhoLogin(array('email' => $user->getEmailAddress(), 'password' => $request->getParameter('password')));
        $result = array("success" => 1);

        //if (!$user->checkPassword($request->getParameter('password'))) {
        if (!$result['success']) {
            $this->result['error'] = $this->getErrorFromList('api203');
            return 'Error';
        }

        $values = array();

        $values['id'] = $user->getUserProfile()->getId();
        $values['first_name'] = $request->getParameter('first_name');
        $values['last_name'] = $request->getParameter('last_name');
        $values['gender'] = $request->getParameter('gender');
        $values['birthdate'] = $request->getParameter('birthdate');
        $values['id_number'] = $request->getParameter('id_number');
        $values['phone'] = $request->getParameter('phone');
        $values['municipality_id'] = $request->getParameter('municipality_id');

        $formOptions = array();

        if ($request->getParameter('npassword', false)) {
            $values['password'] = $request->getParameter('npassword');
            $formOptions['update_password'] = true;
        }

        $form = new epUserApplyApiForm($user->getUserProfile(), $formOptions, false);

        $form->bind($values);
        if ($form->isValid()) {
            $formValues = $form->getValues();

            $buhoValues = array();
            $buhoValues['user'] = $user->getHash(); /* this can be replaced by $user->getEmailAddress() */
            $buhoValues['fullname'] = $formValues['first_name'] . ' ' . $formValues['last_name'];
            $buhoValues['identifier'] = array_key_exists('id_number', $formValues) ? (is_null($formValues['id_number']) ? '' : $formValues['id_number']) : '';
            $buhoValues['mobile_phone'] = array_key_exists('phone', $formValues) ? (is_null($formValues['phone']) ? '' : $formValues['phone']) : '';
            $buhoValues['birthday'] = $formValues['birthdate'];
            $buhoValues['gender'] = $formValues['gender'];
            $buhoValues['municipality'] = $formValues['municipality_id'];
            
            //$result = $buho->buhoUpdateUser($buhoValues);

            if (!$result['success']) {
                $errorCode = $result['error']['code'];
                
                if (strcasecmp($errorCode, '10608') == 0) { /* Not verified */
                    $this->result['error'] = $this->getErrorFromList('api208');
                    return 'Error';
                }
                
                $this->result['error'] = $this->getErrorFromList('api209');
                return 'Error';
            }

            if (array_key_exists('password', $formValues)) {
                //$result = $buho->buhoResetPassword($buhoValues);

                if (!$result['success']) {
                    $this->result['error'] = $this->getErrorFromList('api210');
                    return 'Error';
                }

                //$buhoValues['validator'] = $result['user']['validator'];
                //$buhoValues['new_password'] = $formValues['password'];

		//////////////////////////////////////// AQUI TE QUEDASTE
//		$buhoValues['new_password'] = ;

                //$result = $buho->buhoUpdatePassword($buhoValues);

                if (!$result['success']) {
                    $this->result['error'] = $this->getErrorFromList('api210');
                    return 'Error';
                }
            }

            $form->save();

            $profile = $form->getObject();

            $this->result['success'] = 1;

            $this->result['user'] = $profile->getUser()->asArray();
            $this->result['user']['member_since'] = $profile->getUser()->getCreatedAt();
            $this->result['user']['municipality'] = $profile->getUser()->getUserProfile()->getMunicipality()->getName();
        } 
        else {
            foreach ($form->getErrorSchema()->getErrors() as $e) {
                $this->result['error'] = array('code' => 'api201', 'type' => 'UserError', 'message' => $e->__toString());
                return 'Error';
            }
        }
    }

    public function executeUserStuff(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;

        $route_params = $this->getRoute()->getParameters();

        $apiCredentials = array('apikey' => $route_params['apikey'], 'name' => $route_params['name']);

        $requiredParams = array();

        if (!$this->validateApiRules($apiCredentials, $requiredParams, $request)) {
            return 'Error';
        }

        if (!$user = Doctrine::getTable('sfGuardUser')->findOneByAlphaId($route_params['user_id'])) {
            $this->result['error'] = $this->getErrorFromList('api200');
            return 'Error';
        }

        $this->result['success'] = 1;

        $this->result['pts_lt'] = (int) $user->getMainPocket()->getBalance();

        $cards = $user->getCards();

        foreach ($cards as $card) {
            $this->result['cards'][$card->getAlphaId()] = $card->asArray(true, true);
        }
    }

    public function executeUserTicket(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;

        $route_params = $this->getRoute()->getParameters();

        $apiCredentials = array('apikey' => $route_params['apikey'], 'name' => $route_params['name']);

        $requiredParams = array('promocode',);

        if (!$this->validateApiRules($apiCredentials, $requiredParams, $request)) {
            return 'Error';
        }

        if (!$user = Doctrine::getTable('sfGuardUser')->findOneByAlphaId($route_params['user_id'])) {
            $this->result['error'] = $this->getErrorFromList('api200');
            return 'Error';
        }
        
        if (!$promocode = $this->validatePromoCode($request->getParameter('promocode'))) {
            return 'Error';
        }

        if (!$result = $this->validateTag($user, $promocode, $request->getParameter('vcode', ''))) {
            return 'Error';
        }

        $card = $this->registerTag($user, $result['promo'], $result['promocode'], $result['vcode'], 'app', $request->getParameter('cache', 0));

        $this->manageSubscription($user, $result['promo']->getAffiliateId(), $result['promocode']->getAssetId());

        $pts = $this->awardPoints('tag', $user);

        $this->result['success'] = 1;

        $this->result['pts_lt'] = (int) $user->getMainPocket()->getBalance();

        $this->result['message'] = '¡Hemos registrado tu Tag exitosamente!.|Has sido premiado con ' . $pts . ' ptos LT por realizar un Tag.|¿Qué tal ha sido tu experiencia';

        $this->result['message'] = $this->result['message'] . ' ' .
                ($result['promocode']->getAsset()->isPlace() ? 'en' : 'con') . ' ' . $result['promocode']->getAsset() . '?';

        $this->result['card'] = $card->asArray(true, true, null, true);
        
        $this->sendSurveyParticipationRequest($user->getUserProfile(), $result['promo'], $result['promocode']->getAsset());
    }

    public function executeUserAcquire(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;

        $route_params = $this->getRoute()->getParameters();

        $apiCredentials = array('apikey' => $route_params['apikey'], 'name' => $route_params['name']);

        $requiredParams = array('cardid', 'conditionid');

        if (!$this->validateApiRules($apiCredentials, $requiredParams, $request)) {
            return 'Error';
        }

        if (!$user = Doctrine::getTable('sfGuardUser')->findOneByAlphaId($route_params['user_id'])) {
            $this->result['error'] = $this->getErrorFromList('api200');
            return 'Error';
        }
        
        if (!$this->validateUserActiveAndDataComplete($user)) {
            return 'Error';
        }

        if (!$result = $this->validateAcquiringCoupon($user, $request->getParameter('cardid'), $request->getParameter('conditionid'))) {
            return 'Error';
        }
        
        if ($result['promo']->getRedeemAutomated()) {
            if (!$this->doAutomaticRedeem($user, $result['promo'], $result['prize'], $result['card'])) {
                return 'Error';
            }
        }
        
        $card = $result['card'];

        if (!$coupon = Doctrine::getTable('Coupon')->findOneByCardId($result['card']->getId())) {
            $couponResult = $this->registerCoupon($user, $result['card'], $result['promo'], $result['prize']);
            $coupon = $couponResult['coupon'];
            $card = $couponResult['card'];

            if ($couponResult['newcard']) {
                $this->result['newcard'] = $couponResult['newcard']->asArray();
            }
        }
        
        if ($result['promo']->getRedeemAutomated()) {
            $coupon = $this->redeemCoupon($coupon);
            $card = $coupon->getCard();
            
            $pts = $this->awardPoints('redeem', $user);
            
            $this->result['message'] = '¡Se ha canjeado tu premio exitosamente en "'.$card->getPromo()->getAffiliate().'"!.|Has sido premiado con ' . $pts . ' ptos LT por realizar un Canje.';
        }
        
        $this->result['card'] = $card->asArray();
        $this->result['coupon'] = $coupon->asArray();
        $this->result['success'] = 1;
    }

    public function executeUserFeedback(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;

        $route_params = $this->getRoute()->getParameters();

        $apiCredentials = array('apikey' => $route_params['apikey'], 'name' => $route_params['name']);

        $requiredParams = array('valoration', 'after_action', 'promo');

        if (!$this->validateApiRules($apiCredentials, $requiredParams, $request)) {
            return 'Error';
        }

        if (!$user = Doctrine::getTable('sfGuardUser')->findOneByAlphaId($route_params['user_id'])) {
            $this->result['error'] = $this->getErrorFromList('api200');
            return 'Error';
        }

        $valoration = $request->getParameter('valoration');

        if (!is_numeric($valoration)) {
            $this->result['error'] = $this->getErrorFromList('api003');
            $search = array('%param%', '%value%');
            $replace = array('valoration', $valoration);
            $this->result['error']['message'] = str_replace($search, $replace, $this->result['error']['message']);
            return 'Error';
        }

        $valoration = intval($valoration);

        if ($valoration < 0 || $valoration > 2) {
            $this->result['error'] = $this->getErrorFromList('api002');
            $search = array('%param%', '%value%');
            $replace = array('valoration', $valoration);
            $this->result['error']['message'] = str_replace($search, $replace, $this->result['error']['message']);
            return 'Error';
        }

        $message = $request->getParameter('msg', false);

        if ($message && strlen($message) > 255) {
            $this->result['error'] = $this->getErrorFromList('api1000');
            $search = array('%message%');
            $replace = array('La longitud del mensaje superar los 255 caracteres máximos');
            $this->result['error']['message'] = str_replace($search, $replace, $this->result['error']['message']);
            return 'Error';
        }
        else if ($message) {
            $message = urldecode($message);
        }

        $action = $request->getParameter('after_action');

        if (strcasecmp($action, 'tag') != 0 && strcasecmp($action, 'redeem') != 0) {
            $this->result['error'] = $this->getErrorFromList('api002');
            $search = array('%param%', '%value%');
            $replace = array('after_action', $action);
            $this->result['error']['message'] = str_replace($search, $replace, $this->result['error']['message']);
            return 'Error';
        }

        $feedback = new Feedback();

        if (strcasecmp($action, 'tag') == 0) {
            if (!$promocode = Doctrine::getTable('PromoCode')->retrievePromoCode($request->getParameter('promo'))) {
                $this->result['error'] = $this->getErrorFromList('api300');
                return 'Error';
            }

            $feedback->setAssetId($promocode->getAssetId());

            $promo = $promocode->getPromo();

            $this->result['message'] = 'Hemos registrado exitosamente tu opinión sobre: ' . $promocode->getAsset() . '.';
        } else {
            if (!$promo = Doctrine::getTable('Promo')->findOneByAlphaId($request->getParameter('promo'))) {
                $this->result['error'] = $this->getErrorFromList('api310');
                return 'Error';
            }

            $this->result['message'] = 'Hemos registrado exitosamente tu opinión.';
        }

        $feedback->setUserId($user->getId());
        $feedback->setValoration($valoration);
        $feedback->setMessage($message);
        $feedback->setAction($action);
        $feedback->setAffiliateId($promo->getAffiliateId());
        $feedback->setPromoId($promo->getId());

        $feedback->save();

        $pts = $this->awardPoints('feedback', $user);

        $this->result['success'] = 1;

        $this->result['message'] = $this->result['message'] . '|Has sido premiado con ' . $pts . ' ptos LT por compartir tu opinión.';

        $this->result['pts_lt'] = (int) $user->getMainPocket()->getBalance();
    }

    public function executePlacesLeading(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;

        $route_params = $this->getRoute()->getParameters();

        $apiCredentials = array('apikey' => $route_params['apikey'], 'name' => $route_params['name']);

        $requiredParams = array();

        if (!$this->validateApiRules($apiCredentials, $requiredParams, $request)) {
            return 'Error';
        }

        if (!$user = Doctrine::getTable('sfGuardUser')->findOneByAlphaId($route_params['user_id'])) {
            $this->result['error'] = $this->getErrorFromList('api200');
            return 'Error';
        }

        $affiliates = Doctrine::getTable('Affiliate')->retrieveWithActivePromos();

        $this->result['success'] = 1;

        foreach ($affiliates as $affiliate) {
            $this->result['affiliates'][$affiliate->getAlphaId()] = $affiliate->asArray(false, false);
        }
    }

    public function executePlacesSearch(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;

        $route_params = $this->getRoute()->getParameters();
        $apiCredentials = array('apikey' => $route_params['apikey'], 'name' => $route_params['name']);
        $requiredParams = array();

        if (!$this->validateApiRules($apiCredentials, $requiredParams, $request)) {
            return 'Error';
        }

        if (!$user = Doctrine::getTable('sfGuardUser')->findOneByAlphaId($route_params['user_id'])) {
            $this->result['error'] = $this->getErrorFromList('api200');
            return 'Error';
        }

        $table = Doctrine::getTable('Affiliate');
        
        $query = $table->addHasAssetTypeQuery('place');
        $query = $table->addWithActivePromosQuery($query);

        if ($categoryId = $request->getParameter('category', false)) {
            if (!$category = Doctrine::getTable('Category')->findOneBy('alpha_id', $categoryId)) {
                $this->result['error'] = $this->getErrorFromList('api600');
                return 'Error';
            }
            $query = $table->addByCategoryQuery($category->getId(), $query);
        }

        if ($keyword = $request->getParameter('keyword', false)) {
            $query = $table->addByKeywordQuery($keyword, $query);
            $query->leftJoin('aa.Location l');
            $query->orWhere('l.address LIKE ?', array('%' . $keyword . '%'));
        }

        if (($lat = $request->getParameter('lat', false)) && ($long = $request->getParameter('long', false))) {
            $query = $table->addWithAssetsInRangeQuery($lat, $long, sfConfig::get('app_mobile_app_search_distance_radius'), $query);
        }

        $limit = $request->getParameter('limit', sfConfig::get('app_ep_max_affiliates_per_page'));
        $offset = $request->getParameter('offset', 0);

        $query->limit($limit);
        $query->offset($offset);
        $count = $query->count();

        $affiliates = $query->execute();

        if ($affiliates->count()) {
            $this->result['affiliates'] = array();
            foreach ($affiliates as $affiliate) {
                $this->result['affiliates'][$affiliate->getAlphaId()] = $affiliate->asArray(false, false);
            }
        }

        $this->result['pagination'] = array(
            'limit' => $limit,
            'offset' => $offset + $limit,
            'more' => $count > $offset + $limit ? 1 : 0
        );

        $this->result['success'] = 1;
    }

    public function executePlacesNearby(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;

        $route_params = $this->getRoute()->getParameters();

        $apiCredentials = array('apikey' => $route_params['apikey'], 'name' => $route_params['name']);

        $requiredParams = array('lat', 'long');

        if (!$this->validateApiRules($apiCredentials, $requiredParams, $request)) {
            return 'Error';
        }

        if (!$user = Doctrine::getTable('sfGuardUser')->findOneByAlphaId($route_params['user_id'])) {
            $this->result['error'] = $this->getErrorFromList('api200');
            return 'Error';
        }

        $table = Doctrine::getTable('Asset');
        $lat = $request->getParameter('lat');
        $long = $request->getParameter('long');
        $dist = $request->getParameter('distance', sfConfig::get('app_mobile_app_search_distance_radius'));

        $query = $table->addByDistanceQuery($lat, $long, $dist, true);

        $query = $table->addByTypeQuery('place', $query);

        $query = $table->addByParticipationInActivePromosQuery($query);

        $this->result['success'] = 1;

        $places = $query->execute();

        if ($places->count()) {
            $this->result['places'] = array();
            foreach ($places as $place) {
                $this->result['places'][$place->getAlphaId()] = $place->asArray();
            }
        }
    }

    public function executePlace(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;

        $route_params = $this->getRoute()->getParameters();

        $apiCredentials = array('apikey' => $route_params['apikey'], 'name' => $route_params['name']);

        $requiredParams = array('affiliateid');

        if (!$this->validateApiRules($apiCredentials, $requiredParams, $request)) {
            return 'Error';
        }

        if (!$user = Doctrine::getTable('sfGuardUser')->findOneByAlphaId($route_params['user_id'])) {
            $this->result['error'] = $this->getErrorFromList('api200');
            return 'Error';
        }

        if (!$affiliate = Doctrine::getTable('Affiliate')->findOneByAlphaId($request->getParameter('affiliateid'))) {
            $this->result['error'] = $this->getErrorFromList('api700');
            return 'Error';
        }

        $params = array();

        $params['type'] = 'place';

        if ($request->hasParameter('city')) {
            $params['city'] = $request->getParameter('city');
        }

        if ($request->hasParameter('lat') && $request->hasParameter('long')) {
            $params['lat'] = $request->getParameter('lat');
            $params['long'] = $request->getParameter('long');

            $params['distance'] = $request->getParameter('distance', 0);
        }

        $this->result['success'] = 1;

        $this->result['affiliate'] = $affiliate->asArray($params, true);
    }

    public function executeBrandsLeading(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;

        $route_params = $this->getRoute()->getParameters();

        $apiCredentials = array('apikey' => $route_params['apikey'], 'name' => $route_params['name']);

        $requiredParams = array();

        if (!$this->validateApiRules($apiCredentials, $requiredParams, $request)) {
            return 'Error';
        }

        if (!$user = Doctrine::getTable('sfGuardUser')->findOneByAlphaId($route_params['user_id'])) {
            $this->result['error'] = $this->getErrorFromList('api200');
            return 'Error';
        }

        $affiliates = Doctrine::getTable('Affiliate')->retrieveWithActivePromos('brand');

        $this->result['success'] = 1;

        foreach ($affiliates as $affiliate) {
            $brands = $affiliate->getBrands();
            foreach ($brands as $brand) {
                $this->result['brands'][$brand->getAlphaId()] = $brand->asArray();
            }
        }
    }

    public function executeBrandsSearch(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;

        $route_params = $this->getRoute()->getParameters();
        $apiCredentials = array('apikey' => $route_params['apikey'], 'name' => $route_params['name']);
        $requiredParams = array();

        if (!$this->validateApiRules($apiCredentials, $requiredParams, $request)) {
            return 'Error';
        }

        if (!$user = Doctrine::getTable('sfGuardUser')->findOneByAlphaId($route_params['user_id'])) {
            $this->result['error'] = $this->getErrorFromList('api200');
            return 'Error';
        }

        $table = Doctrine::getTable('Asset');
        $query = $table->addByTypeQuery('brand');
        $query = $table->addWithActivePromosQuery($query);

        if ($categoryId = $request->getParameter('category', false)) {
            if (!$category = Doctrine::getTable('Category')->findOneBy('alpha_id', $categoryId)) {
                $this->result['error'] = 'Identificador de categoria inválido';
                return 'Error';
            }

            $query = $table->addByCategoryQuery($category->getId(), $query);
        }

        if ($keyword = $request->getParameter('keyword', false)) {
            $query = $table->addByKeywordQuery($keyword, $query);
        }

        $limit = $request->getParameter('limit', sfConfig::get('app_ep_max_affiliates_per_page'));
        $offset = $request->getParameter('offset', 0);

        $query->limit($limit);
        $query->offset($offset);
        $count = $query->count();

        $affiliates = $query->execute();

        $result = array();
        foreach ($affiliates as $affiliate) {
            $result[$affiliate->getAlphaId()] = $affiliate->asArray(false, false);
        }

        if (count($result)) {
            $this->result['affiliates'] = $result;
        }
        
        $this->result['pagination'] = array(
            'limit' => $limit,
            'offset' => $offset + $limit,
            'more' => $count > $offset + $limit ? 1 : 0
        );
        
        $this->result['success'] = 1;
    }

    public function executeBrand(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;

        $route_params = $this->getRoute()->getParameters();

        $apiCredentials = array('apikey' => $route_params['apikey'], 'name' => $route_params['name']);

        $requiredParams = array('brand');

        if (!$this->validateApiRules($apiCredentials, $requiredParams, $request)) {
            return 'Error';
        }

        if (!$user = Doctrine::getTable('sfGuardUser')->findOneByAlphaId($route_params['user_id'])) {
            $this->result['error'] = $this->getErrorFromList('api200');
            return 'Error';
        }

        if (!$brand = Doctrine::getTable('Asset')->findOneByAlphaId($request->getParameter('brand'))) {
            $this->result['error'] = $this->getErrorFromList('api701');
            return 'Error';
        }

        $this->result['success'] = 1;
        $this->result['brand'] = $brand->asArray(true);
    }

    public function executeCheckInTag(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;

        $route_params = $this->getRoute()->getParameters();
        $apiCredentials = array('apikey' => $route_params['apikey'], 'name' => $route_params['name']);
        $requiredParams = array();
	    error_log("\nentrando a executeCheckInTag"." - ".date("Y-m-d H:i:s")."\n",3, "/var/tmp/error-email-licoteca.log");

        if (!$this->validateApiRules($apiCredentials, $requiredParams, $request)) {
		error_log("saliendo por error con las reglas del API\n",3,"/var/tmp/error-email-licoteca.log");
            return 'Error';
        }

        if (!$asset = $this->validateAsset($route_params['asset'])) {
		error_log("saliendo por error en el Asset\n",3,"/var/tmp/error-email-licoteca.log");
            return 'Error';
        }

        $email = $request->getParameter('email', false);
        $membershipCardId = $request->getParameter('mcard', false);
        $tagSource = 'tablet';

        if (!$membershipCardId && !$email) {
            $this->result['error'] = $this->getErrorFromList('api001');
		    error_log("saliendo por error api001\n",3,"/var/tmp/error-email-licoteca.log");
            return 'Error';
        } else if ($membershipCardId) {
	    error_log("antes de validar la MC\n",3,"/var/tmp/error-email-licoteca.log");
            if (!$user = $this->validateMembershipCardForTag($membershipCardId, $asset, $email)) {
                error_log("saliendo por error en el MembershipCard $membershipCardId (PUEDE SER: por usuario con 2 tarjetas) en $asset\n",3,"/var/tmp/error-email-licoteca.log");
                return 'Error';
            }
	    error_log("paso la validacion de la MC\n",3,"/var/tmp/error-email-licoteca.log");
            $tagSource = 'tablet_card';
        } else {
            if (!$user = $this->handleUserByEmail($email, $asset)) {
                error_log("saliendo por error en el User $email\n",3,"/var/tmp/error-email-licoteca.log");
                return 'Error';
            }
            $tagSource = 'tablet_email';
        }
	   error_log("$user == ".$user->getEmailAddress()."\n",3,"/var/tmp/error-email-licoteca.log");
        
        if (!$promocode = $this->validatePromoCode($route_params['promocode'])) {
            return 'Error';
        }

        if (!$result = $this->validateTag($user, $promocode, $route_params['vcode'], $asset)) {
            return 'Error';
        }

        $card = $this->registerTag($user, $result['promo'], $result['promocode'], $result['vcode'], $tagSource);

        $this->manageSubscription($user, $result['promo']->getAffiliateId(), $result['promocode']->getAssetId());

        $pts = $this->awardPoints('tag', $user);

        $this->result['card'] = $card->asArray(true, false, null, true);
        $this->result['user'] = $user->asArray();
        
        if ($user->getPreRegistered()) {
            $this->result['message'] = '¡'.$user->getEmailAddress().' tu Tag ha sido registrado exitosamente! Recuerda verificar tu email para completar tu registro';
        }
        else {
            $this->result['message'] = '¡'.$user->getFullname().' tu Tag ha sido registrado exitosamente!';
        }
        
        if ($surveys = $this->getSurveysAsArray($result['promo'], $asset)) {
            $this->result['surveys'] = $surveys;
        }
        
        $this->result['success'] = 1;
        error_log("antes de enviar el correo a ".$user->getEmailAddress()." en ".$asset->getName()." - ".date("Y-m-d H:i:s")."\n",3,"/var/tmp/error-email-licoteca.log");
        $this->sendTagNotification($user->getUserProfile(), $result['promo'], $asset, true);
	    error_log("ya mando el correo a: ". $user->getEmailAddress()."\n",3, "/var/tmp/error-email-licoteca.log");
    }

    public function executeCheckInStuff(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;

        $route_params = $this->getRoute()->getParameters();

        $apiCredentials = array('apikey' => $route_params['apikey'], 'name' => $route_params['name']);

        $requiredParams = array();

        if (!$this->validateApiRules($apiCredentials, $requiredParams, $request)) {
            return 'Error';
        }

        if (!$asset = $this->validateAsset($route_params['asset'])) {
            return 'Error';
        }

        $email = $request->getParameter('email', false);
        $mCardId = $request->getParameter('mcard', false);

        if (!$mCardId && !$email) {
            $this->result['error'] = $this->getErrorFromList('api001');
            $this->result['error']['message'] .= ' (email o mcard)';
            return 'Error';
        } else if ($mCardId) {
            if (!$mCard = $this->validateMembershipCard($mCardId, true)) {
                return 'Error';
            }
            $user = $mCard->getUser();
        } else {
            if (!$user = Doctrine::getTable('sfGuardUser')->findOneBy('email_address', $email)) {
                $this->result['error'] = $this->getErrorFromList('api207');
                return 'Error';
            }
        }
        
        if (!$this->validateUserActiveAndDataComplete($user)) {
            return 'Error';
        }

        $this->result['success'] = 1;
        $this->result['user'] = $user->asArray();

        $cards = $user->getCardsRelatedTo($asset->getId());
        foreach ($cards as $card) {
            $this->result['cards'][$card->getStatus()][$card->getAlphaId()] = $card->asArray(true, false);
        }
    }

    public function executeCheckInRedeem(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;

        $route_params = $this->getRoute()->getParameters();

        $apiCredentials = array('apikey' => $route_params['apikey'], 'name' => $route_params['name']);

        $requiredParams = array();

        if (!$this->validateApiRules($apiCredentials, $requiredParams, $request)) {
            return 'Error';
        }

        if (!$asset = $this->validateAsset($route_params['asset'])) {
            return 'Error';
        }
        
        $userId = $request->getParameter('user', false);
        $cardId = $request->getParameter('card', false);
        $prizeId = $request->getParameter('prize', false);
        $serial = $request->getParameter('serial', false);
        $password = $request->getParameter('password', false);

        if ($cardId && $prizeId && $userId) {
            if (!$user = Doctrine::getTable('sfGuardUser')->findOneByAlphaId($userId)) {
                $this->result['error'] = $this->getErrorFromList('api200');
                return 'Error';
            }
            
            if (!$this->validateUserActiveAndDataComplete($user)) {
                return 'Error';
            }

            if (!$result = $this->validateAcquiringCoupon($user, $cardId, $prizeId)) {
                return 'Error';
            }

            if ($result['card']->hasStatus('exchanged')) {
                $coupon = $result['card']->getCoupon();
            } else {
                $result = $this->registerCoupon($user, $result['card'], $result['promo'], $result['prize']);
                $coupon = $result['coupon'];
            }
        } else if ($serial && $password) {
            if (!$coupon = $this->validateSerialAndPassword($serial, $password)) {
                return 'Error';
            }
        } else {
            $this->result['error'] = $this->getErrorFromList('api001');
            return 'Error';
        }

        if (!$this->validateCoupon($coupon, $asset)) {
            return 'Error';
        }

        $coupon = $this->redeemCoupon($coupon, $asset);

        $this->manageSubscription($coupon->getUser(), $coupon->getPromo()->getAffiliateId(), $asset->getId());

        $pts = $this->awardPoints('redeem', $coupon->getUser());

        $this->result['user'] = $coupon->getUser()->asArray();
        $this->result['message'] = '¡Tu premio ha sido canjeado exitosamente! | Has obtenido ' . $pts . ' pts LT por canjear tu premio';
        $this->result['prize'] = $coupon->getPrize()->getPrize();
        
        if ($surveys = $this->getSurveysAsArray($coupon->getPromo(), $asset)) {
            $this->result['surveys'] = $surveys;
        }

        $this->result['success'] = 1;
        
        $this->sendRedeemNotification($coupon->getUser()->getUserProfile(), $coupon->getPromo(), $asset, false);
        # Respaldo para Licoteca
        $admin_user = Doctrine_Core::getTable('sfGuardUser')->findOneByIsAdmin(true);
        $this->sendRedeemNotification($admin_user->getUserProfile(), $coupon->getPromo(), $asset, false);
    }

    public function executeGiveTag(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;
        
        $this->isNewUser = false;

        $route_params = $this->getRoute()->getParameters();
        $apiCredentials = array('apikey' => $route_params['apikey'], 'name' => $route_params['name']);
        $requiredParams = array('user');

        if (!$this->validateApiRules($apiCredentials, $requiredParams, $request)) {
            return 'Error';
        }
        
        if (!$promocode = $this->validatePromoCode($route_params['promocode'])) {
            return 'Error';
        }

        $userIdentifier = $request->getParameter('user');
        $numTags = $request->getParameter('tags',1);
        $tagSource = 'api';

        if (preg_match(sfValidatorEmail::REGEX_EMAIL, $userIdentifier)) {
            if (!$user = $this->handleUserByEmail($userIdentifier, $promocode->getAsset())) {
                return 'Error';
            }
            $tagSource = 'api_email';
        } 
        else {
            if (!$user = $this->validateUserByBuhoId($userIdentifier)) {
                return 'Error';
            }
            $tagSource = 'api_buhoid';
        }

        if (!$result = $this->validateManyTags($user, $promocode, $numTags)) {
            return 'Error';
        }
        
        $assignedTags = 0;
        for ($assignedTags = 0; $assignedTags < $result['assign']; $assignedTags++) {
            $this->registerTag($user, $result['promo'], $promocode, null, $tagSource);
            $this->awardPoints('tag', $user);
        }
        
        $this->manageSubscription($user, $result['promo']->getAffiliateId(), $result['promocode']->getAssetId());
        
        $this->result['message'] = 'Se '.($numTags > 1 ? 'han asignado '.$assignedTags.' Tags' : 'ha asignado un Tag').' al usuario '.$userIdentifier.' para la promoción '.$promocode->getPromo().' ('.$promocode->getAlphaId().')';
        
        $this->result['requested_tags'] = (int) $numTags;
        $this->result['assigned_tags'] = $assignedTags;
        
        $this->result['success'] = 1;
        
        $this->sendManyTagsNotification($user->getUserProfile(), $promocode->getPromo(), $promocode->getAsset(), $assignedTags, true);
    }

    protected function sendVerificationMailUnregistered($userData) {
        $routing = $this->getContext()->getConfiguration()->getRouting('frontend', array(
            'prefix' => '', // '' in case you want no script name displayed  
            'host' => sfConfig::get('app_domain','lealtag.com'),
                ));
        
        $route = $routing->generate('validate', array('validate' => $userData['validator']), true);
        
        $this->mail(array(
            'subject'       => 'Verifica tu cuenta para ser premiado',
            'teaser'        => 'Felicidades, solo falta un paso más para activar tu cuenta en LealTag',
            'to'            => $userData['email'],
            'html'          => 'sendValidatePreregistered',
            'text'          => 'sendValidatePreregisteredText',
            'substitutions' => array('%ROUTE%' => array($route."?u=".$userData['hash'])),
            'category'      => array('transactional', 'verification', 'api', 'buho-unverified'),
        ));
    }

    protected function sendTagNotification(UserProfile $profile, Promo $promo, Asset $asset, $withSurveys = false) {
        $routing = $this->getContext()->getConfiguration()->getRouting('frontend', array(
            'prefix' => '', // '' in case you want no script name displayed  
            'host' => sfConfig::get('app_domain','lealtag.com'),
                ));
        
        $pr = $this->createParticipationRequest($profile->getUser(), $promo, $asset, 'tag', true, $withSurveys);
        
        $route = $routing->generate('survey_feedback', array(), true).'?h='.$pr->getHash();
        
        $this->mail(array(
            'subject'       => 'Has recibido una visita en '.$asset->getName(),
            //'teaser'        => '¡Vamos a vivirlo!',
            'to'            => $profile->getEmail(),
            'html'          => 'sendTagNotification',
            'text'          => 'sendTagNotificationText',
            'substitutions' => array(
                    '%FULLNAME%'    => array(($profile->getFullname() ? $profile->getFullname() : $profile->getEmail())),
                    '%ROUTE%'       => array($route),
                    '%ASSET%'       => array($asset->getName())
                ),
            'category'      => array('transactional', 'tag', 'api'),
        ));
    }
    
    protected function sendManyTagsNotification(UserProfile $profile, Promo $promo, Asset $asset, $numTags, $withSurveys = false) {
        $routing = $this->getContext()->getConfiguration()->getRouting('frontend', array(
            'prefix' => '', // '' in case you want no script name displayed  
            'host' => sfConfig::get('app_domain','lealtag.com'),
                ));
        
        $pr = $this->createParticipationRequest($profile->getUser(), $promo, $asset, 'tag', true, $withSurveys);
        
        $route = $routing->generate('survey_feedback', array(), true).'?h='.$pr->getHash();
        
        $this->mail(array(
            'subject'       => 'Hemos registrado '.($numTags > 1 ? $numTags.' visitas' : 'una visita').' en '.$asset->getName(),
            'teaser'        => 'Gracias por ser un cliente fiel en ' . $asset->getName(),
            'to'            => $profile->getEmail(),
            'html'          => 'sendManyTagsNotification',
            'text'          => 'sendManyTagsNotificationText',
            'substitutions' => array(
                    '%FULLNAME%'    => array(($profile->getFullname() ? $profile->getFullname() : $profile->getEmail())),
                    '%ROUTE%'       => array($route),
                    '%ASSET%'       => array($asset->getName()),
                    '%TAGS%'       => array(($numTags > 1 ? $numTags.' Tags' : 'un Tag')),
                ),
            'category'      => array('transactional', 'redeem', 'api'),
        ));
    }
    
    protected function sendRedeemNotification(UserProfile $profile, Promo $promo, Asset $asset, $withSurveys = false) {
        $routing = $this->getContext()->getConfiguration()->getRouting('frontend', array(
            'prefix' => '', // '' in case you want no script name displayed  
            'host' => sfConfig::get('app_domain','lealtag.com'),
                ));
        
        $pr = $this->createParticipationRequest($profile->getUser(), $promo, $asset, 'redeem', true, $withSurveys);
        
        $route = $routing->generate('survey_feedback', array(), true).'?h='.$pr->getHash();
        
        $this->mail(array(
            'subject'       => 'Cuentanos como fue tu experiencia en '.$asset->getName(),
            'teaser'        => 'Esperamos que hayas disfrutado tu premio y que sigas siendo premiado a través de LealTag',
            'to'            => $profile->getEmail(),
            'html'          => 'sendRedeemNotification',
            'text'          => 'sendRedeemNotificationText',
            'substitutions' => array(
                    '%FULLNAME%'    => array(($profile->getFullname() ? $profile->getFullname() : $profile->getEmail())),
                    '%ROUTE%'       => array($route),
                    '%ASSET%'       => array($asset->getName()),
                ),
            'category'      => array('transactional', 'redeem', 'api'),
        ));
    }
    
    protected function sendSurveyParticipationRequest(UserProfile $profile, Promo $promo, Asset $asset) {
        $routing = $this->getContext()->getConfiguration()->getRouting('frontend', array(
            'prefix' => '', // '' in case you want no script name displayed  
            'host' => sfConfig::get('app_domain','lealtag.com'),
                ));
        
        $pr = $this->createParticipationRequest($profile->getUser(), $promo, $asset, 'tag', false, true);
        
        $route = $routing->generate('survey', array(), true).'?h='.$pr->getHash();
        
        $this->mail(array(
            'subject'       => 'Participa en esta encuesta y gana puntos LealTag',
            'teaser'        => 'Mereces ser premiado',
            'to'            => $profile->getEmail(),
            'html'          => 'sendParticipationRequest',
            'text'          => 'sendParticipationRequestText',
            'substitutions' => array(
                    '%FULLNAME%'    => array(($profile->getFullname() ? $profile->getFullname() : $profile->getEmail())),
                    '%ROUTE%'       => array($route),
                    '%ASSET%'       => array($asset->getName())
                ),
            'category'      => array('transactional', 'participation-request', 'api'),
        ));
    }
    
    protected function setEmailSubject($subject) {
        return sfContext::getInstance()->getI18N()->__($subject);
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
    
    static protected function createGuid() {
        $guid = "";

        for ($i = 0; ($i < 8); $i++) {
            $guid .= sprintf("%02x", mt_rand(0, 255));
        }

        return $guid;
    }
}
