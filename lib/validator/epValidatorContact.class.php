<?php

class epValidatorContact extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addOption('business', 'business');
        $this->addOption('address', 'address');
        $this->addOption('rif', 'rif');
        $this->addOption('type', 'type');
        $this->addOption('name', 'name');
        $this->addOption('phone', 'phone');
        $this->addOption('email', 'email');
        $this->addOption('throw_global_error', true);

        $this->setMessage('invalid', 'Datos invalidos');
         $this->addMessage('rif', 'rif necesario.');
    }

    protected function doClean($values) {
        $business = isset($values[$this->getOption('business')]) ? $values[$this->getOption('business')] : '';
        $address = isset($values[$this->getOption('address')]) ? $values[$this->getOption('address')] : '';
        $rif = isset($values[$this->getOption('rif')]) ? $values[$this->getOption('rif')] : '';
        $type = isset($values[$this->getOption('type')]) ? $values[$this->getOption('type')] : '';
        $name = isset($values[$this->getOption('name')]) ? $values[$this->getOption('name')] : '';
        $phone = isset($values[$this->getOption('phone')]) ? $values[$this->getOption('phone')] : '';
        $email = isset($values[$this->getOption('email')]) ? $values[$this->getOption('email')] : '';
        
        if ($this->getOption('throw_global_error')){ 
                    throw new sfValidatorError($this, 'invalid');
        } else {
                    throw new sfValidatorErrorSchema($this, array($this->getOption('rif') => new sfValidatorError($this, 'invalid')));
                }
        if ($business && $address && $rif && $type && $name && $phone && $email) {
            return array_merge($values, array('business' => $business, 'address' => $address, 'rif' => $rif, 'type' => $type, 'name' => $name, 'phone' => $phone, 'email' => $email));
        }
    }
    
}

?>

