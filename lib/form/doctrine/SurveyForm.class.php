<?php

/**
 * Survey form.
 *
 * @package    elperro
 * @subpackage form
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SurveyForm extends BaseSurveyForm {
    public function configure() {
        
        if (!($this->getOption('routing') instanceof sfRouting)) {
            throw new InvalidArgumentException("You must pass a routing object as an option to this form!");
        } else {
            $routing = $this->getOption('routing');
        }
        
        unset($this['alpha_id'],$this['created_at'],$this['updated_at']);
        
        $this->widgetSchema['description'] = new sfWidgetFormTextarea();
        
        //Embedding at least a Item form
        $items = $this->getObject()->getItems();
        if (!$items->count() && $this->getObject()->isNew()) {
            $item = new SurveyItem();
            
            $item->setSurvey($this->getObject());
            $item->setItemType('text');
            
            $items = array($item);
        }
        //An empty form will act as a container for all the PromoPrizes
        $items_forms = new SfForm();
        $count = 0;
        foreach ($items as $item) {
            $item_form = new SurveyItemForm($item, array('routing' => $routing));
            //Embedding each form in the container
            $items_forms->embedForm($count, $item_form);
            $count++;
        }
        //Embedding the container for all the PromoPrizes in the main form
        $this->embedForm('items', $items_forms);
    }
    
    public function addItem($num) {
        $item = new SurveyItem();
        
        $item->setSurvey($this->getObject());
        $item->setItemType('text');
        
        $item_form = new SurveyItemForm($item, array('routing' => $this->getOption('routing')));

        //Embedding the new PromoPrize in the container
        $this->embeddedForms['items']->embedForm($num, $item_form);
        //Re-embedding the container
        $this->embedForm('items', $this->embeddedForms['items']);
    }
    
    public function addOption($itemNum, $optionNum) {
        try {
            $item_form = $this->getEmbeddedForm('items')->getEmbeddedForm($itemNum);
        } catch (Exception $exc) {
            $item = new SurveyItem();
        
            $item->setSurvey($this->getObject());
            $item->setItemType('simple_selection');

            $item_form = new SurveyItemForm($item, array('routing' => $this->getOption('routing')));
        }
        
        $item_form->addOption($optionNum);
        
        $this->embeddedForms['items']->embedForm($itemNum, $item_form);
        
        //Re-embedding the container
        $this->embedForm('items', $this->embeddedForms['items']);
    }
    
    public function bind(array $taintedValues = null, array $taintedFiles = null) {
        foreach ($taintedValues['items'] as $key => $item) {
            if (!isset($this['items'][$key])) {
                $this->addItem($key);
            }
                
            if (isset($taintedValues['items'][$key]['options'])) {
                foreach ($taintedValues['items'][$key]['options'] as $k => $option) {
                    if (!isset($this['items'][$key]['options']) || !isset($this['items'][$key]['options'][$k])) {
                        $this->addOption($key,$k);
                    }
                }
            }
        }
        
        parent::bind($taintedValues, $taintedFiles);
    }
}
