<?php

/**
 * MunicipalityTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class MunicipalityTable extends Doctrine_Table
{
    public function addByStateQuery($state, Doctrine_Query $q = null)
    {
        if(is_null($q))
        {
            $q = Doctrine_Query::create()
                    ->from('Municipality m');
        }
        
        $alias = $q->getRootAlias();
        
        $q->andWhere($alias.'.state_id = ?', $state);
        
        $q->orderBy('name');
        
        return $q;
    }
    
    /**
     * Returns an instance of this class.
     *
     * @return object MunicipalityTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Municipality');
    }
}