<?php

/**
 * affiliate actions.
 *
 * @package    elperro
 * @subpackage affiliate
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class affiliateActions extends sfActions
{
    public function executeIndex(sfWebRequest $request) 
    {
        $user = $this->getUser();
        
        if($user->hasGroup('admin')){
            $this->affiliates = Doctrine::getTable('Affiliate')->findAll();
        }
        else
        {
            $user = $user->getGuardUser();
            
            $this->redirect('affiliate_show', $user->getAffiliate());
        }
    }

    public function executeShow(sfWebRequest $request) 
    {
        $user = $this->getUser();
        
        if($user->hasGroup('admin')){
            $this->affiliate = $this->getRoute()->getObject();
        }
        else
        {
            $user = $user->getGuardUser();
            
            $this->affiliate = $user->getAffiliate();
        }
    }

    public function executeNew(sfWebRequest $request) 
    {
        $user = $this->getUser();
        
        if($user->hasGroup('admin')){
            $this->form = new AffiliateForm();
        
            if ($request->isMethod('post'))
            {
                $this->processForm($request, $this->form);
            }
        }
        else
        {
            $user = $user->getGuardUser();
            
            $this->redirect('affiliate_show', $user->getAffiliate());
        }
    }

    public function executeEdit(sfWebRequest $request) 
    {
        $user = $this->getUser();
        
        if($user->hasGroup('admin')){
            $affiliate = $this->getRoute()->getObject();
        }
        else
        {
            $user = $user->getGuardUser();
            
            $affiliate = $user->getAffiliate();
        }
        
        $this->form = new AffiliateForm($affiliate);
        
        if ($request->isMethod('put'))
        {
            $this->processForm($request, $this->form);
        }
    }
    
    public function executeChangePass(sfWebRequest $request){
        $user = $this->getUser();
        
        if($user->hasGroup('admin')){
            $affiliate = $this->getRoute()->getObject();
        }
        else
        {
            $user = $user->getGuardUser();
            
//            $affiliate = $user->getAffiliate();
        }
        
        $this->form = new epChangePassForm();
        
        if ($request->isMethod('post'))
        {
            $this->form->bind($request->getParameter('changePass'));
        
                if ($this->form->isValid()) 
                {
                    $values = $this->form->getValues();
                    $user->setPassword($values['password']);
                    $user->save();
                    $this->redirect('homepage');
                }
        }
    }

    protected function processForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        
        if ($form->isValid()) 
        {
            $affiliate = $form->save();

            $this->redirect('affiliate_show', $affiliate);
        }
    }
}
