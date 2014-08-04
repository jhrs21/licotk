<?php
/**
 * Description of epWidgetFormSurveyCheckbox
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class epWidgetFormSurveyCheckbox extends sfWidgetFormSelectCheckbox {
    
    protected function configure($options = array(), $attributes = array()) {
        parent::configure($options, $attributes);

        $this->addOption('first_checkbox_attributes', array());
        $this->addOption('last_checkbox_attributes', array());
    }
    
    public function render($name, $value = null, $attributes = array(), $errors = array()) {
        if (!is_null($value) && !is_array($value)) {
            $value = explode(';', $value);
        }
        
        return parent::render($name, $value, $attributes, $errors);
    }

    protected function formatChoices($name, $value, $choices, $attributes) {
        $inputs = array();
        $last = count($choices) - 1;
        $i = 0;
        
        foreach ($choices as $key => $option) {
            $baseAttributes = array(
                'name' => $name,
                'type' => 'checkbox',
                'value' => self::escapeOnce($key),
                'id' => $id = $this->generateId($name, self::escapeOnce($key)),
            );

            if ((is_array($value) && in_array(strval($key), $value)) || (is_string($value) && strval($key) == strval($value))) {
                $baseAttributes['checked'] = 'checked';
            }
            
            if ($i == 0) {
                $baseAttributes = array_merge($baseAttributes, $this->getOption('first_checkbox_attributes'));
            }
            
            if ($i == $last) {
                $baseAttributes = array_merge($baseAttributes, $this->getOption('last_checkbox_attributes'));
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
