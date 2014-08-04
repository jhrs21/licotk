<?php

/**
 * Affiliate form.
 *
 * @package    elperro
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class AffiliateForm extends BaseAffiliateForm
{
    public function configure()
    {
        $this->useFields(array('name', 'description', 'thumb', 'logo', 'category_id', 'categories_list'));
        
        $this->widgetSchema['logo'] = new sfWidgetFormInputFileEditable(array(
                    'file_src' => '/uploads/' . $this->getObject()->getLogo(),
                    'edit_mode' => !$this->isNew(),
                    'is_image' => true,
                    'with_delete' => false,
                ));
        
        $this->widgetSchema['thumb'] = new sfWidgetFormInputFileEditable(array(
                    'file_src' => '/uploads/' . $this->getObject()->getThumb(),
                    'edit_mode' => !$this->isNew(),
                    'is_image' => true,
                    'with_delete' => false,
                ));
        
        $this->widgetSchema['categories_list'] = new sfWidgetFormDoctrineChoice(array('multiple' => true, 'expanded' => true, 'model' => 'Category'));
        
        $this->validatorSchema['logo'] = new sfValidatorFile(array(
                    'required' => false,
                    'mime_types' => 'web_images',
                    'path' => sfConfig::get('sf_upload_dir'),
                ));
        
        $this->validatorSchema['thumb'] = new sfValidatorFile(array(
                    'required' => false,
                    'mime_types' => 'web_images',
                    'path' => sfConfig::get('sf_upload_dir'),
                ));
    }
}
