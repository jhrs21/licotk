<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of epSelfServiceSecondForm
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class epSelfServiceSecondForm extends BaseForm {

    public function configure() {
        $this->setWidgets(array(
            'asset' => new sfWidgetFormInputText(),
            'picture' => new sfWidgetFormInputFile(),
            'latitude' => new sfWidgetFormInputHidden(),
            'longitude' => new sfWidgetFormInputHidden(),
            'address' => new sfWidgetFormInputText()
        ));

        $this->widgetSchema->setLabels(array(
            'asset' => 'Nombre del establecimiento',
            'picture' => 'Logo',
            'latitude' => 'Latitud',
            'longitude' => 'Longitud',
            'address' => 'Dirección'
        ));

        $this->setValidators(array(
            'asset' => new sfValidatorString(),
            'picture' => new sfValidatorFile(array( 'required' => false,
                                                    'mime_types' => 'web_images',
                                                    'path' => sfConfig::get('sf_web_dir')),
                                            array( // messages
                                                'required'   => 'Please select a file to upload',
                                                'mime_types' => 'The file type is not allowed (%mime_type%).')),
            'latitude' => new sfValidatorString(),
            'longitude' => new sfValidatorString(),
            'address' => new sfValidatorString()
        ));

        $this->widgetSchema->setFormFormatterName('epWeb');
        $this->widgetSchema->setNameFormat('secondStep[%s]');
    }

}

?>
