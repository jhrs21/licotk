<?php
/**
 * Description of epWidgetFormChoiceUnordered
 *
 * @author jacobo
 */
class epWidgetFormChoicesInLine extends sfWidgetFormSelectRadio
{
    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);

        $this->addOption('bvalidator_attrs', array());
        $this->addOption('with_label', true);
        $this->addOption('label_class', false);
    }
    
    protected function formatChoices($name, $value, $choices, $attributes)
    {
        $inputs = array();
        $numChoices = count($choices);
        $i = 0;
        $bvalidatorAttibutes = $this->getOption('bvalidator_attrs');
        
        foreach ($choices as $key => $option)
        {
            $baseAttributes = array(
                'name'  => substr($name, 0, -2),
                'type'  => 'radio',
                'value' => self::escapeOnce($key),
                'id'    => $id = $this->generateId($name, self::escapeOnce($key)),
            );

            if (strval($key) == strval($value === false ? 0 : $value))
            {
                $baseAttributes['checked'] = 'checked';
            }
            
            $labelAttributes = array('for' => $id, 'id' => 'label_'.$id);
            
            if($this->getOption('label_class'))
            {
                $labelAttributes['class'] = $this->getOption('label_class');
            }
            
            $i++;
            
            if(count($bvalidatorAttibutes) && $numChoices == $i){
                $inputs[$id] = array(
                        'input' => $this->renderTag('input', array_merge($baseAttributes, $attributes, $bvalidatorAttibutes)),
                        'label' => $this->renderContentTag('label', $option, $labelAttributes),
                    );
            }
            else {
                $inputs[$id] = array(
                        'input' => $this->renderTag('input', array_merge($baseAttributes, $attributes)),
                        'label' => $this->renderContentTag('label', $option, $labelAttributes),
                    );
            }
        }

        return call_user_func($this->getOption('formatter'), $this, $inputs);
    }
    
    public function formatter($widget, $inputs)
    {
        $rows = array();
        foreach ($inputs as $input)
        {
        $rows[] = $this->renderContentTag('li', ($this->getOption('with_label') ? $input['label'].$this->getOption('label_separator') : '').$input['input']);
        }

        return !$rows ? '' : $this->renderContentTag('ul', implode($this->getOption('separator'), $rows), array('class' => $this->getOption('class')));
    }
}

?>
