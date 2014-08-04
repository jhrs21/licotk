<?php

/**
 * Description of epValidatorValidationCodeRegistration
 *
 * @author Jacobo Martínez
 */
class epValidatorValidationCodeRegistration extends sfValidatorBase
{
    public function configure($options = array(), $messages = array())
    {
        $this->addOption('code_field', 'code');
        $this->addOption('email_field', 'email');
        $this->addOption('throw_global_error', true);

        $this->setMessage('invalid', 'Código de activación o correo electrónico inválido.');
        $this->addMessage('code-used', 'El código de validación indicado ya ha sido utilizado.');
        $this->addMessage('code-invalid', 'El código de validación es inválido.');
        $this->addMessage('email-invalid', 'El correo electrónico indicado no existe.');
        $this->addMessage('email-inactive', 'No ha completado la activación de la cuenta de usuario para el correo indicado.');
        $this->addMessage('promo-ended', 'El periodo para acumular Tags en la promoción ya ha terminado.');
        $this->addMessage('promo-max-uses', 'Ya ha alcanzado el máximo de participaciones para la promoción.');
        $this->addMessage('promo-max-daily-tags', 'Ya ha alcanzado el máximo de Tags que la promoción permite acumular por día.');
    }
  
    protected function doClean($values) 
    {
        $code = isset($values[$this->getOption('code_field')]) ? $values[$this->getOption('code_field')] : '';
        $email = isset($values[$this->getOption('email_field')]) ? $values[$this->getOption('email_field')] : '';
        
        //$method = sfConfig::get('app_retrieve_vcode_method', false) ? sfConfig::get('app_retrieve_vcode_method') : 'retrieveValidationCode';
        
        /**
         * don't allow to search for an empty code or empty email address
         */
        if ($code && $email) 
        {
            $user = Doctrine::getTable('sfGuardUser')->retrieveByEmail($email);
            
            if (!$user) 
            {
                throw new sfValidatorError($this, 'email-invalid');
            }
            elseif (!$user->getIsActive()) 
            {
                throw new sfValidatorError($this, 'email-inactive');
            }
            
            if (!$vcode = $this->getTable()->retrieveValidationCode($code))
            {
                if ($this->getOption('throw_global_error')) 
                {
                    throw new sfValidatorError($this, 'code-invalid');
                } 
                else
                {
                    throw new sfValidatorErrorSchema($this, array($this->getOption('code_field') => new sfValidatorError($this, 'code-invalid')));
                }               
            }
            else
            {
                $this->businessRulesValidation($vcode, $user);
                
                return array_merge($values, array('vcode' => $vcode));
            }
        }

        if ($this->getOption('throw_global_error')) 
        {
            throw new sfValidatorError($this, 'code-invalid');
        } 
        else
        {
            throw new sfValidatorErrorSchema($this, array($this->getOption('code_field') => new sfValidatorError($this, 'code-invalid')));
        }
    }
    
    public function businessRulesValidation(ValidationCode $vcode, sfGuardUser $user) 
    {
        $promocode = $vcode->getPromoCode();
        
        $promo = $promocode->getPromo();
            
        if(!$promo->isActive())
        {
            throw new sfValidatorError($this, 'promo-ended');
        }
        else if(!$promocode->isActive())
        {
            throw new sfValidatorError($this, 'code-invalid');
        }
        else
        {
            if($promo->getMaxUses() > 0 && $user->getCompleteParticipationsNumber($promo->getId()) == $promo->getMaxUses())
            {
                throw new sfValidatorError($this, 'promo-max-uses');
            }
            else
            {
                if($promo->getMaxDailyTags() > 0 && $user->countTodayTickets($promo->getId()) == $promo->getMaxDailyTags())
                {
                    throw new sfValidatorError($this, 'promo-max-daily-tags');
                }
            }
        }
    }


    protected function getTable()
    {
        return Doctrine::getTable('ValidationCode');
    }
}
