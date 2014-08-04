<?php

/**
 * asset actions.
 *
 * @package    elperro
 * @subpackage asset
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class assetActions extends sfActions {

    public function executePlaces(sfWebRequest $request) 
    {
        $this->assets = Doctrine::getTable('Asset')->retrieveAssets();
        
        $this->setLayout('layout_blueprint');
    }

    public function executeShowPlace(sfWebRequest $request)
    {
        $this->asset = $this->getRoute()->getObject();
        
        $this->forward404Unless($this->asset->isPlace());
        
        $this->setLayout('layout_blueprint');
    }

    public function executeNewPlace(sfWebRequest $request)
    {
        $asset = new Asset();
            
        $asset->setAssetType('place');
        
        $this->form = new AssetForm($asset);
        
        if ($request->isMethod('post')) 
        {
            $this->processForm($request, $this->form);
        }
        
        $this->setLayout('layout_blueprint');
    }

    public function executeEditPlace(sfWebRequest $request)
    {
        $asset = $this->getRoute()->getObject();
        
        $this->forward404Unless($asset->isPlace());
        
        $this->form = new AssetForm($asset);
        
        if ($request->isMethod('put')) 
        {
            $this->processForm($request, $this->form);
        }
        
        $this->setLayout('layout_blueprint');
    }
    
    public function executeBrands(sfWebRequest $request) 
    {
        $this->assets = Doctrine::getTable('Asset')->retrieveAssets('brand');
        
        $this->setLayout('layout_blueprint');
    }
    
    public function executeShowBrand(sfWebRequest $request)
    {
        $this->asset = $this->getRoute()->getObject();
        
        $this->forward404Unless($this->asset->isBrand());
        
        $this->setLayout('layout_blueprint');
    }
    
    public function executeNewBrand(sfWebRequest $request)
    {
        $asset = new Asset();
            
        $asset->setAssetType('brand');
        
        $this->form = new AssetForm($asset, array('type' => 'brand'));
        
        if ($request->isMethod('post')) 
        {
            $this->processForm($request, $this->form);
        }
        
        $this->setLayout('layout_blueprint');
    }
    
    public function executeEditBrand(sfWebRequest $request)
    {
        $asset = $this->getRoute()->getObject();
        
        $this->forward404Unless($asset->isBrand());
        
        $this->form = new AssetForm($asset, array('type' => 'brand'));
        
        if ($request->isMethod('put')) 
        {
            $this->processForm($request, $this->form);
        }
        
        $this->setLayout('layout_blueprint');
    }
    
    public function executePopulateSubCategorySelect(sfWebRequest $request) 
    {
        $this->forward404Unless($request->isXmlHttpRequest());

        $category = $request->getParameter('category', 0);

        $form = new AssetForm(null, array('category' => $category));

        $this->select = $form['sub_category_id'];

        $this->setLayout(false);
    }

    protected function processForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        
        if ($form->isValid())
        {
            $asset = $form->save();

            $this->redirect('asset_show_'.$asset->getAssetType(), $asset);
        }
    }
}
