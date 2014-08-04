<?php

/**
 * EmbeddedPromoConditionForm is a subclass of PromoConditionForm
 * It is used as an embedded form in PromoForm
 *
 * @author Jacobo Martínez
 */

class EmbeddedLocationForm extends LocationForm
{
    public function configure()
    {
        parent::configure();
        
        unset(
            $this['affiliate_id'], $this['asset_id'], $this['name']
        );
        
        $this->widgetSchema['country_id']->setAttribute('id','country-select');
        
        $this->widgetSchema['state_id']->setAttribute('id','state-select');
        
        $this->widgetSchema['municipality_id']->setAttribute('id','municipality-select');
        
        $this->widgetSchema['city_id']->setAttribute('id','city-select');
        
        if($this->getOption('setAssetName', false))
        {
            $this->widgetSchema->setNameFormat('asset[location][0][%s]');
        }
    }
    
    public static function formValuesAreBlank(array $values)
    {
        $ignoredFields = array('id', 'affiliate_id', 'asset_id', name, 'created_at', 'updated_at');
        
        $fieldNames = array_diff(Doctrine::getTable('Location')->getFieldNames(), $ignoredFields);

        return parent::_formValuesAreBlank($fieldNames, $values);
    }
}

?>
