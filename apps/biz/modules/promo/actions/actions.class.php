<?php

/**
 * promo actions.
 *
 * @package    elperro
 * @subpackage promo
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class promoActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $user = $this->getUser();

        $this->promos = null;

        if ($user->hasGroup('admin')) {
            $this->promos = Doctrine::getTable('Promo')->findAll();
        } else {
            $user = $user->getGuardUser();

            $this->promos = Doctrine::getTable('Promo')->findByAffiliateId($user->getAffiliateId());
        }
    }

    public function executeShow(sfWebRequest $request) {
        $promo = $this->getRoute()->getObject();

        $user = $this->getUser();

        if ($user->hasGroup('admin')) {
            $this->promo = $promo;
        } else {
            $user = $user->getGuardUser();

            if ($user->getAffiliateId() != $promo->getAffiliateId()) {
                $this->getUser()->setFlash('notice', 'Usted no está autorizado para acceder a información de la promoción indicada.');

                $this->redirect($this->generateUrl('promo'));
            }

            $this->promo = $promo;
        }
    }

    public function executeNew(sfWebRequest $request) {
        $user = $this->getUser();

        if ($user->hasGroup('admin')) {
            $this->form = new PromoForm();
        } else {
            $user = $user->getGuardUser();

            $promo = new Promo();

            $promo->setAffiliateId($user->getAffiliateId());

            $this->form = new PromoForm($promo);
        }

        if ($request->isMethod('post')) {
            $this->processForm($request, $this->form);
        }
    }

    public function executeEdit(sfWebRequest $request) {
        $promo = $this->getRoute()->getObject();

        $user = $this->getUser();

        if (!$user->hasGroup('admin')) {
            $user = $user->getGuardUser();

            if ($user->getAffiliateId() != $promo->getAffiliateId()) {
                $this->getUser()->setFlash('notice', 'Usted no está autorizado para modificar la promoción indicada.');

                $this->redirect($this->generateUrl('promo'));
            }
        }

        $this->form = new PromoForm($promo);

        if ($request->isMethod('put')) {
            $this->processForm($request, $this->form);
        }
    }

    public function executeListCoupons(sfWebRequest $request) {
        $this->promo = $this->getRoute()->getObject();
        $user = $this->getUser()->getGuardUser();
        
        if ($user->getAffiliateId() != $this->promo->getAffiliateId()) {
            return 'Error';
        }
        
        if ($request->getParameter('success', false)) {
            $this->getUser()->setFlash('success', 'El cupón ha sido canjeado satisfactoriamente.');
        }

        $this->form = new epDateRangeForm();

        $this->pager = new sfDoctrinePager('Coupon', sfConfig::get('app_ep_coupons_per_page', 20));

        $serial = $request->getParameter('serial', null);
        $id_number = $request->getParameter('id_number', null);
        $status = $request->getParameter('status', null);
        $begin_date = str_replace('/', '-', $request->getParameter('begin_date', null));
        $end_date = str_replace('/', '-', $request->getParameter('end_date', null));

        $this->pager->setQuery($this->promo->getCouponsQuery($serial, $status, $id_number, $begin_date, $end_date));

        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();

        if ($request->isXmlHttpRequest()) {
            return $this->renderPartial('listCoupon', array('pager' => $this->pager));
        }
    }

    public function executeRedeem(sfWebRequest $request) {
        $user = $this->getUser()->getGuardUser();

        $this->couponForm = new epCouponRedeemForm();
        $this->userForm = new epSearchUserPrizesForm(array(), array('user' => $user));
    }

    public function executeSearchUserPrizes(sfWebRequest $request) {
        $user = $this->getUser()->getGuardUser();

        $this->userForm = new epSearchUserPrizesForm(array(), array('user' => $user));

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->userForm->bind($request->getParameter($this->userForm->getName()));

            if ($this->userForm->isValid()) {
                $formValues = $this->userForm->getValues();

                if (!$formValues['user']->getIsActive()) {
                    $this->setFlashMessage('error','El usuario aún no ha verificado su cuenta, debe verificar su cuenta para poder canjear. Le hemos enviado un correo para que pueda realizar la confirmación.');
                    try {
                        if ($formValues['user']->getPreRegistered()) {
                            $this->sendUserEmailOnlyVerificationRemainderMail($formValues['user']->getUserProfile());
                        } else {
                            $this->sendVerificationMail($formValues['user']->getUserProfile());
                        }
                    } catch (Exception $e) {
                        $this->setFlashMessage('error','Ha ocurrido un error al tratar de enviar un email al usuario, intente nuevamente más tarde');
                    }
                    return 'Success'; /* Esto se hace para retornar a la misma vista mostrando el mensaje de error */
                }

                if (!$formValues['user']->dataComplete()) {
                    $this->setFlashMessage('error','El usuario aún no ha completado sus datos, debe completarlos para poder canjear. Le hemos enviado un correo para que pueda completar sus datos.');
                    try {
                        $this->sendUserCompleteDataMail($formValues['user']->getUserProfile());
                    } catch (Exception $e) {
                        $this->setFlashMessage('error','Ha ocurrido un error al tratar de enviar un email al usuario, intente nuevamente más tarde');
                    }
                    return 'Success'; /* Esto se hace para retornar a la misma vista mostrando el mensaje de error */
                }

                $this->redirect($this->getController()->genUrl(array('sf_route' => 'promo_user_prizes', 'user' => $formValues['user']->getAlphaId(), 'promo' => $formValues['promo']->getAlphaId()), false));
            }
        }
    }

    public function executeRedeemCoupon(sfWebRequest $request) {
        $defaults = array();

        if ($this->serial = $request->getParameter('serial', false)) {
            $defaults['serial'] = $this->serial;
        }

        $this->form = new epCouponRedeemForm($defaults);

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {
                $values = $this->form->getValues();

                $coupon = $values['coupon'];

                $coupon->setStatus('used');
                $coupon->getCard()->setStatus('redeemed');
                $coupon->setRedeemedAt($this->getUser()->getGuardUser()->getAsset());
                $coupon->setUsedAt(date(DateTime::W3C));

                $coupon->save();

                $this->redirect($this->getController()->genUrl(array('sf_route' => 'promo_list_coupon', 'id' => $coupon->getPromo()->getId()), false) . '?success=1');
            }
        }

        if ($request->isXmlHttpRequest()) {
            return $this->renderPartial('redeemCouponForm', array('form' => $this->form, 'serial' => $this->serial, 'footer' => false));
        }
    }

    public function executeAddPromoPrizeForm(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());

        $count = $request->getParameter('count', 0);

        $form = new PromoForm();

        $form->setNumPrizes($count + 1);

        $this->prize = $form['prizes'][$count];

        $this->setLayout(false);
    }

    public function executeAddPromoTermForm(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());

        $count = $request->getParameter('count', 0);

        $form = new PromoForm();

        $form->setNumTerms($count + 1);

        $this->term = $form['terms'][$count];

        $this->setLayout(false);
    }

    public function executeUserStuff(sfWebRequest $request) {
        $user = $this->getUser()->getGuardUser();
        $routeParams = $this->getRoute()->getParameters();

        if (!$this->userClient = Doctrine::getTable('sfGuardUser')->findOneBy('alpha_id', $routeParams['user'])) {
            $this->setFlashMessage('error','Identificador de usuario inválido');
            $this->redirect('promo_search_user_prizes');
        }

        if (!$this->promo = Doctrine::getTable('Promo')->findOneBy('alpha_id', $routeParams['promo'])) {
            $this->setFlashMessage('error','Identificador de promoción inválido');
            $this->redirect('promo_search_user_prizes');
        }

        $cards = $this->userClient->getCardsRelatedTo($user->getAssetId(), $this->promo->getId());
        foreach ($cards as $card) {
            $resutl[$card->getStatus()][] = $card;
        }
        $cards = $resutl;

        $this->forms = array();

        $status = array('exchanged', 'complete', 'active');

        foreach ($this->promo->getPrizes(true) as $prize) {
            $canBeRedeemed = false;
            foreach ($status as $s) {
                if (array_key_exists($s, $cards)) {
                    foreach ($cards[$s] as $card) {
                        $canBeExchangedFor = $card->getCanBeExchangedFor();
                        if (in_array($prize->getAlphaId(), $canBeExchangedFor)) {
                            $this->forms[] = new epRedeemPrizeForm(array(), array('promo' => $this->promo, 'prize' => $prize, 'card' => $card, 'user' => $this->userClient));
                            $canBeRedeemed = true;
                            break;
                        }
                    }
                }

                if ($canBeRedeemed) {
                    break;
                }
            }

            if ($canBeRedeemed) {
                continue;
            }

            $this->forms[] = new epRedeemPrizeForm(array(), array('promo' => $this->promo, 'prize' => $prize, 'user' => $this->userClient));
        }
    }

    public function executeRedeemPrize(sfWebRequest $request) {
        $user = $this->getUser()->getGuardUser();
        $routeParams = $this->getRoute()->getParameters();

        if (!$this->userClient = Doctrine::getTable('sfGuardUser')->findOneBy('alpha_id', $routeParams['user'])) {
            $this->setFlashMessage('error','Identificador de usuario inválido');
            $this->redirect('promo_search_user_prizes');
        }

        if (!$this->promo = Doctrine::getTable('Promo')->findOneBy('alpha_id', $routeParams['promo'])) {
            $this->setFlashMessage('error','Identificador de promoción inválido');
            $this->redirect('promo_search_user_prizes');
        }

        $form = new epRedeemPrizeForm(array(), array('promo' => $this->promo, 'user' => $this->userClient));

        $form->bind($request->getParameter($form->getName()));

        if (!$form->isValid()) {
            $this->redirect(
                    $this->getController()->genUrl(
                            array('sf_route' => 'promo_user_prizes', 'user' => $user->getAlphaId(), 'promo' => $promo->getAlphaId())));
        }

        $values = $form->getValues();

        if ($values['card']->hasStatus('exchanged')) {
            $coupon = $values['card']->getCoupon();
        } else {
            $values = $this->registerCoupon($values['user'], $values['card'], $values['promo'], $values['prize']);
            $coupon = $values['coupon'];
        }
        
        $asset = $user->getAsset();
        
        $coupon = $this->redeemCoupon($coupon, $asset);

        $this->manageSubscription($coupon->getUser(), $coupon->getPromo()->getAffiliateId(), $asset->getId());
        $this->awardPoints('redeem', $coupon->getUser());
        
        $this->setFlashMessage('success','El premio: "'.$coupon->getPrize()->getPrize().'" ha sido canjeado exitosamente.');

        $this->redirect($this->getController()->genUrl(array('sf_route' => 'promo_redeem'), false));
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

        if ($form->isValid()) {
            $promo = $form->save();

            $this->redirect($this->getController()->genUrl(array('sf_route' => 'promo_show', 'id' => $promo->getId()), false));
        }
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
    
    protected function sendUserEmailOnlyVerificationRemainderMail(UserProfile $profile) {
        $routing = $this->getContext()->getConfiguration()->getRouting('frontend', array(
            'prefix' => '', // '' in case you want no script name displayed  
            'host' => sfConfig::get('app_domain','club.licoteca.com.ve'),
                ));

        $route = $routing->generate('user_complete_register', array('validate' => $profile->getValidate()), true);

        $this->mail(array(
            'subject' => sfContext::getInstance()->getI18N()->__('Aún no has completado tu cuenta en Licoteca'),
            'fullname' => $profile->getFullname(),
            'email' => $profile->getEmail(),
            'parameters' => array(
                'fullname' => $profile->getFullname(),
                'route1' => $route."?uid=".$profile->getUser()->getHash(),
                'name' => $profile->getUser()->getFirstName(),
                'gender' => $profile->getGender()
            ),
            'text' => 'email/sendValidateNewText',
            'html' => 'email/sendValidateNew'
        ));
    }

    protected function sendVerificationMail($profile) {
        $routing = $this->getContext()->getConfiguration()->getRouting('frontend', array(
            'prefix' => '', // '' in case you want no script name displayed  
            'host' => sfConfig::get('app_domain','club.licoteca.com.ve'),
                ));

        $route = $routing->generate('validate', array('validate' => $profile->getValidate()), true);

        $this->mail(array(
            'subject' => sfContext::getInstance()->getI18N()->__('Bienvenido a Licoteca - Verifica tu cuenta'),
            'fullname' => $profile->getFullname(),
            'email' => $profile->getEmail(),
            'parameters' => array(
                'fullname' => $profile->getFullname(),
                'route1' => $route . "?uid=" . $profile->getUser()->getHash(),
                'name' => $profile->getUser()->getFirstName(),
                'gender' => $profile->getGender()
            ),
            'text' => 'email/sendValidateNewText',
            'html' => 'email/sendValidateNew'
        ));
    }

    protected function sendUserCompleteDataMail($profile) {
        $routing = $this->getContext()->getConfiguration()->getRouting('frontend', array(
            'prefix' => '', // '' in case you want no script name displayed  
            'host' => sfConfig::get('app_domain','club.licoteca.com.ve'),
                ));

        $route = $routing->generate('sf_guard_login', array(), true);

        $this->mail(array(
            'subject' => sfContext::getInstance()->getI18N()->__('Completa los datos de cuenta en Licoteca'),
            'fullname' => $profile->getFullname(),
            'email' => $profile->getEmail(),
            'parameters' => array(
                'fullname' => $profile->getFullname(),
                'route1' => $route
            ),
            'text' => 'email/sendCompleteDataText',
            'html' => 'email/sendCompleteData'
        ));
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

    protected function setFlashMessage($name,$msg) {
        $this->getUser()->setFlash($name, $msg);
    }

}
