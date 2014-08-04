<?php

/**
 * EmbeddedPromoConditionForm is a subclass of PromoConditionForm
 * It is used as an embedded form in PromoForm
 *
 * @author Jacobo Martínez
 */

class EmbeddedLocationAjaxForm extends EmbeddedLocationForm
{
    protected $modifiedWidget;
    
    protected $modifiedValue;

    public function __construct($modifiedWidget, $modifiedValue)
    {
        $this->modifiedWidget = $modifiedWidget;
        
        $this->modifiedValue = $modifiedValue;

        parent::__construct();
    }
    
    public function configure()
    {
        parent::configure();
        
        unset($this['country_id'], $this['address'], $this['latitude'], $this['longitude']);
        
        
    }
    
    protected function updateChoices($modifiedWidget, $modifiedValue)
    {
        $choices = array();
        
        switch ($choice_1) 
        {
            case 1:
                $choices = array('1' => 'Sub item 1 of Item 1', '2' => 'Sub item 2 of Item 1');
                break;
            case 2:
                $choices = array('3' => 'Sub item 2 of Item 2', '4' => 'Sub item 2 of Item 2');
                break;
        }
        
        return $choices;
    }
}

?>
