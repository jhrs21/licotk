<?php

/**
 * PromoRedeemerConfig
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    elperro
 * @subpackage model
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class PromoRedeemerConfig extends BasePromoRedeemerConfig {
    public function getCredentials() {
        $credentials = '';
        
        if ($this->getApiKey()) {
            $credentials .= '&apikey='.$this->getApiKey();
        }
        
        if ($this->getApiToken()) {
            $credentials .= '&apitoken='.$this->getApiToken();
        }
        
        return $credentials;
    }
}