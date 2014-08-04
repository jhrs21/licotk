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
        $this->affiliates = Doctrine::getTable('Affiliate')->findAll();
        
        $this->setLayout('layout_blueprint');
    }

    public function executeShow(sfWebRequest $request) 
    {
        $this->affiliate = $this->getRoute()->getObject();
        
        $this->setLayout('layout_blueprint');
    }

    public function executeNew(sfWebRequest $request) 
    {
        $this->form = new AffiliateForm();
        
        if ($request->isMethod('post'))
        {
            $this->processForm($request, $this->form);
        }
        
        $this->setLayout('layout_blueprint');
    }

    public function executeEdit(sfWebRequest $request) 
    {
        $affiliate = $this->getRoute()->getObject();
        
        $this->form = new AffiliateForm($affiliate);
        
        if ($request->isMethod('put'))
        {
            $this->processForm($request, $this->form);
        }
        
        $this->setLayout('layout_blueprint');
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
