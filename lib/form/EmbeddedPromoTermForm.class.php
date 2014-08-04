<?php

/**
 * EmbeddedPromoConditionForm is a subclass of PromoConditionForm
 * It is used as an embedded form in PromoForm
 *
 * @author Jacobo Martínez
 */
class EmbeddedPromoTermForm extends PromoTermForm {

    public function configure() {
        parent::configure();
        
        if (!($this->getOption('routing') instanceof sfRouting)) {
            throw new InvalidArgumentException("You must pass a routing object as an option to this form!");
        } else {
            $routing = $this->getOption('routing');
        }

        if ($disabled = $this->getOption('disabled', false)) {
            $this->widgetSchema['term']->setAttribute('disabled', $disabled);
        }
        
        $deleteAttributes = array('value' => 'Eliminar', 'class' => 'delete-trigger', 'is_prize' => 0);
        
        if (!$this->getObject()->isNew()) {
            $deleteAttributes['url'] = $routing->generate('promo_delete_term', array('id' => $this->getObject()->getId()), true);
        }

        $this->widgetSchema['delete'] = new sfWidgetFormInput(array('type' => 'button'), $deleteAttributes);

        $this->widgetSchema->setLabels(array('term' => 'Condición', 'delete' => false));
        $this->widgetSchema->setFormFormatterName('epWeb');
        
        //$this->validatorSchema = new EmbeddedPromoTermValidatorSchema($this->validatorSchema);
    }

    public static function formValuesAreBlank($values) {
        if(is_null($values)){
            return true;
        }
        
        $fieldNames = array_diff(Doctrine::getTable('PromoTerm')->getFieldNames(), array('id', 'promo_id'));

        return parent::_formValuesAreBlank($fieldNames, $values);
    }
}