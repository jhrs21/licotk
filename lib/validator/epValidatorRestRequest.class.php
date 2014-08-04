<?php

/**
 * Description of epValidatorRestRequest
 *
 * @author jacobo
 */
class epValidatorRestRequest extends sfValidatorBase
{
    public function configure($options = array(), $messages = array())
    {
        $this->addOption('email_field', 'username_or_email');
        $this->addOption('throw_global_error', true);

        $this->setMessage('invalid', 'El email indicado no se encuentra asociado a ningun usuario.');
    }
  
    protected function doClean($values) 
    {
        $username_or_email = isset($values[$this->getOption('email_field')]) ? $values[$this->getOption('email_field')] : '';

        // don't allow to search for an empty email or username
        if ($username_or_email) 
        {
            if (strpos($username_or_email, '@') !== false) 
            {
                $user = Doctrine::getTable('sfGuardUser')->createQuery('u')
                            ->where('u.email_address = ?', $username_or_email)
                            ->fetchOne();
            } 
            else 
            {
                $user = Doctrine::getTable('sfGuardUser')->createQuery('u')
                            ->where('u.username = ?', $username_or_email)
                            ->fetchOne();
            }
            
            if ($user) 
            {
                return array_merge($values, array('user' => $user));
            }
        }

        if ($this->getOption('throw_global_error')) 
        {
            throw new sfValidatorError($this, 'invalid');
        } 
        else
        {
            throw new sfValidatorErrorSchema($this, array($this->getOption('email_field') => new sfValidatorError($this, 'invalid')));
        }
    }
    
    protected function getTable()
    {
        return Doctrine::getTable('sfGuardUser');
    }
}