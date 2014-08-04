<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of epCouponObjectRedeemForm
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class epCouponObjectRedeemForm extends CouponForm
{
    public function configure() {
        parent::configure();
        
        $this->useFields(array('password'));
    }
}

?>
