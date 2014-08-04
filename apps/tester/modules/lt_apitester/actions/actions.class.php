<?php

/**
 * apitester actions.
 *
 * @package    ep_apitester
 * @subpackage apitester
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class lt_apitesterActions extends sfActions
{
    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request)
    {
        $this->redirect('apitester_login');
    }
    
    public function executeLogin(sfWebRequest $request)
    {
        $this->form = new testLoginForm();
        
        if($request->isMethod('post'))
        {
            $this->form->bind($request->getParameter($this->form->getName()));
            if($this->form->isValid())
            {
                $this->values = $this->form->getValues();
                
                $ep = new epApi();
                
                $this->result = $ep->ltLogin($this->values);
                
                return 'TestResult';
            }
        }
    }

}
