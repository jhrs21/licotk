<?php
/**
 * 
 */
class epLicotecaLevelForm extends BaseForm
{
    public function configure()
    {
        $levels = Doctrine::getTable('LicotecaUserLevel')->findAll();
        
        $choices = array();
        
        foreach($levels as $level){
            $choices[$level->getId()] = $level->getName();
        }
           
        $this->setWidgets(array(
            'level' => new sfWidgetFormChoice(array('choices' => $choices)),
            'bottom' => new sfWidgetFormInput(),
            'top' => new sfWidgetFormInput()
        ));
        
        $this->setValidators(array(
            'level' => new sfValidatorChoice(array('choices' => array_keys($choices),'required' => true)),
            'bottom' => new sfValidatorInteger(array('required' => true)),
            'top' => new sfValidatorInteger(array('required' => true))
        ));
        
        $this->widgetSchema->setLabels(array(
            'level'    => 'Nivel',
            'bottom'   => 'Límite inferior',
            'top' => 'Límite superior',
        ));
        
        $this->widgetSchema->setNameFormat('epLicotecaLevelForm[%s]');
        
        $this->widgetSchema->setFormFormatterName('epWeb');
    }
}