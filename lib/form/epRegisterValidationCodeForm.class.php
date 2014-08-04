<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of epRegisterValidationCode
 *
 * @author jacobo
 */
class epRegisterValidationCodeForm extends BaseForm
{
    protected $user = null;
    
    public function __construct(sfGuardUser $user = null, $defaults = array(), $options = array(), $CSRFSecret = null)
    {
        $this->user = $user;

        parent::__construct($defaults = array(), $options = array(), $CSRFSecret = null);
    }
    
    public function configure()
    {
        $attributes = array(
            'data-bvalidator' => 'required',
            'data-bvalidator-msg' => 'Campo requerido'
        );
        
        if(is_null($this->user))
        {
            $this->widgetSchema['email'] = new sfWidgetFormInput(array(),$attributes);
        }
        else
        {
            $this->widgetSchema['email'] =  new sfWidgetFormInputHidden(array(),$attributes);
            
            $this->setDefault('email', $this->user->getEmailAddress());
        }
        
        $this->validatorSchema['email'] =  new sfValidatorEmail(array(), array('invalid' => 'Correo Electrónico invalido.'));
        
        $attributes = array(
            'data-bvalidator' => 'maxlength[8],required',
            'data-bvalidator-msg' => 'Ingresa un código de 8 caracteres máximo'
        );
        
        $this->widgetSchema['code'] =  new sfWidgetFormInput(array(),$attributes);
        
        $this->validatorSchema['code'] =  new sfValidatorString(
                    array('max_length' => $this->getOption('max_code_length', 8)),
                    array('required' => 'Campo obligatorio','max_length' => 'La longitud máxima de un código es %max_length%')
                );
        
        $this->validatorSchema->setPostValidator(new epValidatorValidationCodeRegistration());
        
        $this->widgetSchema->setLabels(array('code' => 'Código de Validación:'));
        
        $this->widgetSchema->setNameFormat('epRegisterValidationCode[%s]');  
    }
}

?>
