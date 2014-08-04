<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of components
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class contactComponents extends sfComponents {
    
    public function executeQASForm() {
        $this->form = new epQASForm();
    }
    
}

?>
