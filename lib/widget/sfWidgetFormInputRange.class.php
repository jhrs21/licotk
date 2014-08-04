<?php

class sfWidgetFormInputRange extends sfWidgetForm {

    protected function configure($options = array(), $attributes = array()) {
        $this->addRequiredOption('from_value');
        $this->addRequiredOption('to_value');

        $this->addOption('template', 'from %from_value% to %to_value%');
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
        $value = array_merge(array('from' => '', 'to' => ''), is_array($value) ? $value : array());

        return strtr($this->translate($this->getOption('template')), array(
                    '%from_value%' => $this->getOption('from_value')->render($name . '[from]', $value['from']),
                    '%to_value%' => $this->getOption('to_value')->render($name . '[to]', $value['to']),
                ));
    }

    /**
     * Gets the stylesheet paths associated with the widget.
     *
     * @return array An array of stylesheet paths
     */
    public function getStylesheets() {
        return array_unique(array_merge($this->getOption('from_value')->getStylesheets(), $this->getOption('to_value')->getStylesheets()));
    }

    /**
     * Gets the JavaScript paths associated with the widget.
     *
     * @return array An array of JavaScript paths
     */
    public function getJavaScripts() {
        return array_unique(array_merge($this->getOption('from_value')->getJavaScripts(), $this->getOption('to_value')->getJavaScripts()));
    }
}
