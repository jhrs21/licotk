<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class epGenerateCouponForm extends sfForm {
    protected $user;
    protected $card;
    
    public function configure() {
        parent::configure();
        
        if (!($this->getOption('user') instanceof sfGuardUser)) {
            throw new InvalidArgumentException("You must pass a sfGuardUser object as an option to this form!");
        } else {
            $user = $this->getOption('user');
        }
        
        if (!($this->getOption('card') instanceof Card)) {
            throw new InvalidArgumentException("You must pass a Card object as an option to this form!");
        } else {
            $card = $this->getOption('card');
        }

        $choices=array();
        
        $prizesIds = $card->getCanBeExchangedFor();
        
        foreach(Doctrine::getTable('PromoPrize')->retrieveByAlphaIds($prizesIds) as $prize){
            $choices[$prize->getAlphaId()] = $prize->getPrize();
        }

        $this->widgetSchema['prize'] = new sfWidgetFormChoice(array('choices' => $choices));
        
        $this->validatorSchema['prize'] = new sfValidatorChoice(array('choices' => array_keys($choices)));
        
        $this->widgetSchema->setLabels(array('prize' => 'Premio',));
        
        $this->widgetSchema->setNameFormat('generateCoupon[%s]');
        
        $this->validatorSchema->setPostValidator(new epValidatorGenerateCoupon(array('user' => $user,'card' => $card)));
    
        $this->widgetSchema->setFormFormatterName('epWeb');
    }
}