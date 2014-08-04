<?php

/**
 * EmbeddedPromoConditionForm is a subclass of PromoConditionForm
 * It is used as an embedded form in PromoForm
 *
 * @author Jacobo Martínez
 */
class EmbeddedPromoPrizeForm extends PromoPrizeForm {

    public function configure() {
        parent::configure();
        unset($this['promo_id'], $this['delivered']);
        
        if (!($this->getOption('routing') instanceof sfRouting)) {
            throw new InvalidArgumentException("You must pass a routing object as an option to this form!");
        } else {
            $routing = $this->getOption('routing');
        }

        $deleteAttributes = array('value' => 'Eliminar', 'class' => 'delete-trigger', 'is_prize' => 1);

        if (!$this->getObject()->isNew()) {
            $deleteAttributes['url'] = $routing->generate('promo_delete_prize', array('id' => $this->getObject()->getId()), true);
        }

        $this->widgetSchema['delete'] = new sfWidgetFormInput(array('type' => 'button'), $deleteAttributes);

        $this->widgetSchema->setLabels(array(
            'prize' => 'Premio',
            'threshold' => 'Cantidad de Tags para alcanzar el premio',
            'stock' => 'Inventario',
            'delete' => false,
        ));
        $this->widgetSchema->setFormFormatterName('epWeb');
        
        //$this->validatorSchema = new EmbeddedPromoPrizeValidatorSchema($this->validatorSchema);
    }

    public static function formValuesAreBlank($values) {
        if(is_null($values)){
            return true;
        }
        
        $ignoredFields = array('id', 'promo_id', 'thumb');

        $fieldNames = array_diff(Doctrine::getTable('PromoPrize')->getFieldNames(), $ignoredFields);

        return parent::_formValuesAreBlank($fieldNames, $values);
    }
}