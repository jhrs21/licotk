<?php
/**
 * 
 */
class epUserLicotecaLevelForm extends BaseForm
{
    public function configure()
    {
        $levels = Doctrine::getTable('LicotecaUserLevel')->findAll();
        $default = $this->getOption('level');
        $choices = array();
        
        foreach($levels as $level){
            $choices[$level->getId()] = $level->getName();
        }
        
        $this->setWidgets(array(
            'level' => new sfWidgetFormChoice(array('choices' => $choices,'default' => $default))
        ));
        
        $this->setValidators(array(
            'level' => new sfValidatorChoice(array('choices' => array_keys($choices),'required' => true))
        ));
        
        $this->widgetSchema->setLabels(array(
            'level'    => 'Nuevo Nivel'
        ));
        
        $this->widgetSchema->setNameFormat('epUserLicotecaLevelForm[%s]');
        
        $this->widgetSchema->setFormFormatterName('epWeb');
    }
}