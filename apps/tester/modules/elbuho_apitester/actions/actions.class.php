<?php

/**
 * elbuho_apitester actions.
 *
 * @package    elperro
 * @subpackage elbuho_apitester
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class elbuho_apitesterActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $buho = new epBuhoApi();
        
        $buhoValues = array('user' => 'jhrs21@yahoo.com', 'gender' => 'male');
        $result = $buho->buhoUpdateUser($buhoValues);
        var_dump($result);
    }
}
