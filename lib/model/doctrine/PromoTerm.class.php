<?php

/**
 * PromoTerm
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    elperro
 * @subpackage model
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class PromoTerm extends BasePromoTerm
{
    public function save(Doctrine_Connection $conn = null) 
    {
        $needles = array(
                'starts_at' => 'starts_at',
                'ends_at' => 'ends_at',
                'expires_at' => 'expires_at',
                'max_uses' => 'max_uses'
            );
        
        if(strpos($this->term, '%') !== false)
        {            
            foreach ($needles as $key => $needle) {
                if(strcmp($key, 'max_uses') == 0)
                {
                    $max_uses = $this->getPromo()->get('max_uses');
                    
                    if($max_uses == 0)
                    {
                        $max_uses = 'ilimitado';
                    }
                    
                    $this->term = str_replace('%max_uses%', $max_uses, $this->term);
                } 
                else 
                {
                    $this->term = str_replace('%'.$needle.'%', $this->getPromo()->getDateTimeObject($key)->format('d/m/Y'), $this->term);                    
                }
            }
        }
        
        return parent::save($conn);
    }
}