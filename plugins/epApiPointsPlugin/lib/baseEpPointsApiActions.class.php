<?php

/**
 * Description of baseEpApiActions
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class baseEpPointsApiActions extends sfActions {

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

        if (array_key_exists('password', $userData)) { /* Only set the password value when it is a new objetc */
            $user->setPassword($userData['password']);
        }

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
                'message' => 'Se ha asignado la tarjeta ' . $membershipCard->getAlphaId() . ' a tu cuenta.'
            );
        } else {
            $this->result['mcard'] = array(
                'id' => $membershipCard->getAlphaId(),
                'status' => $membershipCard->getStatus(),
                'assigned' => 0,
                'message' => 'La tarjeta ' . $mcard->getAlphaId() .
                ' esta asociada a tu cuenta, para reemplezarla contactanos a soporte@lealtag.com,' .
                'La tarjeta ' . $membershipCard->getAlphaId() . ' no se ha asociado a tu cuenta.'
            );
        }

        return $membershipCard;
    }

    protected function handleUserByEmail($email, Asset $asset) {
        $this->isNewUser = false;

        if (!preg_match(sfValidatorEmail::REGEX_EMAIL, $email)) {
            $this->result['error'] = $this->getErrorFromList('api1000');
            $search = array('%message%');
            $replace = array('El correo electrónico "' . $email . '" no tiene el formato adecuado');
            $this->result['error']['message'] = str_replace($search, $replace, $this->result['error']['message']);
            return false;
        }

        if (!$user = Doctrine::getTable('sfGuardUser')->findOneBy('email_address', $email)) {
            $buho = new epBuhoApi();

            $result = $buho->buhoGetUser(array('user' => $email));

            if (!$result['success']) {
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
                    $this->sendUserEmailOnlyVerificationMail($profile, $asset);
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
        if (!$asset = Doctrine::getTable('Asset')->findOneByAlphaIdAndAssetType($asset, $type)) {
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

    protected function validateTag(sfGuardUser $user, PromoCode $promocode, $vcode = '') {
        $promo = $promocode->getPromo();

        if (!$promo->isActive()) {
            $this->result['error'] = $this->getErrorFromList('api301');
            $search = array('%promo%');
            $replace = array($promo);
            $this->result['error']['message'] = str_replace($search, $replace, $this->result['error']['message']);
            return false;
        }

        if ($promo->getMaxUses() > 0 && $user->getCompleteParticipationsNumber($promo->getId()) == $promo->getMaxUses()) {
            $this->result['error'] = $this->getErrorFromList('api302');
            $search = array('%max%', '%promo%');
            $replace = array($promo->getMaxUses(), $promo);
            $this->result['error']['message'] = str_replace($search, $replace, $this->result['error']['message']);
            return false;
        }

        if ($promo->getMaxDailyTags() > 0 && $user->countTodayTickets($promo->getId()) == $promo->getMaxDailyTags()) {
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

        if ($prize->getThreshold() > $card->getTickets()->count()) {
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

        if ($membershipCard->hasStatus('active')) {
            $user = $membershipCard->getUser();
        } else if ($membershipCard->hasStatus('unassigned')) {
            if (!$email) {
                $this->result['error'] = $this->getErrorFromList('api802');
                return false;
            } else {
                if (!$user = $this->handleUserByEmail($email, $asset)) {
                    return false;
                }
            }

            $membershipCard = $this->manageUserMembershipCard($user, $membershipCard, $asset);
        }

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
                    $this->sendUserEmailOnlyVerificationRemainderMail($user->getUserProfile());
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
        $promo = $pcode->getPromo();

        $ticket = new Ticket();

        $ticket->setUser($user);
        $ticket->setPromo($promo);
        $ticket->setPromoCode($pcode);
        $ticket->setAsset($pcode->getAsset());
        $ticket->setCache($cache);
        $ticket->setVia($source);

        if (!is_null($vcode)) {
            $ticket->setValidationCode($vcode);
            $vcode->setUsed(true);
            $vcode->setUser($user);
            $vcode->setUsedAt(date(DateTime::W3C));
            $vcode->save();
        }

        if (!$card = $user->hasActiveCard($promo->getId())) {
            $card = new Card();
            $card->setUser($user);
            $card->setPromo($promo);
            $card->setStatus('active');
        }

        $ticket->setCard($card);

        if ($card->hasReachedTheLimit()) {
            $card->setStatus('complete');

            //// FUNCION DE TD
            if ($card->getPromo()->getAffiliate()->getSlug() == "tudescuenton") {
                $card->setStatus('redeemed');
                $prize = Doctrine::getTable('PromoPrize')->findOneByPromoId($promo->getId());

                $coupon = new Coupon();
                $coupon->setUser($user);
                $coupon->setPromo($promo);
                $coupon->setPrize($prize);
                $coupon->setCard($card);
                $coupon->setStatus('used');
                $coupon->save();
                //TE PREMIO
                $values = array();
                $values['hash'] = $user->getHash();
                $values['email'] = $user->getEmailAddress();
                $values['promo'] = $promo->getId();
                $values['premio'] = $prize->getId();

                $tdApi = new epTDApi();
                $result = $tdApi->tdSendPrize($values);

                $values['resultado'] = $result;

                $this->registerTdApiCall($values);
            }
        }

        $card->save();

        $ticket->save();

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
            if (strcasecmp($result['error']['type'], 'RedeemError') == 0 && $result['error']['code'] == 6) { /* Si el premio ya fue canjeado (código de error 6) no retornar error, la razón para que esto pueda suceder es el timeout en una llamada previa. */
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
        
        if (strcasecmp($error['type'], 'RedeemError') == 0) {
            switch ($error['code']) {
                case 0:
                    break;
                case 1:
                    break;
                case 2:
                    $apiError = $this->getErrorFromList('api1200');
                    $search = array('%email%');
                    $replace = array($user->getEmail());
                    $apiError['message'] = str_replace($search, $replace, $apiError['message']);
                    break;
                case 3:
                    $apiError = $this->getErrorFromList('api1201');
                    $search = array('%email%');
                    $replace = array($user->getEmail(), $promo->getAffiliate()->getName());
                    $apiError['message'] = str_replace($search, $replace, $apiError['message']);
                    break;
                case 4:
                    $apiError = $this->getErrorFromList('api1202');
                    $search = array('%affiliate%');
                    $replace = array($prize->getPrize().' - '.$prize->getAlphaId(),$promo->getAffiliate()->getName());
                    $apiError['message'] = str_replace($search, $replace, $apiError['message']);
                    break;
                case 5:
                    $apiError = $this->getErrorFromList('api1203');
                    $search = array('%affiliate%');
                    $replace = array($prize->getPrize().' - '.$prize->getAlphaId(),$promo->getAffiliate()->getName());
                    $apiError['message'] = str_replace($search, $replace, $apiError['message']);
                    break;
                case 6:
                    /*No mostrar error si la respuesta es que el premio ya ha sido canjeado*/
                    break;
                default:
                    $apiError = $this->getErrorFromList('api1100');
                    $search = array('%affiliate%');
                    $replace = array($promo->getAffiliate()->getName());
                    $apiError['message'] = str_replace($search, $replace, $apiError['message']);
                    break;
            }
        }
        
        return $apiError;
    }

    protected function mail($options) {
        $required = array('subject', 'parameters', 'email', 'html', 'text');

        foreach ($required as $option) {
            if (!isset($options[$option])) {
                throw new sfException("Required option $option not supplied to sfApply::mail");
            }
        }
        $message = $this->getMailer()->compose();
        $message->setSubject($options['subject']);

        // Render message parts
        $message->setBody($this->getPartial($options['html'], $options['parameters']), 'text/html');
        $message->addPart($this->getPartial($options['text'], $options['parameters']), 'text/plain');
        $address = $this->getFromAddress();
        $message->setFrom(array($address['email'] => $address['fullname']));
        $message->setTo(array($options['email'] => array_key_exists('fullname', $options) ? $options['fullname'] : $options['email']));
        $this->getMailer()->send($message);
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
