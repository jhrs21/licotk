<?php

/**
 * SurveyItem form.
 *
 * @package    elperro
 * @subpackage form
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SurveyItemForm extends BaseSurveyItemForm {

    public function configure() {
        
        if (!($this->getOption('routing') instanceof sfRouting)) {
            throw new InvalidArgumentException("You must pass a routing object as an option to this form!");
        } else {
            $routing = $this->getOption('routing');
        }
        
        unset($this['survey_id'], $this['alpha_id'], $this['created_at'], $this['updated_at']);

        $this->widgetSchema['item_type'] = new sfWidgetFormChoice(
                array('choices' => array('text' => 'Texto', 'simple_selection' => 'Selección Simple', 'multiple_selection' => 'Seleccción Multiple', 'date' => 'Fecha')),
                array('class' => 'item_type_widget')
            );

        $deleteAttributes = array('value' => 'Eliminar Item', 'class' => 'delete-trigger');
        $addOptionAttributes = array('value' => 'Agregar Opción', 'class' => 'add-option', 'add_option_url' => $routing->generate('survey_add_item_option', array('id' => $this->getObject()->getId()), true));        

        if (!$this->getObject()->isNew()) {
            $deleteAttributes['delete_url'] = $routing->generate('survey_delete_item', array('id' => $this->getObject()->getId()), true);
            $addOptionAttributes['add_option_url'] .= '?id='.$this->getObject()->getId();
        }

        $this->widgetSchema['delete'] = new sfWidgetFormInput(array('type' => 'button'), $deleteAttributes);
        $this->widgetSchema['delete']->setLabel(false);
        $this->widgetSchema['add_option'] = new sfWidgetFormInput(array('type' => 'button'), $addOptionAttributes);
        $this->widgetSchema['add_option']->setLabel(false);
        
        //Embedding at least a Option form if the Item type requires it
        if ($this->getObject()->usesOptions()) {
            $options = $this->getObject()->getOptions();
            if (!$options->count()) {
                $option = new SurveyItemOption();
                $option->setItem($this->getObject());
                $options = array($option);
            }
            //An empty form will act as a container for all the Options
            $options_forms = new SfForm();
            $count = 0;
            foreach ($options as $option) {
                $option_form = new SurveyItemOptionForm($option, array('routing' => $routing));
                //Embedding each form in the container
                $options_forms->embedForm($count, $option_form);
                $count++;
            }
            
            //Embedding the container for all the Options in the Item form
            $this->embedForm('options', $options_forms);
        }
    }
    
    public function addOption($num) {
        $option = new SurveyItemOption();
        
        $option->setItem($this->getObject());
        
        $option_form = new SurveyItemOptionForm($option, array('routing' => $this->getOption('routing')));

        if (!isset($this->embeddedForms['options'])) {
            $this->embedForm('options', new sfForm());
        }
        //Embedding the new Option in the container
        $this->embeddedForms['options']->embedForm($num, $option_form);
        //Re-embedding the container
        $this->embedForm('options', $this->embeddedForms['options']);
    }
}
