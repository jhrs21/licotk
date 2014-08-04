<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class epSelfServiceFourthForm extends BaseForm {

    public function configure() {
        $this->setWidgets(array(
            'prize' => new sfWidgetFormInput(),
            'threshold' => new sfWidgetFormInput(),
            'stock' => new sfWidgetFormInput()
        ));

        $this->widgetSchema->setLabels(array(
            'prize' => 'Premio',
            'threshold' => 'Cantidad de "tags" para obtener premio',
            'stock' => 'Cantidad de premios disponibles'
        ));

        $this->setValidators(array(
            'prize' => new sfValidatorString(),
            'threshold' => new sfValidatorInteger(),
            'stock' => new sfValidatorInteger(),
        ));

        $this->widgetSchema->setFormFormatterName('epWeb');
        $this->widgetSchema->setNameFormat('fourthStep[%s]');
    }

}

?>
