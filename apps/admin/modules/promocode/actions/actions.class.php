<?php

/**
 * promocode actions.
 *
 * @package    elperro
 * @subpackage promocode
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class promocodeActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->promocodes = $this->getRoute()->getObjects();
    $this->setLayout('layout_blueprint');
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->promocode = $this->getRoute()->getObject();
    
    $this->setLayout('layout_blueprint');
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new PromoCodeForm();
    
    $this->setLayout('layout_blueprint');
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->form = new PromoCodeForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
    
    $this->setLayout('layout_blueprint');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->form = new PromoCodeForm($this->getRoute()->getObject());
    
    $this->setLayout('layout_blueprint');
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->form = new PromoCodeForm($this->getRoute()->getObject());

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
    
    $this->setLayout('layout_blueprint');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->getRoute()->getObject()->delete();

    $this->redirect('promocode/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $promocode = $form->save();

      $this->redirect('promocode',$promocode);
    }
  }
}
