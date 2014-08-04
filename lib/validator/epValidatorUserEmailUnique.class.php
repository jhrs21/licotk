<?php

/**
 * Description of epValidatorUser
 *
 * @author Jacobo Martínez
 */
class epValidatorUserEmailUnique extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addOption('email_field', 'email');
        $this->addOption('throw_global_error', true);
        $this->setMessage('invalid', 'An object with the same "%email_field%" already exist.');
    }

    protected function doClean($values) {
        $email = isset($values[$this->getOption('email_field')]) ? $values[$this->getOption('email_field')] : '';

        // don't allow to register with an empty email
        if ($email) {
            $user = $this->getTable()->findOneBy('email_address', $email);
            //  user exists in local db?
            if ($user) {
                // verify if the user is pre-registered
                if (!$user->getPreRegistered()) {
                    if ($this->getOption('throw_global_error')) {
                        throw new sfValidatorError($this, 'invalid');
                    }
                    throw new sfValidatorErrorSchema($this, array($this->getOption('email_field') => new sfValidatorError($this, 'invalid')));
                }
            }

            return array_merge($values, array('user' => $user));
        }

        if ($this->getOption('throw_global_error')) {
            throw new sfValidatorError($this, 'invalid');
        }
        throw new sfValidatorErrorSchema($this, array($this->getOption('email_field') => new sfValidatorError($this, 'invalid')));
    }

    protected function getTable() {
        return Doctrine::getTable('sfGuardUser');
    }

}
