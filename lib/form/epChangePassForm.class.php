<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class epChangePassForm extends BaseForm {

    public function configure() {
//        $this->setWidget('old_password', new sfWidgetFormInputPassword(
//                        array('label' => 'Contraseña anterior:'),
//                        array(
//                            'maxlength' => 128,
//                            'data-bvalidator' => 'minlength[6],required',
//                            'data-bvalidator-msg' => 'Ingrese una contraseña de 6 caracteres mínimo.'
//                        )
//        ));
        
        $this->setWidget('password', new sfWidgetFormInputPassword(
                        array('label' => 'Contraseña:'),
                        array(
                            'maxlength' => 128,
                            'data-bvalidator' => 'minlength[6],required',
                            'data-bvalidator-msg' => 'Ingrese una contraseña de 6 caracteres mínimo.'
                        )
        ));

        $this->setWidget('password2', new sfWidgetFormInputPassword(
                        array('label' => 'Confirmar Contraseña:'),
                        array(
                            'maxlength' => 128,
                            'data-bvalidator' => 'equalto[sfApplyApply_password], required',
                            'data-bvalidator-msg' => 'Las contraseñas no coinciden.'
                        )
        ));

        $this->widgetSchema->moveField('password2', sfWidgetFormSchema::AFTER, 'password');
        
//        $this->setValidator('old_password', new sfValidatorString(
//                        array(
//                            'required' => true,
//                            'trim' => true,
//                            'min_length' => 6,
//                            'max_length' => 128
//                        ),
//                        array(
//                            'required' => 'Campo obligatorio.',
//                            '' => 'Contraseña no coincide con la ya almacenada'
//                        )
//        ));
        
        $this->setValidator('password', new sfValidatorString(
                        array(
                            'required' => true,
                            'trim' => true,
                            'min_length' => 6,
                            'max_length' => 128
                        ),
                        array(
                            'required' => 'Campo obligatorio.',
                            'min_length' => 'La contraseña es muy corta. Debe contener entre %min_length% y %max_length% caracteres.',
                            'max_length' => 'La contraseña es muy corta. Debe contener entre %min_length% y %max_length% caracteres.'
                        )
        ));

        $this->setValidator('password2', new sfValidatorString(
                        array(
                            'required' => true,
                            'trim' => true,
                            'min_length' => 6,
                            'max_length' => 128
                        ),
                        array(
                            'required' => 'Campo obligatorio.',
                            'min_length' => 'La contraseña es muy corta. Debe contener entre %min_length% y %max_length% caracteres.',
                            'max_length' => 'La contraseña es muy corta. Debe contener entre %min_length% y %max_length% caracteres.'
                        )
        ));
        
        $this->widgetSchema->setNameFormat('changePass[%s]');
        
        $this->widgetSchema->setFormFormatterName('epWeb');
    }

}

?>
