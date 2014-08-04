<?php

class sfWidgetFormFilterInputRange extends sfWidgetFormInputRange {

    /**
     * Configures the current widget.
     *
     * Available options:
     *
     *  * with_empty:      Whether to add the empty checkbox (true by default)
     *  * empty_label:     The label to use when using an empty checkbox
     *  * template:        The template used for from value and to value
     *                     Available placeholders: %from_value%, %to_value%
     *  * filter_template: The template to use to render the widget
     *                     Available placeholders: %value_range%, %empty_checkbox%, %empty_label%
     *
     * @param array $options     An array of options
     * @param array $attributes  An array of default HTML attributes
     *
     * @see sfWidgetForm
     */
    protected function configure($options = array(), $attributes = array()) {
        parent::configure($options, $attributes);

        $this->addOption('with_empty', true);
        $this->addOption('empty_label', 'is empty');
        $this->addOption('template', 'from %from_value%<br />to %to_value%');
        $this->addOption('filter_template', '%value_range%<br />%empty_checkbox% %empty_label%');
    }

    /**
     * Renders the widget.
     *
     * @param  string $name        The element name
     * @param  string $value       The value displayed in this widget
     * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
     * @param  array  $errors      An array of errors for the field
     *
     * @return string An HTML tag string
     *
     * @see sfWidgetForm
     */
    public function render($name, $value = null, $attributes = array(), $errors = array()) {
        $values = array_merge(array('is_empty' => ''), is_array($value) ? $value : array());

        return strtr($this->getOption('filter_template'), array(
                    '%value_range%' => parent::render($name, $value, $attributes, $errors),
                    '%empty_checkbox%' => $this->getOption('with_empty') ? $this->renderTag('input', array('type' => 'checkbox', 'name' => $name . '[is_empty]', 'checked' => $values['is_empty'] ? 'checked' : '')) : '',
                    '%empty_label%' => $this->getOption('with_empty') ? $this->renderContentTag('label', $this->translate($this->getOption('empty_label')), array('for' => $this->generateId($name . '[is_empty]'))) : '',
                ));
    }
}
