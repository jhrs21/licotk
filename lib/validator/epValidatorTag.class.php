<?php

/**
 * Description of epValidatorTag
 *
 * @author Jacobo Martínez
 */
class epValidatorTag extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addOption('user_field', 'user_identifier');
        $this->addOption('promocode_field', 'promocode');
        $this->addOption('throw_global_error', true);

        $this->setMessage('invalid', 'Correo Electrónico o Tarjeta inválida');
        $this->addMessage('email', 'Error al enviar Correo Electrónico al usuario');
        $this->addMessage('mcard_invalid', 'Tarjeta inválida');
        $this->addMessage('mcard_unassigned', 'Tarjeta no asignada');
        $this->addMessage('pcode_invalid', 'Promoción inválida');
        $this->addMessage('pcode_inactive', 'Promoción inactiva');
    }

    protected function doClean($values) {
        $user_val = isset($values[$this->getOption('user_field')]) ? $values[$this->getOption('user_field')] : '';

        $promocode = isset($values[$this->getOption('promocode_field')]) ? $values[$this->getOption('promocode_field')] : '';
        
        if (!$user_val) {
            if ($this->getOption('throw_global_error')) {
                throw new sfValidatorError($this, 'invalid');
            } else {
                throw new sfValidatorErrorSchema($this, array($this->getOption('user_field') => new sfValidatorError($this, 'invalid')));
            }
        }
        
        $user = false;

        if (preg_match(sfValidatorEmail::REGEX_EMAIL, $user_val)) {
            $user = $this->validateUserByEmail($user_val);
            $via = 'web_email';
        }
        else {
            $user = $this->validateUserByMembershipCard($user_val);
            $via = 'web_card';
        }
        
        $pcode = $this->validatePromoCode($promocode);
        
        return array_merge($values, array('user' => $user, 'via' => $via, 'promocode' => $pcode));
    }
    
    protected function validateUserByEmail($email) {
        return Doctrine::getTable('sfGuardUser')->findOneBy('email_address', $email);
    }

    protected function validateUserByMembershipCard($membershipCardId) {
        if (!$membershipCard = Doctrine::getTable('MembershipCard')->findOneBy('alpha_id', $membershipCardId)) {
            if ($this->getOption('throw_global_error')) {
                throw new sfValidatorError($this, 'mcard_invalid');
            } else {
                throw new sfValidatorErrorSchema($this, array($this->getOption('user_field') => new sfValidatorError($this, 'mcard_invalid')));
            }
        }

        if ($membershipCard->hasStatus('inactive')) {
            if ($this->getOption('throw_global_error')) {
                throw new sfValidatorError($this, 'mcard_invalid');
            } else {
                throw new sfValidatorErrorSchema($this, array($this->getOption('user_field') => new sfValidatorError($this, 'mcard_invalid')));
            }
        }

        if ($membershipCard->hasStatus('unassigned')) {
            if ($this->getOption('throw_global_error')) {
                throw new sfValidatorError($this, 'mcard_unassigned');
            } else {
                throw new sfValidatorErrorSchema($this, array($this->getOption('user_field') => new sfValidatorError($this, 'mcard_unassigned')));
            }
        }

        return $membershipCard->getUser();
    }

    protected function validatePromoCode($promocode) {
        if (!$pcode = Doctrine::getTable('PromoCode')->retrievePromoCode($promocode)) {
            if ($this->getOption('throw_global_error')) {
                throw new sfValidatorError($this, 'pcode_invalid');
            } else {
                throw new sfValidatorErrorSchema($this, array($this->getOption('promocode_field') => new sfValidatorError($this, 'pcode_invalid')));
            }
        }

        if (!$pcode->isActive()) {
            if ($this->getOption('throw_global_error')) {
                throw new sfValidatorError($this, 'pcode_inactive');
            } else {
                throw new sfValidatorErrorSchema($this, array($this->getOption('promocode_field') => new sfValidatorError($this, 'pcode_inactive')));
            }
        }
        
        return $pcode;
    }
}
