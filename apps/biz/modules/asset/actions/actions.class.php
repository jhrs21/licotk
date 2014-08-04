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
        $user = $this->getUser();
        
        if($user->hasGroup('admin')){
            $this->assets = Doctrine::getTable('Asset')->retrieveAssets();
        }
        else {
            $this->assets = Doctrine::getTable('Asset')->retrieveAssets('place',$user->getAffiliateId());
        }
    }

    public function executeShowPlace(sfWebRequest $request)
    {
        $asset = $this->getRoute()->getObject();
        
        $this->forward404Unless($asset->isPlace());
        
        $user = $this->getUser();
        
        if(!$user->hasGroup('admin'))
        {
            $user = $user->getGuardUser();
            
            if($user->getAffiliateId() != $asset->getAffiliateId())
            {
                $this->getUser()->setFlash('notice', 'Usted no está autorizado para acceder a información del establecimiento indicado.');
 
                $this->redirect($this->generateUrl('asset_products'));
            }
        }
        
        $this->asset = $asset;
    }

    public function executeNewPlace(sfWebRequest $request)
    {
        $user = $this->getUser();
        
        $asset = new Asset();
            
        $asset->setAssetType('place');
        
        if(!$user->hasGroup('admin'))
        {
            $user = $user->getGuardUser();
            
            $asset->setAffiliateId($user->getAffiliateId());
        }
        
        $this->form = new AssetForm($asset);
        
        if ($request->isMethod('post')) 
        {
            $this->processForm($request, $this->form);
        }
    }

    public function executeEditPlace(sfWebRequest $request)
    {
        $asset = $this->getRoute()->getObject();
        
        $this->forward404Unless($asset->isBrand());
        
        $user = $this->getUser();
        
        if(!$user->hasGroup('admin'))
        {
            $user = $user->getGuardUser();
            
            if($user->getAffiliateId() != $asset->getAffiliateId())
            {
                $this->getUser()->setFlash('notice', 'Usted no está autorizado para modificar la información del establecimiento indicado.');
 
                $this->redirect($this->generateUrl('asset_products'));
            }
        }
        
        $this->form = new AssetForm($asset);
        
        if ($request->isMethod('put')) 
        {
            $this->processForm($request, $this->form);
        }
    }
    
    public function executeBrands(sfWebRequest $request) 
    {
        $user = $this->getUser();
        
        if($user->hasGroup('admin')){
            $this->assets = Doctrine::getTable('Asset')->retrieveAssets('brand');
        }
        else
        {
            $user = $user->getGuardUser();
            
            $this->assets = Doctrine::getTable('Asset')->retrieveAssets('brand', $user->getAffiliateId());
        }
    }
    
    public function executeShowBrand(sfWebRequest $request)
    {
        $asset = $this->getRoute()->getObject();
        
        $this->forward404Unless($asset->isBrand());
        
        $user = $this->getUser();
        
        if(!$user->hasGroup('admin'))
        {
            $user = $user->getGuardUser();
            
            if($user->getAffiliateId() != $asset->getAffiliateId())
            {
                $this->getUser()->setFlash('notice', 'Usted no está autorizado para acceder a información de la marca indicada.');
 
                $this->redirect($this->generateUrl('asset_products'));
            }
        }
        
        $this->asset = $asset;
    }
    
    public function executeNewBrand(sfWebRequest $request)
    {
        $user = $this->getUser();
        
        $asset = new Asset();
            
        $asset->setAssetType('brand');
        
        if(!$user->hasGroup('admin'))
        {
            $user = $user->getGuardUser();
            
            $asset->setAffiliateId($user->getAffiliateId());
        }
        
        $this->form = new AssetForm($asset);
        
        if ($request->isMethod('post')) 
        {
            $this->processForm($request, $this->form);
        }
    }
    
    public function executeEditBrand(sfWebRequest $request)
    {
        $asset = $this->getRoute()->getObject();
        
        $this->forward404Unless($asset->isBrand());
        
        $user = $this->getUser();
        
        if(!$user->hasGroup('admin'))
        {
            $user = $user->getGuardUser();
            
            if($user->getAffiliateId() != $asset->getAffiliateId())
            {
                $this->getUser()->setFlash('notice', 'Usted no está autorizado para modificar la información de la marca indicada.');
 
                $this->redirect($this->generateUrl('asset_products'));
            }
        }
        
        $this->form = new AssetForm($asset);
        
        if ($request->isMethod('put')) 
        {
            $this->processForm($request, $this->form);
        }
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