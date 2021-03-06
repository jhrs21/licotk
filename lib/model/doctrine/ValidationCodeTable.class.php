<?php

/**
 * ValidationCodeTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class ValidationCodeTable extends Doctrine_Table {
    public function addByPromoCodeQuery($promocode, Doctrine_Query $q = null) {
        if (is_null($q)) {
            $q = Doctrine_Query::create()
                    ->from('ValidationCode vc');
        }

        $alias = $q->getRootAlias();

        $q->andWhere($alias . '.promo_code_id = ?', $promocode);

        return $q;
    }

    public function addByUsedQuery($used = false, Doctrine_Query $q = null) {
        if (is_null($q)) {
            $q = Doctrine_Query::create()->from('ValidationCode vc');
        }

        $alias = $q->getRootAlias();

        $q->andWhere($alias . '.used = ?', $used);

        return $q;
    }

    public function addByActiveQuery($active = true, Doctrine_Query $q = null) {
        if (is_null($q)) {
            $q = Doctrine_Query::create()->from('ValidationCode vc');
        }

        $alias = $q->getRootAlias();

        $q->andWhere($alias . '.active = ?', $active);

        return $q;
    }

    public function addByCodeQuery($code, Doctrine_Query $q = null) {
        if (is_null($q)) {
            $q = Doctrine_Query::create()->from('ValidationCode vc');
        }

        $alias = $q->getRootAlias();

        $q->andWhere($alias . '.code = ?', $code);

        return $q;
    }

    public function retrieveValidationCode($code, $promocode = null, $used = false, $active = true) {
        $q = $this->addByCodeQuery($code, $this->addByUsedQuery($used, $this->addByActiveQuery($active)));

        if (!is_null($promocode)) {
            $q = $this->addByPromoCodeQuery($promocode, $q);
        }

        return $q->fetchOne();
    }

    /**
     * Returns an instance of this class.
     *
     * @return object ValidationCodeTable
     */
    public static function getInstance() {
        return Doctrine_Core::getTable('ValidationCode');
    }

}