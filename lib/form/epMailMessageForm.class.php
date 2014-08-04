<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class epMailMessageForm extends MailMessageForm {

    public function configure() {
        parent::configure();
        
        /**
         *  Inherited Widgets 
         */
        $this->widgetSchema['teaser']->setAttributes(array('style' => 'width:100%', 'data-bvalidator' => 'minlength[5],maxlength[255],required','data-bvalidator-msg' => 'Ingresa un mensaje de 5 hasta 255 caracteres.'));
        $this->widgetSchema['subject']->setAttributes(array('style' => 'width:100%', 'data-bvalidator' => 'minlength[5],maxlength[255],required','data-bvalidator-msg' => 'Ingresa el asunto de correo, utiliza de 5 hasta 255 caracteres.'));
        $this->widgetSchema['body']->setAttributes(array(
            'data-bvalidator' => 'minlength[10],maxlength[4000],required',
            'data-bvalidator-msg' => 'Ingresa el cuerpo del correo, utiliza de 10 hasta 4000 caracteres.',
            'style' => 'width:100%'
            ));
        
//        $this->widgetSchema['body'] = new sfWidgetFormTextareaTinyMCE(array(
//                'width'  => 550,
//                'height' => 350,
//                'config' => 'theme_advanced_disable: "anchor,image,cleanup,help"',
//            ));
        
        /**
         *  Inherited Validators 
         */
        $this->validatorSchema['teaser']->setOption('min_length',10);
        $this->validatorSchema['subject']->setOption('min_length',10);
        $this->validatorSchema['body']->setOption('min_length',10);
        
        $this->validatorSchema['teaser']->setMessages(array('required' => 'Ingresa el cintillo del correo','min_length' => 'Ingresa un mensaje de al menos 10 caracteres', 'max_length' => 'Ingresa un mensaje de máximo 255 caracteres'));
        $this->validatorSchema['subject']->setMessages(array('required' => 'Ingresa el asunto del correo','min_length' => 'Ingresa un mensaje de al menos 10 caracteres', 'max_length' => 'Ingresa un mensaje de máximo 255 caracteres'));
        $this->validatorSchema['body']->setMessages(array('required' => 'Ingresa el cuerpo del correo','min_length' => 'Ingresa un mensaje de al menos 10 caracteres', 'max_length' => 'Ingresa un mensaje de máximo 4000 caracteres'));

        $this->widgetSchema->setLabels(array(
            'teaser' => 'Cintillo',
            'subject' => 'Asunto',
            'body' => 'Cuerpo'
        ));
        
        //$this->validatorSchema->setPostValidator(new epValidatorContact());
        
        //$this->widgetSchema->setFormFormatterName('epWeb');
    }
    
}

?>
