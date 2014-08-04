<?php

/**
 * Asset form.
 *
 * @package    elperro
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class AssetForm extends BaseAssetForm
{
    public function configure()
    {
        unset(
            $this['id'], $this['alpha_id'], $this['hash'], $this['asset_type'],
            $this['created_at'], $this['updated_at'], $this['promos_list'], 
            $this['contacts_list']
        );
        
        $user = sfContext::getInstance()->getUser();
        
        if($user->hasGroup('admin'))
        {
            $this->widgetSchema['affiliate_id'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => $this->getRelatedModelName('Affiliate'), 'add_empty' => false
                ));
            
            $this->widgetSchema->moveField('affiliate_id', sfWidgetFormSchema::FIRST);
        }
        else
        {
            $this->widgetSchema['affiliate_id'] = new sfWidgetFormInputHidden();
            
            $this->validatorSchema['affiliate_id'] = new sfValidatorChoice(array('choices' => array($this->getObject()->getAffiliateId())));
        }
        
        $table = Doctrine::getTable('Category');
        
        $categoryQuery = $table->addRootCategoriesQuery($table->addByTypeQuery($this->getOption('type', 'place')));
        
        $this->widgetSchema['category_id'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => $this->getRelatedModelName('Category'),
                    'query' => $categoryQuery,
                    'add_empty' => false
                ),
                array('id' => 'category-select',));
        
        $this->widgetSchema['categories_list'] = new sfWidgetFormDoctrineChoice(array('multiple' => true, 'expanded' => true, 'model' => 'Category'));        
        
        if($this->getOption('category', false)){
            $subCategoryQuery = $table->addSubCategoriesQuery($this->getOption('category'));
        }
        else if($this->getObject()->isNew()){
            $subCategoryQuery = $table->addSubCategoriesQuery($categoryQuery->fetchOne()->getId());
        }
        else{
           $subCategoryQuery = $table->addSubCategoriesQuery($this->getObject()->getCategoryId()); 
        }
        
        $this->widgetSchema['logo'] = new sfWidgetFormInputFileEditable(array(
                    'file_src' => '/uploads/' . $this->getObject()->getLogo(),
                    'edit_mode' => !$this->isNew(),
                    'is_image' => true,
                    'with_delete' => false,
                ));
        
        $this->validatorSchema['logo'] = new sfValidatorFile(array(
                    'required' => false,
                    'mime_types' => 'web_images',
                    'path' => sfConfig::get('sf_upload_dir'),
                ));
        
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
        
        if($this->object->isPlace()){
            $this->embedForm('location', new AssetLocationForm($this->getObject(),1));
        }
    }
    
    public function updateObjectEmbeddedForms($values, $forms = null) 
    {
        if (is_array($forms)) 
        {
            foreach ($forms as $key => $form) 
            {
                if ($form instanceof EmbeddedPromoPrizeForm) 
                {
                    $formValues = isset($values[$key]) ? $values[$key] : array();
                    
                    if (EmbeddedLocationForm::formValuesAreBlank($formValues))
                    {
                        if ($id = $form->getObject()->getId())
                        {
                            $this->object->unlink('Location', $id);
                            
                            $form->getObject()->delete();
                        }

                        unset($forms[$key]);
                    }
                }
            }
        }

        return parent::updateObjectEmbeddedForms($values, $forms);
    }

    public function saveEmbeddedForms($con = null, $forms = null) 
    {
        if (is_array($forms)) 
        {
            foreach ($forms as $key => $form) 
            {
                if ($form instanceof EmbeddedLocationForm) 
                {
                    if ($form->getObject()->isModified())
                    {
                        $form->getObject()->Asset = $this->object;
                    }
                    else
                    {
                        unset($forms[$key]);
                    }
                }
            }
        }

        return parent::saveEmbeddedForms($con, $forms);
    }
    
    public function bind(array $taintedValues = null, array $taintedFiles = null)
    {
        return parent::bind($taintedValues, $taintedFiles);
    }
}
