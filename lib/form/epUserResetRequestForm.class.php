<?php

/**
 * sfGuardFormSignin for sfGuardAuth signin action
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfGuardFormSignin.class.php 23536 2009-11-02 21:41:21Z Kris.Wallsmith $
 */
class epUserResetRequestForm extends sfApplyResetRequestForm
{
    public function configure() 
    {
        parent::configure();
        
        $this->widgetSchema->setLabels(array('username_or_email' => 'Correo electrónico:'));
        
        $this->validatorSchema['username_or_email']->setMessages(array(
                'required' => 'Campo obligatorio',
                'invalid' => 'Correo electrónico inválido.'
            ));
        
        $this->widgetSchema->setNameFormat('sfApplyResetRequest[%s]');
        
        $this->widgetSchema->setFormFormatterName('table');
    }
}
