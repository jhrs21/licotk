<?php

/**
 * epSigninBizForm for sfGuardAuth signin action in Biz app
 */
class epSigninBizForm extends BasesfGuardFormSignin {

    /**
     * @see sfForm
     */
    public function configure() {
        $this->widgetSchema->setLabels(array(
            'username' => 'Usuario:',
            'password' => 'Contraseña:',
            'remember' => 'Recordar'
        ));
        
        $this->validatorSchema['username'] = new sfValidatorString(
                array('required' => true, 'trim' => true), 
                array('required' => 'Campo obligatorio', 'invalid' => 'Correo electrónico inválido')
            );
        
        $this->validatorSchema->setPostValidator(new sfGuardValidatorUser(array(),array('invalid'=>'Contraseña o usuario incorrecto')));
    }

}
