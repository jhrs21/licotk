<?php

/**
 * SurveyItemOption form.
 *
 * @package    elperro
 * @subpackage form
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SurveyItemOptionForm extends BaseSurveyItemOptionForm {

    public function configure() {
        if (!($this->getOption('routing') instanceof sfRouting)) {
            throw new InvalidArgumentException("You must pass a routing object as an option to this form!");
        } else {
            $routing = $this->getOption('routing');
        }
        
        unset($this['alpha_id'],$this['survey_item_id'],$this['created_at'],$this['updated_at']);
        
        $deleteAttributes = array('value' => 'Eliminar Opción', 'class' => 'delete-trigger');

        if (!$this->getObject()->isNew()) {
            $deleteAttributes['delete_url'] = $routing->generate('survey_delete_item_option', array('id' => $this->getObject()->getId()), true);
        }

        $this->widgetSchema['delete'] = new sfWidgetFormInput(array('type' => 'button'), $deleteAttributes);
        $this->widgetSchema['delete']->setLabel(false);
    }

}
