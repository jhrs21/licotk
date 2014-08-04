<?php

class epValidatorActivateQr extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addOption('promocode_field', 'promocode');
        $this->addOption('serial_inf_field', 'serial inferior');
        $this->addOption('serial_sup_field', 'serial superior');
        $this->addOption('throw_global_error', true);

        $this->setMessage('invalid', 'Codigo de promocion invalido');
        $this->addMessage('inf_invalid', 'Valor de serial inferior invalido');
        $this->addMessage('sup_invalid', 'Valor de serial superior invalido');
        $this->addMessage('rangos-invalidos', 'Rango inferior es mayor que el rango superior');
    }

    protected function doClean($values) {
        $promocode = isset($values[$this->getOption('promocode_field')]) ? $values[$this->getOption('promocode_field')] : '';

        $serial_inf = isset($values[$this->getOption('serial_inf_field')]) ? $values[$this->getOption('serial_inf_field')] : '';

        $serial_sup = isset($values[$this->getOption('serial_sup_field')]) ? $values[$this->getOption('serial_sup_field')] : '';

        //$method = sfConfig::get('app_retrieve_coupon_for_redeem_method', false) ? sfConfig::get('app_retrieve_coupon_for_redeem_method') : 'retrieveBySerial';
        // don't allow to search for an empty serial
//        if ($serial) 
//        {
        //$coupon = $this->getTable()->$method($serial);
        $promo = Doctrine::getTable("PromoCode")->findBy('id', $promocode)->getFirst();
        if (!$promo->hasStatus('unassigned')) {
            if ($promo->hasStatus('used')) {
                if ($this->getOption('throw_global_error')) {
                    throw new sfValidatorError($this, 'invalid');
                } else {
                    throw new sfValidatorErrorSchema($this, array($this->getOption('promocode_field') => new sfValidatorError($this, 'invalid')));
                }
            }
        }
        
//        echo $promocode;
//        $validation_codes = Doctrine_Query::create()
//                        ->from('ValidationCode vc')
//                        ->where('vc.serial BETWEEN ? AND ?', array(1, 2))
//                        ->andWhere('vc.promo_code_id = ?',$promocode)
//                        ->execute();
//        echo $validation_codes;
        
        if($serial_inf>$serial_sup){
                throw new sfValidatorError($this, 'rangos-invalidos');
            }
        
        if ($promo && $serial_inf && $serial_sup) {
            // check range serials
            return array_merge($values, array('promo' => $promo, 'inferior' => $serial_inf, 'superior' => $serial_sup));
        }
    }

    protected function getTable() {
        return Doctrine::getTable('Coupon');
    }

}

?>
