<?php
/**
 * Description of epCouponRedeemForm
 *
 * @author Jacobo Martínez
 */
class epCouponRedeemForm extends BaseForm
{
    public function configure()
    {
        $attributes = array(
                        'maxlength' => 12,
                        'data-bvalidator' => 'rangelength[5:12],required',
                        'data-bvalidator-msg' => 'La longitud debe ser de 5 a 12 caracteres'
                    );
        
        $this->setWidgets(array(
            'serial'    => array_key_exists('serial', $this->defaults) ? new sfWidgetFormInputHidden(array(),$attributes) : new sfWidgetFormInputText(array(), $attributes),
            'password'  => new sfWidgetFormInput(array(),$attributes),
        ));
        
        $this->widgetSchema->setLabels(array('serial' => 'Serial', 'password' => 'Contraseña'));
        
        $this->setValidators(array(
            'serial'    => new sfValidatorString(array('max_length' => 12)),
            'password'  => new sfValidatorString(array('max_length' => 12)),
        ));
        
        $this->validatorSchema->setPostValidator(new epValidatorCouponRedeem());
        
        $this->widgetSchema->setNameFormat('epCouponRedeemForm[%s]');
        
        $this->widgetSchema->setFormFormatterName('epWeb');
    }
    
}

?>
