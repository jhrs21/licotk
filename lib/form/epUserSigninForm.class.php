<?php

/**
 * sfGuardFormSignin for sfGuardAuth signin action
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfGuardFormSignin.class.php 23536 2009-11-02 21:41:21Z Kris.Wallsmith $
 */
class epUserSigninForm extends BasesfGuardFormSignin
{
  /**
   * @see sfForm
   */
    public function configure()
    {
        parent::configure();
        
        $this->widgetSchema['username']->setAttributes(array(
                'data-bvalidator' => 'email,required',
                'data-bvalidator-msg' => 'Ingrese una dirección de E-mail válida.'
            ));
        
        $this->widgetSchema['password']->setAttributes(array(
                'data-bvalidator' => 'required',
                'data-bvalidator-msg' => 'Campo obligatorio.'
            ));
        
        $this->widgetSchema->setLabels(array(
                'username' => 'Email:',
                'password' => 'Contraseña:',
                'remember' => 'Recordarme'
            ));
        
        $this->validatorSchema['username']->setMessage('required','Campo obligatorio');
        
        $this->validatorSchema['password']->setMessage('required','Campo obligatorio');
        
        $this->validatorSchema->setPostValidator(new epValidatorUser());
        
        $this->widgetSchema->setFormFormatterName('epWeb');
    }
}
