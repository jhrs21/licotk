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
        $this->promos = Doctrine::getTable('Promo')->findAll();

        $this->setLayout('layout_blueprint');
    }

    public function executeShow(sfWebRequest $request) {
        $promo = $this->getRoute()->getObject();

        $this->promo = $promo;

        $this->setLayout('layout_blueprint');
    }

    public function executeNew(sfWebRequest $request) {
        $this->form = new PromoForm(null, array('user' => $this->getUser(), 'routing' => $this->getContext()->getRouting()));

        if ($request->isMethod('post')) {
            $this->processForm($request, $this->form);
        }

        $this->setLayout('layout_blueprint');
    }

    public function executeEdit(sfWebRequest $request) {
        $promo = $this->getRoute()->getObject();

        $this->form = new PromoForm($promo, array('user' => $this->getUser(), 'routing' => $this->getContext()->getRouting()));

        if ($request->isMethod('put')) {
            $this->processForm($request, $this->form);
        }

        $this->setLayout('layout_blueprint');
    }

    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();
        $this->getRoute()->getObject()->delete();
        $this->redirect('promo/index');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

        if ($form->isValid()) {
            $promo = $form->save();
            $this->redirect($this->getController()->genUrl(array('sf_route' => 'promo_show', 'id' => $promo->getId()), false));
        }
    }

    public function executeListCoupon(sfWebRequest $request) {
        if ($request->getParameter('success', false)) {
            $this->getUser()->setFlash('success', '<h2>El cupón ha sido canjeado satisfactoriamente.</h2>');
        }

        $this->promo = $this->getRoute()->getObject();

        $this->coupons = $this->promo->getCoupons();
    }

    public function executeRedeemCoupon(sfWebRequest $request) {
        $this->form = new epCouponRedeemForm();

        if ($request->getParameter('success', false)) {
            $this->getUser()->setFlash('success', '<h2>El cupón ha sido canjeado satisfactoriamente.</h2>');
        }

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {
                $values = $this->form->getValues();

                $coupon = $values['coupon'];
                
                $coupon->setStatus('used');
                $coupon->getCard()->setStatus('redeemed');
                $coupon->save();

                $this->redirect($this->getController()->genUrl(array('sf_route' => 'promo_list_coupon', 'id' => $coupon->getPromo()->getId()), false) . '?success=1');
            }
        }
    }

    public function executeAddPromoPrizeForm(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());

        $count = $request->getParameter('count', 0);
        $promo = false;
        
        if ($request->hasParameter('id')) {
            $promo = Doctrine::getTable('Promo')->findOneById($request->getParameter('id'));
        }

        if ($promo) {
            $form = new PromoForm($promo, array('user' => $this->getUser(), 'routing' => $this->getContext()->getRouting()));
        }
        else {
            $form = new PromoForm(null, array('user' => $this->getUser(), 'routing' => $this->getContext()->getRouting()));
        }
        
        $form->addPrize($count);

        $this->prize = $form['prizes'][$count];

        $this->setLayout(false);
    }

    public function executeDeletePromoPrize(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());

        $this->getRoute()->getObject()->delete();

        $this->getResponse()->setHeaderOnly(true);
        $this->getResponse()->setStatusCode(200);
        return sfView::NONE;
    }

    public function executeAddPromoTermForm(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());
        
        $count = $request->getParameter('count', 0);
        $promo = false;
        
        if ($request->hasParameter('id')) {
            $promo = Doctrine::getTable('Promo')->findOneById($request->getParameter('id'));
        }

        if ($promo) {
            $form = new PromoForm($promo, array('user' => $this->getUser(), 'routing' => $this->getContext()->getRouting()));
        }
        else {
            $form = new PromoForm(null, array('user' => $this->getUser(), 'routing' => $this->getContext()->getRouting()));
        }

        $form->addTerm($count);

        $this->term = $form['terms'][$count];

        $this->setLayout(false);
    }

    public function executeDeletePromoTerm(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());

        $this->getRoute()->getObject()->delete();

        $this->getResponse()->setHeaderOnly(true);
        $this->getResponse()->setStatusCode(200);
        return sfView::NONE;
    }

    public function executePopulateAssets(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());
        $form = new PromoForm(null,
                        array(
                            'affiliate' => $request->getParameter('value'),
                            'setAssetName' => true,
                            'user' => $this->getUser(),
                            'routing' => $this->getContext()->getRouting(),
                        )
                    );

        $this->assets = $form['assets_list'];

        $this->setLayout(false);
    }

}
