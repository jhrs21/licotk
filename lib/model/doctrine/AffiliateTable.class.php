<?php

/**
 * AffiliateTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class AffiliateTable extends Doctrine_Table {

    public function addWithAssetsInRangeQuery($lat, $long, $maxDistance = 0, Doctrine_Query $q = null) {
        $earth_radius = 6371; /* Radio de La Tierra en Km. */
        $radian_factor = 0.0174532925199433; /* M_PI/180 usado para convertir de grados a radianes (M_PI = 3.1415926535898) */

        $lon1 = $long - ($maxDistance / abs(cos($lat * $radian_factor) * 111.14));
        $lon2 = $long + ($maxDistance / abs(cos($lat * $radian_factor) * 111.14));

        $lat1 = (float) $lat - ($maxDistance / 111.14);
        $lat2 = (float) $lat + ($maxDistance / 111.14);

        if (is_null($q)) {
            $q = Doctrine_Query::create()->from('Affiliate a');
        }

        $alias = $q->getRootAlias();

        $q->leftJoin($alias . '.Assets aa2');

        $q->leftJoin('aa2.Location l');
        $q->andWhere('l.longitude BETWEEN ' . $lon1 . ' AND ' . $lon2);
        $q->andWhere('l.latitude BETWEEN ' . $lat1 . ' AND ' . $lat2);
        $q->andWhere(
                '(2 *' . $earth_radius . ' * ASIN(
                SQRT(
                    POWER(SIN((' . $lat . ' - ABS(l.latitude)) * ' . $radian_factor . '/ 2), 2)
                    + COS(' . $lat . ' * ' . $radian_factor . ') 
                    * COS(ABS(l.latitude) * ' . $radian_factor . ') 
                    * POWER(SIN((' . $long . ' - l.longitude) * ' . $radian_factor . ' / 2), 2) 
                )
            )) <= ' . $maxDistance
        );

        return $q;
    }

    public function addHasAssetTypeQuery($type = 'place', Doctrine_Query $q = null) {
        if (is_null($q)) {
            $q = Doctrine_Query::create()->from('Affiliate a');
        }

        $alias = $q->getRootAlias();

        $q->leftJoin($alias . '.Assets aa')->andWhere('aa.asset_type = ?', $type);

        return $q;
    }

    public function addWithActivePromosQuery(Doctrine_Query $q = null) {
        if (is_null($q)) {
            $q = Doctrine_Query::create()->from('Affiliate a');
        }

        $alias = $q->getRootAlias();

        $q->leftJoin($alias . '.Promos p')
                ->andWhere('p.starts_at <= ?', date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d"), date("Y"))))
                ->andWhere('p.expires_at >= ?', date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d"), date("Y"))));

        return $q;
    }

    public function retrieveWithActivePromos($type = 'place', $limit = 0) {
        $q = $this->addWithActivePromosQuery($this->addHasAssetTypeQuery($type));
        
        if ($limit > 0) {
            $q->limit($limit);
        }
        
        return $q->execute();
    }

    public function addLimitQuery($limit = 10, Doctrine_Query $q = null) {
        if (is_null($q)) {
            $q = Doctrine_Query::create()->from('Affiliate a');
        }

        $q->limit($limit);

        return $q;
    }

    public function addActiveQuery(Doctrine_Query $q = null) {
        if (is_null($q)) {
            $q = Doctrine_Query::create()->from('Affiliate a');
        }

        $alias = $q->getRootAlias();

        $q->andWhere($alias . '.active = ?', true);

        return $q;
    }

    public function addByCategoryQuery($category_id, Doctrine_Query $q = null) {
        if (is_null($q)) {
            $q = Doctrine_Query::create()->from('Affiliate a');
        }

        $alias = $q->getRootAlias();

        $q->andWhere($alias . '.category_id = ?', $category_id);

        return $q;
    }

    public function addByKeywordQuery($keyword, Doctrine_Query $q = null) {
        if (is_null($q)) {
            $q = Doctrine_Query::create()->from('Affiliate a');
        }
        
        $alias = $q->getRootAlias();
        
        $q->andWhere($alias . '.name LIKE ? OR ' . $alias . '.description LIKE ?', array('%' . $keyword . '%', '%' . $keyword . '%'));
        
        return $q;
    }

    public function addBelongsToCategoryQuery($category, Doctrine_Query $q = null) {
        if (is_null($q)) {
            $q = Doctrine_Query::create()->from('Affiliate a');
        }

        $alias = $q->getRootAlias();

        $q->leftJoin($alias . '.AffiliateCategory ac');
        $q->andWhere('ac.category_id = ?', $category);

        return $q;
    }

    public function getPlacesQuery($category = null) {
        $q = $this->addWithActivePromosQuery($this->addHasAssetTypeQuery('place'));

        if (!is_null($category)) {
            $q = $this->addBelongsToCategoryQuery($category, $q);
        }

        return $q;
    }
    
    public function getWithActivePromosQuery($category = null) {
        $q = $this->addWithActivePromosQuery();

        if (!is_null($category)) {
            $q = $this->addBelongsToCategoryQuery($category, $q);
        }
        
        $q->orderBy('RAND()');

        return $q;
    }

    public function retrievePromoted($limit, $category_id = false) {
        $q = $this->addWithActivePromosQuery($this->addHasAssetTypeQuery('place'));
        
        if ($category_id) {
            $q = $this->addByCategoryQuery($category_id, $q);
        }
        
        $q->limit($limit);
        $q->orderBy('RAND()');
        
        return $q->execute();
    }
    
    public function retrieveSuggested($limit, $category_id = false) {
        $q = $this->addWithActivePromosQuery($this->addHasAssetTypeQuery('place'));
        
        if ($category_id) {
            $q = $this->addByCategoryQuery($category_id, $q);
        }
        
        $q->limit($limit);
        $q->orderBy('RAND()');
        
        return $q->execute();
    }
    
    /**
     * Returns an instance of this class.
     *
     * @return object AffiliateTable
     */
    public static function getInstance() {
        return Doctrine_Core::getTable('Affiliate');
    }

}