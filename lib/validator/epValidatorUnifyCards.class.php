<?php

/**
 * Description of epValidatorTag
 *
 * @author Jacobo Martínez
 */
class epValidatorUnifyCards extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addOption('promo_field', 'promo');
        $this->addOption('throw_global_error', true);

        $this->setMessage('invalid', 'Promoción inactiva');
    }

    protected function doClean($values) {
        $promoVal = isset($values[$this->getOption('promo_field')]) ? $values[$this->getOption('promo_field')] : '';
        
        if (!$promo = Doctrine::getTable('Promo')->findOneBy('id', $promoVal)) {
            if ($this->getOption('throw_global_error')) {
                throw new sfValidatorError($this, 'invalid');
            } else {
                throw new sfValidatorErrorSchema($this, array($this->getOption('promo_field') => new sfValidatorError($this, 'invalid')));
            }
        }
        
        $users = $this->usersQuery($promo->getId())->execute();
        
        return array_merge($values, array('users' => $users, 'promo' => $promo));
    }
    
    protected function usersQuery($promo) {
        $q = Doctrine_Query::create()->from('sfGuardUser u');
        
        $alias = $q->getRootAlias();
        
        $q->addSelect('DISTINCT( '.$alias.'.id) AS id');

        $q->leftJoin($alias . '.Cards c');
        
        $q->addWhere('c.promo_id = ?', $promo);

        $q->andWhereIn('c.status', array('active', 'complete'));
        
        $q->orderBy($alias . '.id ASC');
        
        $q->setHydrationMode(Doctrine_Core::HYDRATE_SINGLE_SCALAR);

        return $q;
    }
}
