<?php

/**
 * Description of epWidgetFormSurveyRadioButton
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class epWidgetFormSurveyRadioButton extends sfWidgetFormSelectRadio {

    protected function configure($options = array(), $attributes = array()) {
        parent::configure($options, $attributes);

        $this->addOption('first_radio_attributes', array());
        $this->addOption('last_radio_attributes', array());
    }

    protected function formatChoices($name, $value, $choices, $attributes) {
        $inputs = array();
        $last = count($choices) - 1;
        $i = 0;
        
        foreach ($choices as $key => $option) {
            $baseAttributes = array(
                'name' => substr($name, 0, -2),
                'type' => 'radio',
                'value' => self::escapeOnce($key),
                'id' => $id = $this->generateId($name, self::escapeOnce($key)),
            );

            if (strval($key) == strval($value === false ? 0 : $value)) {
                $baseAttributes['checked'] = 'checked';
            }
            
            if ($i == 0) {
                $baseAttributes = array_merge($baseAttributes, $this->getOption('first_radio_attributes'));
            }
            
            if ($i == $last) {
                $baseAttributes = array_merge($baseAttributes, $this->getOption('last_radio_attributes'));
            }

            $inputs[$id] = array(
                'input' => $this->renderTag('input', array_merge($baseAttributes, $attributes)),
                'label' => $this->renderContentTag('label', self::escapeOnce($option), array('for' => $id)),
            );
            
            $i++;
        }

        return call_user_func($this->getOption('formatter'), $this, $inputs);
    }
}