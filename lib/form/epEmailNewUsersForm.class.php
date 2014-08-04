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
class epEmailNewUsersForm extends sfForm {
    public function configure() {
        parent::configure();
        
        $this->setWidgets(array(
            'emails' => new sfWidgetFormInputText()
        ));

        $this->setValidators(array(
            'emails' => new sfValidatorString(array('required'=>true))
        ));
        
        $this->widgetSchema->setLabels(array(
            'emails' => 'correos'
        ));
        
        $this->widgetSchema->setHelps(array(
            'emails' => '(NOTA: los correos deben estar separados por ";")'
        ));
        
        $this->widgetSchema->setNameFormat('emailNewUsers[%s]');
    }
}

?>
