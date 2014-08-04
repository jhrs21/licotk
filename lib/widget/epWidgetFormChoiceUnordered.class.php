<?php
/**
 * Description of epWidgetFormChoiceUnordered
 *
 * @author jacobo
 */
class epWidgetFormChoiceUnordered extends sfWidgetFormSelectRadio
{
    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);

        $this->addOption('with_label', true);
        $this->addOption('label_class', false);
    }
    protected function formatChoices($name, $value, $choices, $attributes)
    {
        $inputs = array();
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
            
            $inputs[$id] = array(
                'input' => $this->renderTag('input', array_merge($baseAttributes, $attributes)),
                'label' => $this->renderContentTag('label', $option, $labelAttributes),
            );
        }

        return call_user_func($this->getOption('formatter'), $this, $inputs);
    }
    
    public function formatter($widget, $inputs)
    {
        $rows = array();
        foreach ($inputs as $input)
        {
        $rows[] = $this->renderContentTag('div', ($this->getOption('with_label') ? $input['label'].$this->getOption('label_separator') : '').$input['input']);
        }

        return !$rows ? '' : $this->renderContentTag('div', implode($this->getOption('separator'), $rows), array('class' => $this->getOption('class')));
    }
}

?>
