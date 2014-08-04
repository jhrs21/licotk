<?php

/**
 * Coupon
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    elperro
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Coupon extends BaseCoupon
{
    public function checkPassword($password) 
    {
        return strcasecmp($this->getPassword(), $password) == 0;
    }
    
    public function hasStatus($status) 
    {
        return strcmp($this->getStatus(), $status) == 0;
    }
    
    public function isExpired() 
    {
        $expiration_date = $this->getPromo()->getDateTimeObject('expires_at');
        
        $expiration_date = mktime(0, 0, 0, $expiration_date->format('m'), $expiration_date->format('d')+1, $expiration_date->format('Y'));
        
        return $expiration_date < time();
    }
    
    public function asArray($host = null) 
    {
        return $coupon = array(
            'id' => $this->getAlphaId(),
            'promo' => $this->getPromo()->getAlphaId(),
            'card' => $this->getCard()->getAlphaId(),
            'condition' => $this->getPrize()->getAlphaId(),
            'message' => 'Valido por: '.$this->getPrize()->getPrize(),
            'status' => $this->getStatus(),
            'serial' => $this->getSerial(),
            'password' => $this->getPassword(),
            'creation_date' => $this->getDateTimeObject('created_at')->format('d/m/Y'),
            'expiration_date' => $this->getPromo()->getDateTimeObject('expires_at')->format('d/m/Y'),
        );
    }
    
    public function save(Doctrine_Connection $conn = null)
    {
        if (!$this->getSerial()) {            
            $this->setSerial(Util::GenSecret(5, Util::CHAR_MIX));
            
            $duplicated = true;
            
            $table = Doctrine::getTable('Coupon');
            
            while ($duplicated) {
                if (!$table->findOneBy('serial', $this->getSerial())) {
                    $duplicated = false;
                }
                else {
                    $this->setSerial(Util::GenSecret(5, Util::CHAR_MIX));
                }
            }
        }
        
        if (!$this->getPassword()) {
            $this->setPassword(Util::GenSecret(5, Util::CHAR_MIX));
        }
        
        if (!$this->getHash()) {
            $this->setHash(hash('sha256',time().$this->getSerial().rand(11111, 99999)));
        }
        
        if (!$this->getAlphaId()) {
            $this->setAlphaId(Util::gen_uuid($this->getHash()));
        }
        
        return parent::save($conn);
    }
}