<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class epContactForm extends sfForm {

    public function configure() {
        parent::configure();

        $this->setWidgets(array(
            'business' => new sfWidgetFormInput(array(),array('data-bvalidator' => 'validateregex,required','data-bvalidator-msg' => 'Ingresa el nombre del negocio sin caracteres especiales')),
            'address' => new sfWidgetFormInput(array(),array('data-bvalidator' => 'required','data-bvalidator-msg' => 'Ingresa la direción del negocio')),
            'rif' => new sfWidgetFormInput(array(),array('data-bvalidator' => 'required','data-bvalidator-msg' => 'Ingresa la direción del negocio')),
            'type' => new sfWidgetFormInput(array(),array('data-bvalidator' => 'required','data-bvalidator-msg' => 'Ingresa el rif del negocio')),
            'name' => new sfWidgetFormInput(array(),array('data-bvalidator' => 'required','data-bvalidator-msg' => 'Ingresa el nombre de la persona de contacto')),
            'phone' => new sfWidgetFormInput(array(),array('data-bvalidator' => 'digit,required','data-bvalidator-msg' => 'Ingresa un número telefónico de contacto, solo números')),
            'email' => new sfWidgetFormInput(array(),array('data-bvalidator' => 'email,required','data-bvalidator-msg' => 'Ingresa un correo electróncio de contacto')),
            'captcha' => new sfWidgetFormReCaptcha(array('public_key' => sfConfig::get('app_recaptcha_public_key')),array('data-bvalidator' => 'required','data-bvalidator-msg' => 'Ingresa las palabras que aparecen en el recuadro'))
        ));

        $this->setValidators(array(
            'business' => new sfValidatorString(array('required' => true)),
            'address' => new sfValidatorString(array('required' => true)),
            'rif' => new sfValidatorString(array('required' => true)),
            'type' => new sfValidatorString(array('required' => true)),
            'name' => new sfValidatorString(array('required' => true)),
            'phone' => new sfValidatorPhoneNumber(array('required' => true)),
            'email' => new sfValidatorEmail(array('required' => true), array('required' => "El email es obligatorio")),
            'captcha' => new sfValidatorReCaptcha(array('private_key' => sfConfig::get('app_recaptcha_private_key')),array('captcha' => 'El valor introducido no es válido'))
        ));

        $this->widgetSchema->setLabels(array(
            'business' => 'Nombre del negocio',
            'address' => 'Dirección',
            'rif' => 'RIF',
            'type' => 'Tipo de negocio',
            'name' => 'Persona de contacto',
            'phone' => 'Teléfono',
            'email' => 'Correo',
        ));

        $this->widgetSchema->setNameFormat('contact[%s]');
        $this->widgetSchema->setFormFormatterName('epWeb');
        
        //$this->validatorSchema->setPostValidator(new epValidatorContact());
        
        
    }

}

?>
