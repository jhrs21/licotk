<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of epApplyResetForm
 *
 * @author jacobo
 */
class epUserResetPasswordForm extends sfApplyResetForm 
{
    public function configure() 
    {
        $this->setWidget('password', new sfWidgetFormInputPassword(array(), array('maxlength' => 128)));
        
        $this->setWidget('password2', new sfWidgetFormInputPassword(array(), array('maxlength' => 128)));
        
        $this->setValidator('password', new sfValidatorString(
                    array('required' => true,
                        'trim' => true,
                        'min_length' => 6,
                        'max_length' => 128
                    ), array(
                        'min_length' => 'La contraseña es muy corta. Debe contener al menos %min_length% caracteres.'
                    )
                ));
        $this->setValidator('password2', new sfValidatorString(
                    array(
                        'required' => true,
                        'trim' => true,
                        'min_length' => 6,
                        'max_length' => 128
                    ), array(
                        'min_length' => 'La contraseña es muy corta. Debe contener al menos %min_length% caracteres.'
                    )
                ));
        
        $this->validatorSchema->setPostValidator(
                    new sfValidatorSchemaCompare(
                        'password', 
                        sfValidatorSchemaCompare::EQUAL, 
                        'password2',
                        array(), 
                        array('invalid' => 'Las contraseñas no son iguales.')
                    )
                );
        
        $this->widgetSchema->setLabels(array(
            'password' => 'Nueva Contraseña',
            'password2' => 'Confirmar Nueva Contraseña'));
        
        $this->widgetSchema->setNameFormat('sfApplyReset[%s]');
        
        $this->widgetSchema->setFormFormatterName('table');
    }

}