<?php

class epUserApplyMobileForm extends epUserApplyForm {

    public function configure() {
        parent::configure();

        unset($this['password2'], $this['phone']);
    }

    public function getPostValidators() {
        $validators = array(
                new sfValidatorDoctrineUnique(array('model' => 'UserProfile', 'column' => 'id_number'), array('invalid' => 'El número de cedula indicado ya se encuentra vinculado a una cuenta.')),
            );

        if ($this->getObject()->isNew()) {
            $validators[] = new epValidatorUserEmailUnique(array(), array('invalid' => 'Ya ha sido registrada una cuenta con este correo electrónico.'));
        }

        return $validators;
    }
}