<?php

/**
 * Project form base class.
 *
 * @package    elperro
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormBaseTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class BaseFormDoctrine extends sfFormDoctrine {

    public function setup() {}

    /**
     * Finds out if at least one form value is not blank
     * 
     * @param array $fieldNames
     * @param array $values
     * @return boolean 
     */
    public static function _formValuesAreBlank(array $fieldNames, array $values) {
        foreach ($fieldNames as $fieldName) {
            if (isset($values[$fieldName]) && !self::formValueIsBlank($values[$fieldName])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Finds out if all form values are not blank
     * 
     * @param array $fieldNames
     * @param array $values
     * @return boolean 
     */
    public static function _formValuesAreNotBlank(array $fieldNames, array $values) {
        foreach ($fieldNames as $fieldName) {
            if (isset($values[$fieldName]) && self::formValueIsBlank($values[$fieldName])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Finds out if <i>value</i> is not an empty value
     * 
     * @param mixed $value An array or a primitive value
     * @return boolean 
     */
    public static function formValueIsBlank($value) {
        if (is_array($value)) {
            foreach ($value as $subValue) {
                if (!self::formValueIsBlank($subValue)) {
                    return false;
                }
            }

            return true;
        }

        return $value ? false : true;
    }
}