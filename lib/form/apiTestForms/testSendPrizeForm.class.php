<?php

/**
 * Description of testLoginForm
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class testSendPrizeForm extends BaseForm
{
    public function configure() 
    {
        $this->setWidgets(array(
                'email' => new sfWidgetFormInput(),
                'hash' => new sfWidgetFormInput(),
                'hash' => new sfWidgetFormInput(),
                'hash' => new sfWidgetFormInput(),
            ));
        
        $this->setValidators(array(
                'email' => new sfValidatorEmail(),
                'hash' => new sfValidatorString(),
            ));
        
        $this->widgetSchema->setNameFormat('test_login[%s]');
    }
}

?>