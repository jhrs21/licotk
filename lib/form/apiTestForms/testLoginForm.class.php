<?php

/**
 * Description of testLoginForm
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class testLoginForm extends BaseForm
{
    public function configure() 
    {
        $this->setWidgets(array(
                'email' => new sfWidgetFormInput(),
                'password' => new sfWidgetFormInput(),
            ));
        
        $this->setValidators(array(
                'email' => new sfValidatorEmail(),
                'password' => new sfValidatorString(),
            ));
        
        $this->widgetSchema->setNameFormat('test_login[%s]');
    }
}

?>