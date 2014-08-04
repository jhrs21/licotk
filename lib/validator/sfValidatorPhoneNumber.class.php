<?php

/**
 * sfValidatorPhoneNumber validates a phone number.
 *
 * @package    
 * @subpackage validator
 * @author     Jacobo Martínez <jacobo.martinez@equilibrio.net.ve>
 */
class sfValidatorPhoneNumber extends sfValidatorString
{
    /**
     * Configures the current validator.
     *
     * Available options:
     * 
     *  * phone_codes:  An array of phone area codes for the code select tag (optional - array('0414','0424','0416','0426','0412') by default)
     *
     * @param array $options    An array of options
     * @param array $messages   An array of error messages
     *
     * @see sfValidatorBase
     */
    protected function configure($options = array(), $messages = array())
    {
        $phone_codes = array('0414','0424','0416','0426','0412');
        $this->addOption('phone_codes', array_combine($phone_codes,$phone_codes));
        $this->addOption('pattern', $this->buildRegex($this->getOption('phone_codes')));
        
        parent::configure($options, $messages);
    }

    /**
     * @see sfValidatorBase
     */
    protected function doClean($value)
    {
        // convert array to date string
        if (is_array($value))
        {
            $value = $this->convertPhoneNumberArrayToString($value);
        }

        return $value;
    }
    
    /**
     * Using the phone codes creates a regular expression.
     *
     * @param  array $phone_codes  An array of phone area codes
     *
     * @return string
     */
    protected function buildRegex($phone_codes)
    {
        $phone_codes = array();

        $regex = '/^(%codes%)[0-9]{7}$/';

        $cant_codes = count($phone_codes);

        $i = 1;

        $codes = '';

        foreach ($phone_codes as $key => $code) {
            $codes = $codes.$code.($i < $cant_codes ? '|' : '');
            $i++;
        }
        
        return str_replace('%codes%', $codes, $regex);
    }

    /**
   * Converts an array representing a phone number to a string.
   *
   * The array can contains the following keys: code, number.
   *
   * @param  array $value  An array of phone number elements
   *
   * @return string
   */
    protected function convertPhoneNumberArrayToString($value)
    {
        // all elements must be empty or a number
        foreach (array('code', 'number') as $key)
        {
            if (isset($value[$key]) && !preg_match('/^[[:digit:]]+$/', $value[$key]) && !empty($value[$key]))
            {
                throw new sfValidatorError($this, 'invalid', array('value' => $value));
            }
        }

        // if one phone number value is empty, all others must be empty too
        $empties =
            (!isset($value['code']) || !$value['code'] ? 1 : 0) 
            + 
            (!isset($value['number']) || !$value['number'] ? 1 : 0);

        if ($empties == 1)
        {
            throw new sfValidatorError($this, 'invalid', array('value' => $value));
        }
        else if (2 == $empties)
        {
            return $this->getEmptyValue();
        }

        $clean = $value['code'].$value['number'];
        
//        if (!preg_match($this->buildRegex($this->getOption('phone_codes')), $clean))
//        {
//            throw new sfValidatorError($this, 'invalid', array('value' => $value));
//        }

        return $clean;
    }
}
