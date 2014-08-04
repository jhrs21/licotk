<?php

/**
 * Description of epValidatorUser
 *
 * @author Jacobo Martínez
 */
class epValidatorLogin extends sfGuardValidatorUser 
{
    public function configure($options = array(), $messages = array())
    {
        parent::configure();
        
        $this->setOption('throw_global_error', true);
        
        $this->setMessage('invalid', 'Correo electrónico y/o password inválido.');
        
        $this->addMessage(
                    'not_active', 
                    'Aún no has confirmado tu correo electrónico.'.
                    '<br>Haz clic <a href="%URL%">Aquí</a> y te enviaremos un correo con el link de confimación.'
                );
    }
    
    protected function doClean($values) 
    {
        $username = isset($values[$this->getOption('username_field')]) ? $values[$this->getOption('username_field')] : '';
        $password = isset($values[$this->getOption('password_field')]) ? $values[$this->getOption('password_field')] : '';

        $allowEmail = sfConfig::get('app_sf_guard_plugin_allow_login_with_email', true);
        
        $method = $allowEmail ? 'retrieveByUsernameOrEmailAddressWithoutActiveFlag' : 'retrieveByUsernameWithoutActiveFlag';

        // don't allow to sign in with an empty username
        if ($username) {
            if ($callable = sfConfig::get('app_sf_guard_plugin_retrieve_by_username_callable')) {
                $user = call_user_func_array($callable, array($username));
            } 
            else {
                $user = $this->getTable()->$method($username);
            }
            
            //  user exists in local db?
            if ($user) {
                $user = new sfGuardUser();
                if(!$user->getIsActive())
                {
                    $type = $user->getUserProfile()->getValidationType();
                    
                    if($type && strcasecmp($type,'New') == 0)
                    {                        
                        $routing = sfContext::getInstance()->getRouting();
                        
                        $url = $routing->generate(
                                        'resend_verification', 
                                        array('user_alpha' => $user->getAlphaId()), 
                                        true
                                    );
                        
                        $msg = $this->getMessage('not_active');
                        
                        $this->setMessage('not_active',  str_replace('%URL%', $url, $msg));
                        
                        if ($this->getOption('throw_global_error')) {
                            throw new sfValidatorError($this, 'not_active');
                        }

                        throw new sfValidatorErrorSchema($this, array(
                                $this->getOption('username_field') => new sfValidatorError($this, 'not_active')
                            ));
                    }
                }
                else if ($user->checkPassword($password)) // password is ok?
                {
                    return array_merge($values, array('user' => $user));
                }
            }
            else{
                
            }
        }

        if ($this->getOption('throw_global_error')) {
            throw new sfValidatorError($this, 'invalid');
        }

        throw new sfValidatorErrorSchema($this, array($this->getOption('username_field') => new sfValidatorError($this, 'invalid')));
    }
    
    protected function getTable()
    {
        return Doctrine::getTable('sfGuardUser');
    }
}
