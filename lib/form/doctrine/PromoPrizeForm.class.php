<?php

/**
 * PromoCondition form.
 *
 * @package    elperro
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PromoPrizeForm extends BasePromoPrizeForm {

    public function configure() {
        unset($this['alpha_id'], $this['hash'], $this['created_at'], $this['updated_at']);
        
        $this->widgetSchema['threshold'] = new sfWidgetFormInput(array('type' => 'number'), array('maxlength' => 4));
        
        $this->validatorSchema['threshold'] = new sfValidatorInteger(array('min' => 1, 'max' => 9999), array('min' => 'El valor mínimo admitido es 1', 'max' => 'El valor máximo admitido es 9999'));

        $this->widgetSchema['thumb'] = new sfWidgetFormInputFileEditable(array(
                    'file_src' => '/uploads/' . $this->getObject()->getThumb(),
                    'edit_mode' => !$this->isNew(),
                    'is_image' => true,
                    'with_delete' => false,
                ));

        $this->validatorSchema['thumb'] = new sfValidatorFile(array(
                    'required' => false,
                    'mime_types' => 'web_images',
                    'path' => sfConfig::get('sf_upload_dir'),
                ));
        
        $attributes = array('maxlength' => 3, 'min' => 0);
        
        $this->widgetSchema['stock'] = new sfWidgetFormInput(array('type' => 'number'), $attributes);
        
        $this->validatorSchema['stock'] = new sfValidatorInteger(array('min' => 0), array('min' => 'El valor mínimo admitido es 0, no se admiten valores negativos'));
        
        $this->widgetSchema->setHelp('stock', 'Cero (0) es equivalente a ilimitado.');

        $this->widgetSchema->moveField('thumb', sfWidgetFormSchema::FIRST);
//        $this->widgetSchema->moveField('prize', sfWidgetFormSchema::AFTER, 'thumb');
    }

}
