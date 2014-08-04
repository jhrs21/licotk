<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of masiveTagByAssetForm
 *
 * @author jacobomartinez
 */
class epMassiveTagByAssetForm extends sfForm {

    public function configure() {
        parent::configure();
        
        $this->setWidgets(array(
            'asset'     => new sfWidgetFormDoctrineChoice(array('model' => 'Asset', 'add_empty' => true, 'order_by' => array('name','asc'))),
            'tags'      => new sfWidgetFormInput(),
        ));

        $this->setValidators(array(
            'asset'     => new sfValidatorDoctrineChoice(array('required' => true, 'model' => 'Asset')),
            'tags'      => new sfValidatorInteger(array('required' => true, 'min' => 1)),
        ));

        $this->widgetSchema->setLabels(array(
            'asset' => 'Establecimiento',
            'tags'  => 'Cantidad de Tags'
        ));

        $this->widgetSchema->setNameFormat('mtfv[%s]');
        $this->widgetSchema->setFormFormatterName('epWeb');
    }
}

?>
