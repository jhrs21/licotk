<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class epActivationAdminForm extends sfForm {

    public function configure() {
        parent::configure();

        $this->setWidgets(array(
            'promo' => new sfWidgetFormInput(),
            'promocode' => new sfWidgetFormInput(),
            'serial-inferior' => new sfWidgetFormInput(),
            'serial-superior' => new sfWidgetFormInput()
        ));

        $this->setValidators(array(
            'promo' => new sfValidatorString(),
            'promocode' => new sfValidatorString(),
            'serial-inferior' => new sfValidatorInteger(),
            'serial-superior' => new sfValidatorInteger()
        ));

        $this->widgetSchema->setLabels(array(
            'promo' => 'Promoción',
            'promocode' => 'Código de promoción',
            'serial-inferior' => 'Serial inferior',
            'serial-superior' => 'Serial superior',
        ));

        $this->widgetSchema->setFormFormatterName('epWeb');
        $promocode = Doctrine::getTable('PromoCode')->findOneBy('id', $this->getOption('promocode_id'));
        $promo = $promocode->getPromo();
        $this->setDefault('promocode', $promocode);
        $this->setDefault('promo', $promo);
    }

}

?>
