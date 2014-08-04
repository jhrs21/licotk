<?php

/**
 * Description of epUserUpdate
 *
 * @author jacobo
 */
class epUserUpdateForm extends epUserApplyForm
{
    public function configure()
    {
        parent::configure();

        $this->setWidget('user_id', new sfWidgetFormInputHidden());
        $this->setWidget('password', new sfWidgetFormInputHidden());
        $this->setWidget('password2', new sfWidgetFormInputHidden());
        $this->setWidget('country_id', new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Country'), 'add_empty' => true)));
        $this->setWidget('state_id', new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('State'), 'add_empty' => true)));
        $this->setWidget('municipality_id', new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Municipality'), 'add_empty' => true)));
        $this->setWidget('city_id', new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('City'), 'add_empty' => true)));
        
        $this->widgetSchema->setNameFormat('sfApplyUpdate[%s]');
        
        $this->setValidator('user_id', new sfValidatorChoice(array('choices' => array($this->getObject()->getUserId()))));
        $this->setValidator('country_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Country'), 'required' => false)));
        $this->setValidator('state_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('State'), 'required' => false)));
        $this->setValidator('municipality_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Municipality'), 'required' => false)));
        $this->setValidator('city_id', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('City'), 'required' => false)));
        
        $this->setDefaultValues();
    }
    
    public function setDefaultValues() {
        $user=$this->getObject()->getUser();
        $this->setDefault('first_name', $user->getFirstName());
        $this->setDefault('last_name', $user->getLastName());
    }
    
}
