<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of epEmailReminderForm
 *
 * @author usurio
 */
class epEmailReminderForm extends sfForm {
    public function configure() {
        parent::configure();
        $promos = $this->getOption('promos');
        $promos = array('' => '') + $promos;

        $this->setWidgets(array(
            'promo' => new sfWidgetFormChoice(array('choices' => $promos,'expanded' => false))
        ));

        $this->setValidators(array(
            'promo' => new sfValidatorChoice(array('choices'=>array_keys($promos), 'required'=>true))
        ));
        
        $this->widgetSchema->setLabels(array(
            'promo' => 'Promoción'
        ));
        
        $this->widgetSchema->setNameFormat('emailRemainder[%s]');
    }
}

?>
