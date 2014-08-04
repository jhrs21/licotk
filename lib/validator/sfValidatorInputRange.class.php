<?php

class sfValidatorInputRange extends sfValidatorBase
{

    /**
     * Configures the current validator.
     * 
     * Available options:
     *
     *  * from_value:   The from value validator (required)
     *  * to_value:     The to value validator (required)
     *  * from_field:  The name of the "from" value field (optional, default: from)
     *  * to_field:    The name of the "to" value field (optional, default: to)
     *
     * @param array $options    An array of options
     * @param array $messages   An array of error messages
     *
     * @see sfValidatorBase
     */
    protected function configure($options = array(), $messages = array()) {
        $this->setMessage('invalid', 'The begin value must be before the end value.');

        $this->addRequiredOption('from_value');
        $this->addRequiredOption('to_value');
        $this->addOption('from_field', 'from');
        $this->addOption('to_field', 'to');
    }

    /**
     * @see sfValidatorBase
     */
    protected function doClean($value) {
        $fromField = $this->getOption('from_field');
        $toField = $this->getOption('to_field');

        $value[$fromField] = $this->getOption('from_value')->clean(isset($value[$fromField]) ? array('text' => $value[$fromField]) : null);
        $value[$toField] = $this->getOption('to_value')->clean(isset($value[$toField]) ? array('text' => $value[$toField]) : null);

        if ($value[$fromField]['text'] && $value[$toField]['text']) {
            $v = new sfValidatorSchemaCompare($fromField, sfValidatorSchemaCompare::LESS_THAN_EQUAL, $toField, array('throw_global_error' => true), array('invalid' => ('invalid')));
            $v->clean($value);
        }

        $value[$fromField] = $value[$fromField]['text'];
        $value[$toField] = $value[$toField]['text'];

        return $value;
    }

}
