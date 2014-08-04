<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of epAdminPromoCodeForm
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class epAdminPromoCodeForm extends PromoCodeForm 
{
    public function configure() {
        parent::configure();
        
        if($this->getOption('requires_validation', false)){
            
        }
    }
}

?>
