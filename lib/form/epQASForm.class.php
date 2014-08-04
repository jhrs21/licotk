<?php

/**
 * Description of epQASForm -- Question And Suggestion Form
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class epQASForm extends BaseForm {
    
    public function configure() {        
        $this->setWidgets(array(
            'name' => new sfWidgetFormInput(array(),array('data-bvalidator' => 'validateregex,required','data-bvalidator-msg' => 'Ingresa tu nombre sin caracteres especiales')),
            'email' => new sfWidgetFormInput(array(),array('data-bvalidator' => 'email,required','data-bvalidator-msg' => 'Ingresa un correo electrónico válido')),
            'message' => new sfWidgetFormTextarea(array(),array('data-bvalidator' => 'rangelength[10:500],required','data-bvalidator-msg' => 'Ingresa un mensaje de al menos 10 caracteres')),
            'captcha' => new sfWidgetFormReCaptcha(array('public_key' => sfConfig::get('app_recaptcha_public_key')))
        ));
        
        $this->widgetSchema->setLabels(array(
            'name' => 'Nombre:',
            'email' => 'E-mail:',
            'message' => 'Dudas o sugerencias:'
        ));

        $this->setValidators(array(
            'name' => new sfValidatorString(array(),array()),
            'email' => new sfValidatorEmail(array(),array('invalid' => 'Dirección de correo electrónico inválida')),
            'message' => new sfValidatorString(array('max_length' => 500, 'min_length' => 10),array('max_length' => 'El mensaje supera el máximo de 500 caracteres', 'min_length' => 'El mensaje debe contener al menos 10 caracteres')),
            'captcha' => new sfValidatorReCaptcha(array('private_key' => sfConfig::get('app_recaptcha_private_key')),array('captcha' => 'El valor introducido no es válido'))
        ));

        $this->widgetSchema->setNameFormat('epQASForm[%s]');

        $this->widgetSchema->setFormFormatterName('epWeb');
        
        //$this->widgetSchema->setFormFormatterName('epWebFormLayout');
    }
}

?>
