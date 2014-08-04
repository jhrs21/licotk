<?php

/**
 * location actions.
 *
 * @package    elperro
 * @subpackage location
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class locationActions extends sfActions {

    public function executePopulateSelect(sfWebRequest $request)
    {
        $this->forward404Unless($request->isXmlHttpRequest());
        
        $form = new EmbeddedLocationForm(null,
                    array(
                        $request->getParameter('widget') => $request->getParameter('value'),
                        'setAssetName' => true,
                    )
                );
        
        $this->select = $form[$request->getParameter('update').'_id'];
        
        $this->setLayout(false);
    }

    public function executeIndex(sfWebRequest $request)
    {
        $this->locations = $this->getRoute()->getObjects();
    }
}
