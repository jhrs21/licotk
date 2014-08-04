<?php

/**
 * self_service actions.
 *
 * @package    elperro
 * @subpackage self_service
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class self_serviceActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $this->form1 = new epSelfServiceFirstForm();
        $this->form2 = new epSelfServiceSecondForm();
        $this->formP = new epSelfServicePromoForm();

//        $thumbnail = new sfThumbnail(150, 150);
//        $thumbnail->loadFile('C:\Users\Jose\Dropbox\Photos\prueba.jpg');
//        $thumbnail->save('C:\Users\Jose\Dropbox\Photos\prueba2.png', 'image/png');

        if ($request->isMethod('post')) {
            $this->form1->bind($request->getParameter($this->form1->getName()));
            $this->form2->bind($request->getParameter($this->form2->getName()), $request->getFiles($this->form2->getName()));
            $this->formP->bind($request->getParameter($this->formP->getName()), $request->getFiles($this->formP->getName()));
            if ($this->form1->isValid() && $this->form2->isValid() && $this->formP->isValid()) {
                $values1 = $this->form1->getValues();
                $category = Doctrine::getTable("Category")->findBy('name', $values1['category'])->getFirst();
                $affiliate = new Affiliate();
                $affiliate->setAlphaId(Util::gen_uuid());
                $affiliate->setHash(Util::gen_uuid('', 64));
                $affiliate->setName($values1['affiliate']);
                $affiliate->setActive(0);
                $affiliate->setCategoryId($category->getId());
                //$affiliate->save();

                $user = new sfGuardUser();
                $user->setAffiliate($affiliate);
                $user->setEmailAddress($values1['email']);
                $user->setFirstName($values1['username']);
                $user->setLastName($values1['username']);
                $user->setPassword($values1['password']);
                //$user->save();

                $values2 = $this->form2->getValues();
                echo "res => ".$values2['picture']." - ".$values2['asset'];
                var_dump($values2['picture']);
                $asset = new Asset();
                $asset->setName($values2['asset']);
                $asset->setAffiliate($affiliate);
                $asset->setAssetType('place');
                $asset->setCategory($category);
                //$asset->save();
                
                $country = new Country();

                $location = new Location();
                $location->setAddress($values2['address']);
                $location->setLatitude($values2['latitude']);
                $location->setLongitude($values2['longitude']);
                $location->setAffiliate($affiliate);
                $location->setAsset($asset);
                $location->setCountryId(9);         //Venezuela
                $location->setStateId(28);          //Miranda
                $location->setMunicipalityId(22);   //Chacao
                $location->setCityId(5);            //Caracas
                //$location->save();

                $values3 = $this->formP->getValues();
                $this->formP->getObject()->setAffiliate($affiliate);
                $this->formP->getObject()->setName($values3['name']);
                $this->formP->getObject()->setDescription($values3['description']);
                $this->formP->getObject()->setMaxUses($values3['max_uses']);
                $this->formP->getObject()->setMaxDailyTags($values3['max_daily_tags']);
                $this->formP->getObject()->setStartsAt($values3['starts_at']);
                $this->formP->getObject()->setEndsAt($values3['ends_at']);
                $this->formP->getObject()->setBeginsAt($values3['begins_at']);
                $this->formP->getObject()->setExpiresAt($values3['expires_at']);
                //$this->formP->save();

                //$this->redirect('self_service/index');
            }
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

    public function executeThankyou(sfWebRequest $request) {
        
    }

}
