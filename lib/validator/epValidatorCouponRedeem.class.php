<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of epValidatorCouponRedeem
 *
 * @author Jacobo Martínez
 */
class epValidatorCouponRedeem extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addOption('serial_field', 'serial');
        $this->addOption('password_field', 'password');
        $this->addOption('throw_global_error', true);

        $this->setMessage('invalid', 'El serial y/o password son inválidos.');
        $this->addMessage('coupon-used', 'Este premio ya fue canjeado en otra oportunidad.');
        $this->addMessage('coupon-invalid', 'El premio es inválido.');
        $this->addMessage('coupon-expired', 'Este premio ya ha expirado.');
        $this->addMessage('redeem-period', 'Aún no ha comenzado el periodo de canje para este premio.');
    }

    protected function doClean($values) {
        $serial = isset($values[$this->getOption('serial_field')]) ? $values[$this->getOption('serial_field')] : '';

        $password = isset($values[$this->getOption('password_field')]) ? $values[$this->getOption('password_field')] : '';

        $method = sfConfig::get('app_retrieve_coupon_for_redeem_method', false) ? sfConfig::get('app_retrieve_coupon_for_redeem_method') : 'retrieveBySerial';

        // don't allow to search for an empty serial
        if ($serial) {
            $coupon = $this->getTable()->$method($serial);

            // coupon exists?
            if ($coupon) {
                if ($coupon->hasStatus('used')) {
                    if ($this->getOption('throw_global_error')) {
                        throw new sfValidatorError($this, 'coupon-used');
                    } else {
                        throw new sfValidatorErrorSchema($this, array($this->getOption('serial_field') => new sfValidatorError($this, 'coupon-used')));
                    }
                }
                
                if (!$coupon->getPromo()->redeemPeriodStarted()) {
                    if ($this->getOption('throw_global_error')) {
                        throw new sfValidatorError($this, 'redeem-period');
                    } else {
                        throw new sfValidatorErrorSchema($this, array($this->getOption('serial_field') => new sfValidatorError($this, 'redeem-period')));
                    }
                }
                
                if ($coupon->isExpired()) {
                    if ($this->getOption('throw_global_error')) {
                        throw new sfValidatorError($this, 'coupon-expired');
                    } else {
                        throw new sfValidatorErrorSchema($this, array($this->getOption('serial_field') => new sfValidatorError($this, 'coupon-expired')));
                    }
                }

                // password is ok?
                if ($coupon->checkPassword($password)) {
                    return array_merge($values, array('coupon' => $coupon));
                }
            }
        }

        if ($this->getOption('throw_global_error')) {
            throw new sfValidatorError($this, 'invalid');
        } else {
            throw new sfValidatorErrorSchema($this, array($this->getOption('serial_field') => new sfValidatorError($this, 'invalid')));
        }
    }

    protected function getTable() {
        return Doctrine::getTable('Coupon');
    }

}
