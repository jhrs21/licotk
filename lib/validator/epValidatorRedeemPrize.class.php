<?php
/**
 * Description of epValidatorRedeemPrize
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class epValidatorRedeemPrize extends sfValidatorBase {
    public function configure($options = array(), $messages = array()) {
        $this->addOption('throw_global_error', true);
        
        $this->addMessage('prize_invalid', 'Identificador de premio inválido');
        $this->addMessage('card_invalid', 'Identificador de tarjeta inválido');
    }
    
    protected function doClean($values) {
        $user = Doctrine::getTable('sfGuardUser')->findOneByAlphaId($values['user']);
        $promo = Doctrine::getTable('Promo')->findOneByAlphaId($values['promo']);
        $prize = Doctrine::getTable('PromoPrize')->findOneByAlphaIdAndPromoId($values['prize'],$promo->getId());
        
        if (!$prize) {
            if ($this->getOption('throw_global_error')) {
                throw new sfValidatorError($this, 'prize_invalid');
            } else {
                throw new sfValidatorErrorSchema($this, array('prize' => new sfValidatorError($this, 'prize_invalid')));
            }
        }
        
        $card = Doctrine::getTable('Card')->findOneByAlphaIdAndPromoIdAndUserId($values['card'],$promo->getId(),$user->getId());
        
        if (!$card) {
            if ($this->getOption('throw_global_error')) {
                throw new sfValidatorError($this, 'card_invalid');
            } else {
                throw new sfValidatorErrorSchema($this, array('prize' => new sfValidatorError($this, 'card_invalid')));
            }
        }
        
        return array_merge($values,array('user' => $user, 'promo' => $promo, 'prize' => $prize, 'card' => $card));
    }
}