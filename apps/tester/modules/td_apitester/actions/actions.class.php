<?php

/**
 * apitester actions.
 *
 * @package    ep_apitester
 * @subpackage apitester
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class td_apitesterActions extends sfActions
{
    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */    
    public function executeSendPrize(sfWebRequest $request)
    {
        $this->form = new testSendPrizeForm();
        
        if($request->isMethod('post'))
        {
            $this->form->bind($request->getParameter($this->form->getName()));
            if($this->form->isValid())
            {
                $this->values = $this->form->getValues();
                
                $api = new epTDApi();
                
                $this->result = $api->tdSendPrize($this->values);
                
                return 'TestResult';
            }
        }
    }

}
