<?php

class epUserApplyApiForm extends epUserApplyMobileForm {

    public function configure() {
        parent::configure();

        unset($this['privacyPolicy']);
        
        if ($this->getOption('update_password', false)) {
            $this->setWidget('password', new sfWidgetFormInputPassword(
                    array('label' => 'Contraseña:'),
                    array('maxlength' => 128, 'data-bvalidator' => 'minlength[6],required', 'data-bvalidator-msg' => 'Ingrese una contraseña de 6 caracteres mínimo.')
                ));

            $this->setValidator('password', new sfValidatorString(
                    array('required' => true, 'trim' => true, 'min_length' => 6, 'max_length' => 128),
                    array('required' => 'Campo obligatorio.', 'min_length' => 'La contraseña es muy corta. Debe contener entre %min_length% y %max_length% caracteres.', 'max_length' => 'La contraseña es muy corta. Debe contener entre %min_length% y %max_length% caracteres.')
                ));
        }
    }
}