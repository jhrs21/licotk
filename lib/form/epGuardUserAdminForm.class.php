<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of epGuardUserAdminForm
 *
 * @author jacobo
 */
class epGuardUserAdminForm extends sfGuardUserAdminForm
{
    public function configure()
    {
        parent::configure();
        unset($this['alpha_id'], $this['hash']);
    }
}

?>
