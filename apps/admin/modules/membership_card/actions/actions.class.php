<?php

/**
 * membership_card actions.
 *
 * @package    elperro
 * @subpackage membership_card
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class membership_cardActions extends sfActions {
    
    public function executeTest(sfWebRequest $request) {        
        $str = (double)microtime()*1000003;
        ////Util::make_seed();
        
        print_r('<br><br>Result = '.$str);
        
        $str = Util::make_seed();
        print_r('<br><br>Result = '.$str);
        
        die('<br><br>Hasta aqui.');
    }

    public function executeIndex(sfWebRequest $request) {
        //$this->mcards = $this->getRoute()->getObjects();
        
        $query = Doctrine_Query::create()->from('MembershipCard mc');
        
        $this->pager = new sfDoctrinePager('MembershipCard',10);
        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
        
        $this->setLayout('layout_blueprint');
    }
    
    public function executeGenerate(sfWebRequest $request) 
    {
        set_time_limit(0);
        
        $this->quantity = $request->getParameter('quantity',0);
        
        if($request->isMethod('post')){            
            for($i = 0; $i < $this->quantity; $i++){
                $mcard = new MembershipCard();
                $mcard->setStatus('unassigned');
                $mcard->save();
            }
        }
        
        if($request->isXmlHttpRequest()){
            return $this->renderPartial('generateConfirmation', array('quantity' => $this->quantity));
        }
        
        $this->total = Doctrine_Query::create()->from('MembershipCard mc')->count();
        
        $this->setLayout('layout_blueprint');
    }

    public function executeShow(sfWebRequest $request) {
        $this->mcard = $this->getRoute()->getObject();
        $this->setLayout('layout_blueprint');
    }

    public function executeNew(sfWebRequest $request) {
        $this->form = new MembershipCardForm();
        $this->setLayout('layout_blueprint');
    }

    public function executeCreate(sfWebRequest $request) {
        $this->form = new MembershipCardForm();
        
        $this->processForm($request, $this->form);

        $this->setTemplate('new');
        
        $this->setLayout('layout_blueprint');
    }

    public function executeEdit(sfWebRequest $request) {
        $this->form = new MembershipCardForm($this->getRoute()->getObject());
        
        $this->setLayout('layout_blueprint');
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->form = new MembershipCardForm($this->getRoute()->getObject());

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
        
        $this->setLayout('layout_blueprint');
    }

    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();

        $this->getRoute()->getObject()->delete();

        $this->redirect('membership_card/index');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $mcard = $form->save();

            $this->redirect('membership_card/edit?id=' . $mcard->getId());
        }
    }

}
