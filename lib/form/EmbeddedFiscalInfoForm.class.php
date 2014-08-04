<?php

/**
 * EmbeddedPromoConditionForm is a subclass of PromoConditionForm
 * It is used as an embedded form in PromoForm
 *
 * @author Jacobo Martínez
 */

class EmbeddedFiscalInfoForm extends FiscalInfoForm
{
    public function configure()
    {
        parent::configure();

        //$this->validatorSchema = new EmbeddedPromoPrizeValidatorSchema($this->validatorSchema);
    }
    
    public static function formValuesAreBlank(array $values)
    {
        $ignoredFields = array();
        
        $fieldNames = array_diff(Doctrine::getTable('Location')->getFieldNames(), $ignoredFields);

        return parent::_formValuesAreBlank($fieldNames, $values);
    }
    
    public static function formValuesAreNotBlank(array $values)
    {
        $ignoredFields = array();
        
        $fieldNames = array_diff(Doctrine::getTable('Location')->getFieldNames(), $ignoredFields);

        return parent::_formValuesAreNotBlank($fieldNames, $values);
    }
}

?>
