<?php

/**
 * Description of epValidatorSearchUserPrizes
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class epValidatorSearchUserPrizes extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addOption('throw_global_error', true);

        $this->setMessage('invalid', 'Correo electrónico o tarjeta LealTag inválida');
        $this->addMessage('email', 'El correo electrónico indicado no se encuentra registrado');
        $this->addMessage('mcard_invalid', 'Tarjeta LealTag inválida');
        $this->addMessage('promo_expired', 'La promoción ha expirado');
        $this->addMessage('promo_redeem_period', 'El periodo de canje no ha iniciado');
    }

    protected function doClean($values) {
        $user = $this->validateUser($values['user']);
        $promo = $this->getPromo($values['promo']);

        return array_merge($values, array('user' => $user, 'promo' => $promo));
    }

    protected function validateUser($userIdentifier) {
        if (preg_match(sfValidatorEmail::REGEX_EMAIL, $userIdentifier)) {
            if (!$user = Doctrine::getTable('sfGuardUser')->findOneBy('email_address', $userIdentifier)) {
                if ($this->getOption('throw_global_error')) {
                    throw new sfValidatorError($this, 'email');
                } else {
                    throw new sfValidatorErrorSchema($this, array('user' => new sfValidatorError($this, 'email')));
                }
            }
        } else {
            if (!$mcard = Doctrine::getTable('MembershipCard')->retrieveByAlphaId($userIdentifier)) {
                if ($this->getOption('throw_global_error')) {
                    throw new sfValidatorError($this, 'mcard_invalid');
                } else {
                    throw new sfValidatorErrorSchema($this, array('user' => new sfValidatorError($this, 'mcard_invalid')));
                }
            }

            $user = $mcard->getUser();
        }

        return $user;
    }

    protected function getPromo($promo_id) {
        $promo = Doctrine::getTable('Promo')->findOneBy('id', $promo_id);

        if (!$promo->redeemPeriodStarted()) {
            if ($this->getOption('throw_global_error')) {
                throw new sfValidatorError($this, 'promo_redeem_period');
            } else {
                throw new sfValidatorErrorSchema($this, array('promo' => new sfValidatorError($this, 'promo_redeem_period')));
            }
        }

        if ($promo->isExpired()) {
            if ($this->getOption('throw_global_error')) {
                throw new sfValidatorError($this, 'promo_expired');
            } else {
                throw new sfValidatorErrorSchema($this, array('promo' => new sfValidatorError($this, 'promo_expired')));
            }
        }

        return $promo;
    }

}

?>
