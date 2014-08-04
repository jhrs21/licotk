<?php

/**
 * user actions.
 *
 * @package    elperro
 * @subpackage user
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class userActions extends sfActions {
    public function executePreGenerateCoupon(sfWebRequest $request) {
        $user = $this->getUser()->getGuardUser();
        $this->card = $this->getRoute()->getObject();

        $this->form = new epGenerateCouponForm(array(), array('user' => $user, 'card' => $this->card));

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {
                $values = $this->form->getValues();

                $result = $this->registerCoupon($user, $values['card'], $values['promo'], $values['prize']);
                
                if ($values['promo']->getRedeemAutomated()) {                    
                    $coupon = $this->redeemCoupon($result['coupon']);
                    $pts = $this->awardPoints('redeem', $user);
                    
                    $this->getUser()->setFlash('prize_redeem_succeeded', '¡Tu premio ha sido canjeado exitosamente en "'.$coupon->getPromo()->getAffiliate().'"!');
                    $this->getUser()->setFlash('lt_pts_awarded', '¡Has sido premiado con ' . $pts . ' puntosLT por tu Tag!');
                    
                    $this->redirect($this->getController()->genUrl(array('sf_route' => 'user_prizes')));
                }

                $this->redirect($this->generateUrl('generate_coupon', array(
                            'user' => $user->getId(),
                            'alpha_id' => $result['card']->getAlphaId(),
                            'prize' => $values['prize']->getAlphaId()
                        )));
            }
        }
    }

    public function executeGenerateCoupon(sfWebRequest $request) {
        $card = $this->getRoute()->getObject();
        $route_params = $this->getRoute()->getParameters();
        $user = $this->getUser()->getGuardUser();
        $profile = Doctrine::getTable('UserProfile')->findOneBy('user_id', $user->getId());
        $prize = Doctrine::getTable('PromoPrize')->findOneBy('alpha_id', $route_params['prize']);
        $coupon = Doctrine::getTable('Coupon')->findOneBy('card_id', $card->getId());
        $terminos = Doctrine::getTable('PromoTerm')->findBy('promo_id', $coupon->getPromoId());
        $terminos_string = "";
        $i = 1;
        
        foreach ($terminos as $termino) {
            $terminos_string .= $i . ". " . $termino->getTerm() . "\r\n\r\n";
            $i++;
        }
        
        $affiliate = $card->getPromo()->getAffiliate();
	if (ob_get_level()){
        	ob_end_clean();
	}

        require(sfConfig::get('sf_lib_dir') . '/vendor/fpdf17/fpdf.php');

        $pdf = new FPDF("P", 'mm', 'letter');
        $pdf->SetFont('Helvetica', '', 11);

        $pdf->AddPage();
        $pdf->SetXY(5, 5);

        //Codigo QR
        $pdf->Image(sfConfig::get('sf_web_dir') . '/images/cupon-descargable2.png', 1, 1, -300);

        //Tu premio es
        $pdf->SetXY(112, 40);
        $pdf->MultiCell(75, 3, utf8_decode($prize->getPrize()), 0);

        //Serial
        $pdf->SetXY(54.5, 56);
        $pdf->MultiCell(34, 5, $coupon->getSerial(), 0);

        //Password
        $pdf->SetXY(54.5, 62);
        $pdf->MultiCell(34, 5, $coupon->getPassword(), 0);

        //Valido hasta
        $pdf->SetXY(151.5, 61);
        $pdf->MultiCell(34.5, 4.5, $coupon->getPromo()->getDateTimeObject('expires_at')->format('d/m/Y'), 0);

        //Nombre afiliado
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(33, 80);
        $pdf->MultiCell(65, 3, utf8_decode($affiliate->getName()), 0);
        $pdf->SetFont('Arial', '', 8);

        //Nombre
        $pdf->SetXY(130, 71);
        $pdf->MultiCell(43, 5, utf8_decode($user->getFirstName()), 0);

        //Apellido
        $pdf->SetXY(130, 78);
        $pdf->MultiCell(43, 5, utf8_decode($user->getLastName()), 0);

        //Cedula
        $pdf->SetXY(130, 85);
        $pdf->MultiCell(43, 5, utf8_decode($profile->getIdNumber()), 0);

        //Direccion
        $assets = Doctrine::getTable('Asset')->findBy('affiliate_id', $affiliate->getId());
        $addresses = "";
        foreach ($assets as $asset) {
            $location = $asset->getLocation();
            $addresses .= "- ".$location[0]->getAddress()."\n\n ";
        }
        $pdf->SetXY(33, 104);
        $pdf->MultiCell(74, 3, $addresses, 0);
//        $pdf->SetXY($pdf->GetX() + 23, $pdf->GetY());
//        $link = "www.lealtag.com";
//        $pdf->SetFont('Arial', 'U');
        //$pdf->Write(4, "www.lealtag.com", $link);

        //Codigo QR
        $string = $coupon->getSerial() . "-" . $coupon->getPassword();
        $membershipCard = $user->getMembershipCard(true);
        require(sfConfig::get('sf_lib_dir') . '/vendor/phpqrcode/qrlib.php');
        $this->suffix = '/qr-pc/' . $string . '-coupon.png';
        $filename = sfConfig::get('sf_web_dir') . $this->suffix;
        $data = "coupon/" . $string;
        $errorCorrectionLevel = "L";
        $matrixPointSize = "8";
        //ob_end_clean();
        QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2, true);
        $pdf->Image($filename, 42, 153, 45);

        //Condiciones de uso
        $pdf->SetXY(113, 103);
        $pdf->SetFont('Arial', '', 5.5);
        $pdf->MultiCell(74, 3, utf8_decode($terminos_string), 0);
        $pdf->Output();
        return sfView::NONE;
    }

    public function executePrize(sfWebRequest $request) {
        if ($this->getRoute() instanceof sfDoctrineRoute) {
            $this->card = $this->getRoute()->getObject();
        }
        else {
            $this->card = Doctrine::getTable('Card')->findOneBy('alpha_id', $request->getParameter('p', 'empty'));
        }
        
        $this->user = $this->getUser()->getGuardUser();

        if (!$this->card->getUserId() == $this->user->getId()) {
            return 'Error';
        }

        if ($this->card->hasStatus('active')) {
            $this->tagForm = new epRegisterValidationCodeForm($this->user);
            $this->tagForm->getWidgetSchema()->setFormFormatterName('epWeb');
        }

        if ($this->card->hasStatus('complete') || count($this->card->getCanBeExchangedFor())) {
            $formOptions = array('user' => $this->user, 'card' => $this->card);

            $this->prizeForm = new epGenerateCouponForm(array(), $formOptions);
            $this->prizeForm->getWidgetSchema()->setFormFormatterName('epWeb');
        }

        if ($this->getUser()->getAskForFeedback()) {
            $this->setFeedbackForm();
        }
    }

    public function executeGenerateMembershipCard(sfWebRequest $request) {
        $user = $this->getUser()->getGuardUser();
        $profile = Doctrine::getTable('UserProfile')->findOneBy('user_id', $user->getId());
        ob_end_clean();

        //require(sfConfig::get('sf_lib_dir') . '/vendor/fpdf17/fpdf.php');
        require(sfConfig::get('sf_lib_dir') . '/vendor/fpdf17/rotation.php');

        $pdf = new PDF_Rotate("P", 'mm', 'letter');
        $pdf->SetFont('Helvetica', '', 11);

        $pdf->AddPage();
        $pdf->SetXY(5, 5);

        $pdf->Image(sfConfig::get('sf_web_dir') . '/images/tarjeta_virtual.png', 1, 1, -300);

        //Nombre
        $pdf->SetXY(55, 140);
        $pdf->MultiCell(43, 5, utf8_decode($user->getFirstName()), 0);

        //Apellido
        $pdf->SetXY(57, 145);
        $pdf->MultiCell(43, 5, utf8_decode($user->getLastName()), 0);

        //Cedula
        $pdf->SetXY(45, 150);
        $pdf->MultiCell(43, 5, utf8_decode($profile->getIdNumber()), 0);

        $string = $user->getAlphaId();
        $membershipCard = $user->getMembershipCard(true);
        require(sfConfig::get('sf_lib_dir') . '/vendor/phpqrcode/qrlib.php');
        $this->suffix = '/qr-pc/' . $string . '-membershipCard.png';
        $filename = sfConfig::get('sf_web_dir') . $this->suffix;
        $data = "http://www.lealtag.com/mc/" . $membershipCard->getAlphaId();
        $errorCorrectionLevel = "L";
        $matrixPointSize = "8";
        //ob_end_clean();
        QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2, true);

        $pdf->RotatedText(60, 172, $membershipCard->getAlphaId(), 180);

        $pdf->RotatedImage($filename, 72, 212, 37, 37, 180);

        $pdf->Output();
        return sfView::NONE;
    }

    public function executePrizes(sfWebRequest $request) {
        $this->user = $this->getUser()->getGuardUser();

        $cards = $this->user->getCards();

        $this->cards = array();

        foreach ($cards as $card) {
            if (strcasecmp($card->getStatus(), 'exchanged') == 0) {
                $this->cards['complete'][] = $card;
            } elseif (strcasecmp($card->getStatus(), 'complete') == 0 || strcasecmp($card->getStatus(), 'active') == 0) {
                $this->cards['active'][] = $card;
            } elseif (strcasecmp($card->getStatus(), 'redeemed') == 0) {
                $this->cards['redeemed'][] = $card;
            } elseif (strcasecmp($card->getStatus(), 'expired') == 0) {
                $this->cards['expired'][] = $card;
            }
        }

        if (!count($this->cards)) {
            $this->form = new epRegisterValidationCodeForm($this->user);
            $this->form->getWidgetSchema()->setFormFormatterName('epWeb');

            return 'NoPrizes';
        }
    }

    public function executeShowProfile(sfWebRequest $request) {
        $this->user = $this->getRoute()->getObject();
    }

    public function executeEditProfile(sfWebRequest $request) {
        $this->user = $this->getRoute()->getObject();

        $this->form = new UserProfileForm();

        if ($request->getMethod('post')) {
            $this->processProfileForm($request, $this->form);
        }
    }

    public function executeRegisterTag(sfWebRequest $request) {
        $this->user = $this->getUser()->getGuardUser();

        $this->form = new epRegisterValidationCodeForm($this->user);

        $this->form->getWidgetSchema()->setFormFormatterName('epWeb');

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));

            if ($this->form->isValid()) {
                $values = $this->form->getValues();

                $vcode = $values['vcode'];

                $card = $this->doRegisterTag($this->user, $vcode);

                $pts = $this->awardPoints('tag', $this->user);
                
                $pr = $this->createParticipationRequest($this->user, $card->getPromo(), $vcode->getPromoCode()->getAsset(), 'tag', true, true);

                $this->getUser()->setFlash('tag_registration_succeeded', '¡Tu Tag ha sido registrado exitosamente y has recibido' . $pts . ' puntosLT por tu Tag!');
                
                $this->redirect($this->getController()->genUrl(array('sf_route' => 'survey_feedback'), true).'?h='.$pr->getHash());
            }
        }
    }

    public function executeFeedback(sfWebRequest $request) {
        $this->setFeedbackForm();

        if ($request->isMethod('post')) {
            $this->feedbackForm->bind($request->getParameter($this->feedbackForm->getName()));

            if ($this->feedbackForm->isValid()) {
                $this->feedbackForm->save();

                $this->pts = $this->awardPoints('feedback', $this->getUser()->getGuardUser());
            }
        }
    }

    protected function setFeedbackForm($action = 'tag') {
        $promoCode = $this->getUser()->getTaggedPromoCode();

        $feedback = new Feedback();

        $feedback->setAction($action);
        $feedback->setUser($this->getUser()->getGuardUser());
        $feedback->setPromo($promoCode->getPromo());
        $feedback->setAsset($promoCode->getAsset());
        $feedback->setAffiliateId($promoCode->getAsset()->getAffiliateId());

        $this->feedbackForm = new epFeedbackForm($feedback);
    }

    protected function doRegisterTag(sfGuardUser $user, ValidationCode $vcode, $cache = false) {
        $pcode = $vcode->getPromoCode();
        $promo = $pcode->getPromo();

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
        $ticket->setVia('web');

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

        if (!$subscription = $user->isSubscribed($promo->getAffiliateId())) {
            $subscription = new Subscription();
            $subscription->setUser($user);
            $subscription->setAffiliateId($promo->getAffiliateId());
            $subscription->setAssetId($pcode->getAssetId());
        } 
        else {
            if (!$subscription = $user->isSubscribed($pcode->getAssetId(), "asset")) {
                $subscription->setAssetId($pcode->getAssetId());
            }
        }

        $subscription->setStatus('active');
        $subscription->setLastInteraction(date(DateTime::W3C));

        $subscription->save();

        return $card;
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
    
    protected function registerTdApiCall($values) {
        $log = new TdApiLog();
        
        $log->setUserEmail($values['email']);
        $log->setUserHash($values['hash']);
        $log->setPromoId($values['promo']);
        $log->setPrizeId($values['premio']);
        $log->setSuccess($values['resultado']['success']);
        
        if ($values['resultado']['success']) {
            $log->setMessage($values['resultado']['msj']);
        }
        else {
            $log->setMessage($values['resultado']['error']['msj']);
            $log->setErrorCode($values['resultado']['error']['cod']);
        }
        
        $log->save();
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
}
