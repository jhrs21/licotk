<?php

/*
 * sfWidgetFormDateForMobile represents a date widget adapted to render properly
 * in mobile devices.
 *
 * @author     Jacobo Martínez <jacobo.martinez@equilibrio.net.ve>
 * @version    1.0
 */

class sfWidgetFormPhoneNumber extends sfWidgetForm
{
    /**
     * Configures the current widget.
     *
     * Available options:
     *
     *  * format:       The phone number format string (%code%-%number% by default)
     *  * phone_codes:  An array of phone area codes for the code select tag (optional - array('0414','0424','0416','0426','0412') by default)
     *                  Be careful that the keys must be the phone codes, and the values what will be displayed to the user
     *  * can_be_empty: Whether the widget accept an empty value (true by default)
     *  * empty_values: An array of values to use for the empty value (empty string for year, month, and day by default)
     *
     * @param array $options     An array of options
     * @param array $attributes  An array of default HTML attributes
     *
     * @see sfWidgetForm
     *
     */
    protected function configure($options = array(), $attributes = array())
    {
        $this->addOption('format', '%code%-%number%');
        $phone_codes = array('0414','0424','0416','0426','0412');
        $this->addOption('phone_codes', array_combine($phone_codes,$phone_codes));
        $this->addOption('can_be_empty', true);
        $this->addOption('empty_values', array('code' => '', 'number' => ''));
    }

    /**
     * @param  string $name        The element name
     * @param  string $value       The date displayed in this widget
     * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
     * @param  array  $errors      An array of errors for the field
     *
     * @return string An HTML tag string
     *
     * @see sfWidgetForm
     */
    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        // convert value to an array
        $default = array('code' => null, 'number' => null);
        if (is_array($value))
        {
            $value = array_merge($default, $value);
        }
        else
        {
            if (is_null($value) || (strlen($value) == 0))
            {
                $value = $default;
            }
            else
            {
                $value = array('code' => substr($value, 0, 4), 'number' => substr($value, 4, 7));
            }
        }

        $phone_number = array();
        
        $emptyValues = $this->getOption('empty_values');

        $phone_number['%code%'] = $this->renderCodeWidget(
                $name.'[code]',
                $value['code'],
                array(
                    'choices' => $this->getOption('can_be_empty') ?
                            array('' => $emptyValues['code']) + $this->getOption('phone_codes')
                                :
                            $this->getOption('phone_codes'),
                    'id_format' => $this->getOption('id_format')
                ),
                array_merge($this->attributes, $attributes)
            );
        
        $phone_number['%number%'] = $this->renderNumberWidget(
                $name.'[number]',
                $value['number'],
                array('id_format' => $this->getOption('id_format')),
                array_merge($this->attributes, $attributes)
            );

        return strtr($this->getOption('format'), $phone_number);
    }

    /**
     * @param string $name
     * @param string $value
     * @param array $options
     * @param array $attributes
     * @return string rendered widget
     */
    protected function renderCodeWidget($name, $value, $options, $attributes)
    {
        $widget = new sfWidgetFormSelect($options, $attributes);
        
        return $widget->render($name, $value);
    }

    /**
     * @param string $name
     * @param string $value
     * @param array $options
     * @param array $attributes
     * @return string rendered widget
     */
    protected function renderNumberWidget($name, $value, $options, $attributes)
    {
        $attributes['maxlength'] = 7;

        $widget = new sfWidgetFormInputText($options, $attributes);
        
        return $widget->render($name, $value);
    }
}