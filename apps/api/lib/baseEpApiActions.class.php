<?php

/**
 * Description of baseEpApiActions
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */

require_once sfConfig::get('sf_lib_dir').'/vendor/sendgrid-php/SendGrid_loader.php';

class baseEpApiActions extends sfActions {
    protected function getSurveysAsArray(Promo $promo, Asset $asset) {
        $surveys = $this->getSurveys($promo, $asset);
        
        $result = array();
        
        foreach ($surveys as $survey) {
            $result[$survey->getAlphaId()] = $survey->asArray(true, true);
        }
        
        return count($result) > 0 ? $result : false;
    }

    protected function getSurveys(Promo $promo, Asset $asset) {
        return $promo->getActiveSurveys();
    }

    protected function updateUser($userData, sfGuardUser $user) {
        return $this->updateUserWithBuhoData($userData, $user);
    }

    protected function createUser($userData) {
        $user = new sfGuardUser();
        $user->addGroupByName('licoteca_users');

        $user = $this->updateUserWithBuhoData($userData, $user);

        try {
            if (!$userData['verified']) {
                $this->sendVerificationMail($user->getUserProfile());
            } else {
                $this->sendWelcomeMail($user->getUserProfile());
            }
            return $user;
        } catch (Exception $e) {
            $user->delete();
            if (!$userData['verified']) {
                $this->result['error'] = $this->getErrorFromList('api100');
            } else {
                $this->result['error'] = $this->getErrorFromList('api101');
            }
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

    protected function manageUserMembershipCard(sfGuardUser $user, MembershipCard $membershipCard, Asset $asset) {
        $mcard = $user->getMembershipCard();

        if (!$mcard) {
            $membershipCard->setStatus('active');
            $membershipCard->setUser($user);
            $membershipCard->setAsset($asset);
            $membershipCard->setValidate(self::createGuid());
            $membershipCard->save();

            $this->result['mcard'] = array(
                'id' => $membershipCard->getAlphaId(),
                'status' => $membershipCard->getStatus(),
                'assigned' => 1,
            );
            
            if ($user->getPreRegistered()) {
                $this->result['mcard']['message'] = 'Felicidades '.$user->getEmailAddress().', se ha asignado la tarjeta ' . $membershipCard->getAlphaId() . ' a tu cuenta y tu Tag ha sido registrado.';
            }
            else {
                $this->result['mcard']['message'] = 'Felicidades '.$user->getFullname().', se ha asignado la tarjeta ' . $membershipCard->getAlphaId() . ' a tu cuenta y tu Tag ha sido registrado.';
            }
        } 
        else {
            $this->result['mcard'] = array(
                'id' => $membershipCard->getAlphaId(),
                'status' => $membershipCard->getStatus(),
                'assigned' => 0,
                'message' => 'Te hemos asignado un tag satisfactoriamente, sin embargo, ya tienes la tarjeta '. $mcard->getAlphaId() .' asociada a tu cuenta. Por favor, devuelve la tarjeta: ' . $membershipCard->getAlphaId() . ', para que otra persona pueda ser premiada. ¡Gracias!'
            );
        }

        return $membershipCard;
    }

    protected function handleUserByEmail($email, Asset $asset) {
        $this->isNewUser = false;
	//error_log("entrando a handleuserbyemail\n",3,"/var/tmp/error-email-licoteca.log");
        if (!preg_match(sfValidatorEmail::REGEX_EMAIL, $email)) {
            $this->result['error'] = $this->getErrorFromList('api1000');
            $search = array('%message%');
            $replace = array('El correo electrónico "' . $email . '" no tiene el formato adecuado');
            $this->result['error']['message'] = str_replace($search, $replace, $this->result['error']['message']);
            return false;
        }
	//error_log("el email ($email) tiene el formato correcto\n",3,"/var/tmp/error-email-licoteca.log");
        if (!$user = Doctrine::getTable('sfGuardUser')->findOneBy('email_address', $email)) {
            $buho = new epBuhoApi();

	    // $result = $buho->buhoGetUser(array('user' => $email));
	    $result = array("success" => 0);

            if (!$result['success']) {
		//error_log("que mas, elbuho dio".$result['success']."\n",3,"/var/tmp/error-email-licoteca.log");
                $user = new sfGuardUser();
                $user->setEmailAddress($email);
                $user->setPreRegistered(true);
                $user->setIsActive(false);
                $user->addGroupByName('licoteca_users');
                
                $profile = new UserProfile();
                $profile->setEmail($email);
                $profile->setValidate('pr' . self::createGuid());
                
                $user->setUserProfile($profile);
                
                $user->save();
                
                try {
                    $this->sendPreRegisteredVerificationMail($profile, $asset);
                    return $user;
                } catch (Exception $e) {
                    $user->delete();
                    $this->result['error'] = $this->getErrorFromList('api100');
                    return false;
                }
            } else {
                if (!$user = $this->createUser($result['user'])) {
                    return false;
                }
            }

            $this->isNewUser = true;
        }
	//error_log("saliendo de handleuserbyemail\n",3,"/var/tmp/error-email-licoteca.log");
        return $user;
    }

    protected function validateUserByAlphaId($userId) {
        if (!$user = Doctrine::getTable('sfGuardUser')->findOneByAlphaId($userId)) {
            $this->result['error'] = $this->getErrorFromList('api200');
            return false;
        }
        
        return $user;
    }
    
    protected function validateUserByBuhoId($buhoId) {
        if (!$user = Doctrine::getTable('sfGuardUser')->findOneBy('hash', $buhoId)) {
            $buho = new epBuhoApi();

            $result = $buho->buhoGetUser(array('user' => $email));

            if (!$result['success']) {
                $this->result['error'] = $this->getErrorFromList('api903');
                return false;
            } else {
                $user = $this->createUser($result['user']);
            }
        }
        return $user;
    }

    protected function validateAsset($asset, $type = 'place') {
        //if (!$asset = Doctrine::getTable('Asset')->findOneByAlphaIdAndAssetType($asset, $type)) {
        if (!$asset = Doctrine::getTable('Asset')->findOneByAlphaId($asset)) {
            if (strcasecmp($type, 'place')) {
                $this->result['error'] = $this->getErrorFromList('api700');
            } else {
                $this->result['error'] = $this->getErrorFromList('api701');
            }
            return false;
        }
        return $asset;
    }

    protected function validateCategory($category, $type = null) {
        if (is_null($type)) {
            if (!$category = Doctrine::getTable('Category')->findOneByAlphaId($category)) {
                $this->result['error'] = $this->getErrorFromList('api600');
                return false;
            }
        } else if (!$category = Doctrine::getTable('Category')->findOneByAlphaIdAndCategoryType($category, $type)) {
            if (strcasecmp($type, 'place')) {
                $this->result['error'] = $this->getErrorFromList('api601');
            } else {
                $this->result['error'] = $this->getErrorFromList('api602');
            }
            return false;
        }
        return $category;
    }

    protected function validatePromoCode($promocode, Asset $asset = null) {
        if (!$pcode = Doctrine::getTable('PromoCode')->retrievePromoCode($promocode)) {
            $this->result['error'] = $this->getErrorFromList('api300');
            return false;
        }

        if (!$pcode->isActive()) {
            $this->result['error'] = $this->getErrorFromList('api307');
            return false;
        }

        if ($asset) {
            if ($promocode->getAssetId() != $asset->getId()) {
                $this->result['error'] = $this->getErrorFromList('api306');
                return false;
            }
        }

        return $pcode;
    }

    protected function validateTag(sfGuardUser $user, PromoCode $promocode, $vcode = '', $numTags = 1) {
        $promo = $promocode->getPromo();

        if (!$promo->isActive()) {
            $this->result['error'] = $this->getErrorFromList('api301');
            $search = array('%promo%');
            $replace = array($promo);
            $this->result['error']['message'] = str_replace($search, $replace, $this->result['error']['message']);
            return false;
        }

        $mu = $promo->getMaxUses();
        
        $cpn = $user->getCompleteParticipationsNumber($promo->getId());

        if ($mu > 0 && $cpn == $mu) {
            $this->result['error'] = $this->getErrorFromList('api302');
            $search = array('%max%', '%promo%');
            $replace = array($mu, $promo);
            $this->result['error']['message'] = str_replace($search, $replace, $this->result['error']['message']);
            return false;
        }
        
        $mdt = $promo->getMaxDailyTags();
        
        $tt = $user->countTodayTickets($promo->getId());

        if ($mdt > 0 && $tt >= $mdt) {
            $this->result['error'] = $this->getErrorFromList('api303');
            $search = array('%max%', '%promo%');
            $replace = array($promo->getMaxDailyTags(), $promo);
            $this->result['error']['message'] = str_replace($search, $replace, $this->result['error']['message']);
            return false;
        }

        $validationcode = null;

        if ($promocode->hasType('validation_required')) {
            $validationcode = Doctrine::getTable('ValidationCode')->retrieveValidationCode($vcode, $promocode->getId());
            if (!$validationcode || !$validationcode->getActive()) {
                $this->result['error'] = $this->getErrorFromList('api304');
                return false;
            }
        }

        return array('promo' => $promo, 'promocode' => $promocode, 'vcode' => $validationcode);
    }
    
    protected function validateManyTags(sfGuardUser $user, PromoCode $promocode, $numTags) {
        $promo = $promocode->getPromo();
        $assign = $numTags;

        if (!$promo->isActive()) {
            $this->result['error'] = $this->getErrorFromList('api301');
            $search = array('%promo%');
            $replace = array($promo);
            $this->result['error']['message'] = str_replace($search, $replace, $this->result['error']['message']);
            return false;
        }
        
        $mu = $promo->getMaxUses();
        
        $cpn = $user->getCompleteParticipationsNumber($promo->getId());

        if ($mu > 0 && $cpn == $mu) {
            $this->result['error'] = $this->getErrorFromList('api302');
            $search = array('%max%', '%promo%');
            $replace = array($promo->getMaxUses(), $promo);
            $this->result['error']['message'] = str_replace($search, $replace, $this->result['error']['message']);
            return false;
        }
        
        $mdt = $promo->getMaxDailyTags();
        
        $tt = $user->countTodayTickets($promo->getId());

        if($mdt > 0 ) {
            if ($tt == $mdt) {
                $this->result['error'] = $this->getErrorFromList('api303');
                $search = array('%max%', '%promo%');
                $replace = array($mu, $promo);
                $this->result['error']['message'] = str_replace($search, $replace, $this->result['error']['message']);
                return false;
            }
            else {
                $diff = $mdt - $tt;
                if ($diff < $numTags) {
                    $assign = $diff;
                    $this->result['diff_message'] = 'El número de Tags solicitados supera el limite de Tags diarios, se han asignado la mayor cantidad posible';
                }
            }
        }

        return array('promo' => $promo, 'promocode' => $promocode, 'assign' => $assign);
    }

    protected function validateAcquiringCoupon(sfGuardUser $user, $cardId, $prizeId) {
        if (!$card = Doctrine::getTable('Card')->findOneByAlphaIdAndUserId($cardId, $user->getId())) {
            $this->result['error'] = $this->getErrorFromList('api400');
            return false;
        }

        if ($card->hasStatus('redeemed')) {
            $this->result['error'] = $this->getErrorFromList('api401');
            return false;
        } else if ($card->hasStatus('canceled')) {
            $this->result['error'] = $this->getErrorFromList('api402');
            return false;
        } else if ($card->hasStatus('expired')) {
            $this->result['error'] = $this->getErrorFromList('api403');
            return false;
        }

        $promo = $card->getPromo();

        if (!$prize = Doctrine::getTable('PromoPrize')->findOneByAlphaIdAndPromoId($prizeId, $promo->getId())) {
            $this->result['error'] = $this->getErrorFromList('api500');
            return false;
        }

        if (!$promo->redeemPeriodStarted()) {
            $this->result['error'] = $this->getErrorFromList('api308');
            return false;
        }

        if ($promo->isExpired()) {
            $this->result['error'] = $this->getErrorFromList('api309');
            return false;
        }

        if ($prize->runOut()) {
            $this->result['error'] = $this->getErrorFromList('api502');
            return false;
        }

        if ($prize->getThreshold() > $card->countTickets()) {
            $this->result['error'] = $this->getErrorFromList('api503');
            return false;
        }

        return array('card' => $card, 'promo' => $promo, 'prize' => $prize);
    }

    protected function validateMembershipCard($membershipCardId, $checkUnassigned = false) {
        if (!$membershipCard = Doctrine::getTable('MembershipCard')->findOneBy('alpha_id', $membershipCardId)) {
            $this->result['error'] = $this->getErrorFromList('api800');
            return false;
        }

        if ($membershipCard->hasStatus('inactive')) {
            $this->result['error'] = $this->getErrorFromList('api801');
            return false;
        }

        if ($checkUnassigned) {
            if ($membershipCard->hasStatus('unassigned')) {
                $this->result['error'] = $this->getErrorFromList('api803');
                return false;
            }
        }

        return $membershipCard;
    }

    protected function validateMembershipCardForTag($membershipCardId, Asset $asset, $email = false) {
        if (!$membershipCard = $this->validateMembershipCard($membershipCardId)) {
            return false;
        }
	//error_log("el valor de membershipcard == $membershipCard\n",3,"/var/tmp/error-email-licoteca.log");
        if ($membershipCard->hasStatus('active')) {
            $user = $membershipCard->getUser();
        } 
        else if ($membershipCard->hasStatus('unassigned')) {
            if (!$email) {
                $this->result['error'] = $this->getErrorFromList('api802');
                return false;
            } else {
                if (!$user = $this->handleUserByEmail($email, $asset)) {
                    return false;
                }
            }
	    //error_log("antes de manejar el usermembershipcard\n",3,"/var/tmp/error-email-licoteca.log");
            $membershipCard = $this->manageUserMembershipCard($user, $membershipCard, $asset);
        }
	//error_log("antes de salir\n",3,"/var/tmp/error-email-licoteca.log");
        return $user;
    }

    protected function validateSerialAndPassword($serial, $password, Asset $asset = null) {
        if (!$coupon = Doctrine::getTable('Coupon')->findOneBy('serial', $serial)) {
            $this->result['error'] = $this->getErrorFromList('api504');
            return false;
        }

        // password is ok?
        if ($coupon->checkPassword($password)) {
            return $coupon;
        }

        $this->result['error'] = $this->getErrorFromList('api509');
        return false;
    }

    protected function validateCoupon(Coupon $coupon, Asset $asset = null) {
        if (!is_null($asset)) {
            if (!in_array($asset->getId(), $coupon->getPromo()->getAssets()->getPrimaryKeys())) {
                $this->result['error'] = $this->getErrorFromList('api505');
                return false;
            }
        }

        if ($coupon->hasStatus('used')) {
            $this->result['error'] = $this->getErrorFromList('api506');
            return false;
        }

        if (!$coupon->getPromo()->redeemPeriodStarted()) {
            $this->result['error'] = $this->getErrorFromList('api508');
            return false;
        }

        if ($coupon->isExpired()) {
            $this->result['error'] = $this->getErrorFromList('api507');
            return false;
        }

        return true;
    }

    protected function validateUserActiveAndDataComplete(sfGuardUser $user) {
        if (!$user->getIsActive()) {
            $this->result['error'] = $this->getErrorFromList('api211');
            try {
                if ($user->getPreRegistered()) {
                    $this->sendPreRegisteredVerificationRemainderMail($user->getUserProfile());
                } else {
                    $this->sendVerificationMail($user->getUserProfile());
                }
            } catch (Exception $e) {
                $this->result['error'] = $this->getErrorFromList('api100');
            }
            return false;
        }

        if (!$user->dataComplete()) {
            $this->result['error'] = $this->getErrorFromList('api212');
            try {
                $this->sendUserCompleteDataMail($user->getUserProfile());
            } catch (Exception $e) {
                $this->result['error'] = $this->getErrorFromList('api102');
            }
            return false;
        }

        return true;
    }

    protected function validatePromo($promo) {
        if (!$promo = Doctrine::getTable('Promo')->findOneByAlphaId($promo)) {
            $this->result['error'] = $this->getErrorFromList('api312');
            return false;
        }
        
        if (!$promo->isActive()) {
            $this->result['error'] = $this->getErrorFromList('api307');
            return false;
        }
        
        return $promo;
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

	$mainPocket = $user->getMainPocket();
	$mainPocket->setTotalTags($mainPocket->getTotalTags()+1);
	$mainPocket->save();

        return $card;
    }

    protected function registerCoupon(sfGuardUser $user, Card $card, Promo $promo, PromoPrize $prize) {
        $card->setStatus('exchanged');

        $newcard = false;

        if ($prize->getThreshold() < $card->getTickets()->count()) {
            $newcard = new Card();

            $newcard->setUser($user);
            $newcard->setPromo($promo);
            $newcard->setStatus('active');

            $i = 0;

            foreach ($card->getTickets() as $key => $ticket) {
                if ($prize->getThreshold() > $i) {
                    $ticket->setUsed(true);
                    $ticket->setUsedAt(date(DateTime::W3C));

                    $card->getTickets()->add($ticket, $key);

                    $i++;
                } else {
                    $newcard->getTickets()->add($ticket);
                }
            }
            $newcard->save();
        }

        $card->save();

        $coupon = new Coupon();

        $coupon->setUser($user);
        $coupon->setPromo($promo);
        $coupon->setPrize($prize);
        $coupon->setCard($card);
        $coupon->setStatus('active');

        $coupon->save();

        $prize->setDelivered($prize->getDelivered() + 1);
        $prize->save();

        return array('coupon' => $coupon, 'card' => $card, 'newcard' => $newcard);
    }

    protected function registerTdApiCall($values) {
        $log = new TdApiLog();

        $log->setUserEmail($values['email']);
        $log->setUserHash($values['hash']);
        $log->setPromoId($values['promo']);
        $log->setPrizeId($values['premio']);
        $log->setSuccess($values['resultado']['success']);

        if ($values['resultado']['success']) {
            $log->setMessage($values['resultado']['msj']);
        } else {
            $log->setMessage($values['resultado']['error']['msj']);
            $log->setErrorCode($values['resultado']['error']['cod']);
        }

        $log->save();
    }

    protected function redeemCoupon(Coupon $coupon, Asset $asset = null) {
        $coupon->setStatus('used');
        $coupon->getCard()->setStatus('redeemed');

        if (is_null($asset)) {
            $asset = $this->onlyOneAsset($coupon->getPromo());
        }

        if ($asset) {
            $coupon->setRedeemedAt($asset);
        }

        $coupon->setUsedAt(date(DateTime::W3C));
        $coupon->save();

        return $coupon;
    }

    protected function onlyOneAsset(Promo $promo) {
        if ($promo->getAssets()->count() == 1) {
            return $promo->getAssets()->getFirst();
        }

        return false;
    }

    protected function doAutomaticRedeem(sfGuardUser $user, Promo $promo, PromoPrize $prize, Card $card) {
        $redeemer = new epRedeemer($promo->getRedeemerConfig());
        $result = $redeemer->redeem($user, $prize, $card->getAlphaId());
        
        if (!$result['success']) {
            /** 
             * Si el premio ya fue canjeado (código de error 6) no retornar error, 
             * la razón para que esto pueda suceder es el timeout en una llamada previa. 
             */
            if (strcasecmp($result['error']['type'], 'RedeemError') == 0 && strcasecmp($result['error']['code'], '006') == 0) {
                return true;
            }
            
            $this->result['error'] = $this->setAutomaticRedeemError($result['error'], $user, $promo, $prize);
            
            return false;
        }
        
        return true;
    }
    
    protected function setAutomaticRedeemError(array $error, sfGuardUser $user, Promo $promo, PromoPrize $prize) {
        $apiError = array();
        
        if (strcasecmp($error['type'], 'cUrlError') == 0) {
            switch ($error['code']) {
                case 28:
                    $apiError = $this->getErrorFromList('api1101');
                    $search = array('%affiliate%');
                    $replace = array($promo->getAffiliate()->getName());
                    $apiError['message'] = str_replace($search, $replace, $apiError['message']);
                    break;
                default:
                    $apiError = $this->getErrorFromList('api1100');
                    $search = array('%affiliate%');
                    $replace = array($promo->getAffiliate()->getName());
                    $apiError['message'] = str_replace($search, $replace, $apiError['message']);
            }
        }
        else if (strcasecmp($error['type'], 'RedeemError') == 0) {
            switch ($error['code']) {
                case '000':
                    $apiError = $this->getErrorFromList('api1200');
                    break;
                case '001':
                    $apiError = $this->getErrorFromList('api1201');
                    break;
                case '002':
                    $apiError = $this->getErrorFromList('api1202');
                    $search = array('%email%');
                    $replace = array($user->getEmail());
                    $apiError['message'] = str_replace($search, $replace, $apiError['message']);
                    break;
                case '003':
                    $apiError = $this->getErrorFromList('api1203');
                    $search = array('%email%');
                    $replace = array($user->getEmail(), $promo->getAffiliate()->getName());
                    $apiError['message'] = str_replace($search, $replace, $apiError['message']);
                    break;
                case '004':
                    $apiError = $this->getErrorFromList('api1204');
                    $search = array('%affiliate%');
                    $replace = array($prize->getPrize().' - '.$prize->getAlphaId(),$promo->getAffiliate()->getName());
                    $apiError['message'] = str_replace($search, $replace, $apiError['message']);
                    break;
                case '005':
                    $apiError = $this->getErrorFromList('api1205');
                    $search = array('%affiliate%');
                    $replace = array($prize->getPrize().' - '.$prize->getAlphaId(),$promo->getAffiliate()->getName());
                    $apiError['message'] = str_replace($search, $replace, $apiError['message']);
                    break;
                case '006':
                    //No mostrar error si la respuesta es que el premio ya ha sido canjeado
                    break;
                default:
                    $apiError = $this->getErrorFromList('api1100');
                    $search = array('%affiliate%');
                    $replace = array($promo->getAffiliate()->getName());
                    $apiError['message'] = str_replace($search, $replace, $apiError['message']);
                    break;
            }
        }
        else {
            $apiError = $error;
        }
        
        return $apiError;
    }

    protected function sendWelcomeMail($profile) {
        $this->mail(array(
            'subject'       => 'Bienvenido a LealTag',
            'teaser'        => 'Felicidades, ahora tienes una cuenta en LealTag',
            'to'            => $profile->getEmail(),
            'html'          => 'sendWelcome',
            'text'          => 'sendWelcomeText',
            'substitutions' => array('%FULLNAME%' => array(($profile->getFullname() ? $profile->getFullname() : $profile->getEmail()))),
            'category'      => array('transactional', 'welcome', 'api'),
        ));
    }
    
    protected function sendVerificationMail($profile) {
        $routing = $this->getContext()->getConfiguration()->getRouting('frontend', array(
            'prefix' => '', // '' in case you want no script name displayed  
            'host' => sfConfig::get('app_domain','lealtag.com'),
                ));
        
        $route = $routing->generate('validate', array('validate' => $profile->getValidate()), true);
        
        $this->mail(array(
            'subject'       => 'Bienvenido a LealTag - Verifica tu cuenta',
            'teaser'        => 'Felicidades, solo falta un paso más para activar tu cuenta en LealTag',
            'to'            => $profile->getEmail(),
            'html'          => 'sendValidateNew',
            'text'          => 'sendValidateNewText',
            'substitutions' => array(
                    '%FULLNAME%'    => array(($profile->getFullname() ? $profile->getFullname() : $profile->getEmail())),
                    '%WELCOME%'     => array((strcasecmp($profile->getGender(), 'female') == 0 ? 'Bienvenida' : 'Bienvenido')),
                    '%ROUTE%'       => array($route)
                ),
            'category'      => array('transactional', 'verification', 'api'),
        ));
    }
    
    protected function sendUserCompleteDataMail($profile) {
        $routing = $this->getContext()->getConfiguration()->getRouting('frontend', array(
            'prefix' => '', // '' in case you want no script name displayed  
            'host' => sfConfig::get('app_domain','lealtag.com'),
                ));
        
        $this->mail(array(
            'subject'       => 'Completa los datos de cuenta en LealTag',
            'teaser'        => 'Estas a sólo un paso de poder disfrutar tus premios',
            'to'            => $profile->getEmail(),
            'html'          => 'sendCompleteData',
            'text'          => 'sendCompleteDataText',
            'substitutions' => array(
                    '%FULLNAME%' => array(($profile->getFullname() ? $profile->getFullname() : $profile->getEmail())),
                    '%ROUTE%'    => array($routing->generate('sf_guard_login', array(), true))
                ),
            'category'      => array('transactional', 'complete-data', 'api'),
        ));
    }
    
    protected function sendPreRegisteredVerificationMail(UserProfile $profile, Asset $asset) {
        $routing = $this->getContext()->getConfiguration()->getRouting('frontend', array(
            'prefix' => '', // '' in case you want no script name displayed  
            'host' => sfConfig::get('app_domain','lealtag.com'),
                ));

        $route = $routing->generate('user_complete_register', array('validate' => $profile->getValidate()), true);
        
        $this->mail(array(
            'subject'       => 'Has sido premiado en '.$asset->getName().', verifica tu cuenta y completa tus datos',
            'teaser'        => 'Estas a sólo un paso de poder disfrutar tus premios',
            'to'            => $profile->getEmail(),
            'html'          => 'sendValidatePreregistered',
            'text'          => 'sendValidatePreregisteredText',
            'substitutions' => array(
                    '%FULLNAME%' => array(($profile->getFullname() ? $profile->getFullname() : $profile->getEmail())),
                    '%ROUTE%'    => array($route)
                ),
            'category'      => array('transactional', 'verification', 'api', 'pre-registered'),
        ));
    }
    
    protected function sendPreRegisteredVerificationRemainderMail(UserProfile $profile) {
        $routing = $this->getContext()->getConfiguration()->getRouting('frontend', array(
            'prefix' => '', // '' in case you want no script name displayed  
            'host' => sfConfig::get('app_domain','lealtag.com'),
                ));

        $route = $routing->generate('user_complete_register', array('validate' => $profile->getValidate()), true);

        $this->mail(array(
            'subject'       => 'Completa tu registro en LealTag',
            'teaser'        => 'Estas a sólo un paso de poder disfrutar tus premios',
            'to'            => $profile->getEmail(),
            'html'          => 'sendValidatePreregistered',
            'text'          => 'sendValidatePreregisteredText',
            'substitutions' => array(
                    '%FULLNAME%' => array(($profile->getFullname() ? $profile->getFullname() : $profile->getEmail())),
                    '%ROUTE%'    => array($route),
                ),
            'category'      => array('transactional', 'verification', 'api', 'pre-registered'),
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
        
        $layout = $this->getPartial(sfConfig::get('app_emails_partials_dir').'/layout');
        
        $body = $this->getPartial(sfConfig::get('app_emails_bodies_dir') . '/' . $options['html']);
        
        $teaser = !empty($options['teaser']) ? $options['teaser'] : '';

        $mail->setFrom(sfConfig::get('app_sendgrid_email'))->
                setFromName(sfConfig::get('app_sendgrid_name'))->
                setSubject($options['subject'])->
                setHtml(str_replace(array('%EMAIL_TEASER%','%EMAIL_BODY%'), array($teaser,$body), $layout));
        
        if (!empty($options['text'])) {
            $text = $this->getPartial(
                    sfConfig::get('app_emails_partials_dir').'/layoutText', 
                    array('teaser' => $teaser, 'body' => $this->getPartial(sfConfig::get('app_emails_bodies_dir') . '/' . $options['text']))
                );
            
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
        $from = sfConfig::get('app_mailing_config_from', false);

        if (!$from) {
            throw new Exception('app_mailing_config_from is not set');
        }
        // i18n the full name
        return array('email' => $from['email'], 'fullname' => sfContext::getInstance()->getI18N()->__($from['fullname']));
    }

    protected function validateApiRules(array $credentials, array $params = array(), sfWebRequest $request = null) {
        if (!$this->checkApikey($credentials['apikey'], $credentials['name'])) {
            return false;
        }

        if (!$this->paramsAreOk($params, $request)) {
            return false;
        }

        return true;
    }

    protected function checkApikey($apikey, $name) {
        if (!$apiUser = Doctrine::getTable('ApiUser')->findOneByName($name)) {
            $this->result['error'] = $this->getErrorFromList('api000');
            return false;
        }

        $key = sha1($apiUser->getSalt() . $apikey);

        if (strcmp($apiUser->getApikey(), $key) != 0) {
            $this->result['error'] = $this->getErrorFromList('api000');
            return false;
        }

        return true;
    }

    protected function paramsAreOk(array $parameters, sfWebRequest $request) {
        foreach ($parameters as $param) {
            if (!$request->hasParameter($param)) {
                $this->result['error'] = $this->getErrorFromList('api001');
                $this->result['error']['message'] .= ' (' . $param . ')';

                return false;
            }
        }
        return true;
    }

    protected function getErrorFromList($code) {
        $error = sfConfig::get('app_errorlist_' . $code, false);

        if (!$error) {
            throw new Exception('app_errorlist_' . $code . ' is not set');
        }
        // i18n the message
        return array('code' => $code, 'type' => $error['type'], 'message' => sfContext::getInstance()->getI18N()->__($error['message']));
    }
    
    static protected function createGuid() {
        $guid = "";
        for ($i = 0; ($i < 8); $i++) {
            $guid .= sprintf("%02x", mt_rand(0, 255));
        }
        return $guid;
    }
}
