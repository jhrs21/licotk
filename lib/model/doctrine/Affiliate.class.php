<?php

/**
 * Affiliate
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    elperro
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Affiliate extends BaseAffiliate {

    /**
     * 
     * @param mixed $with_assets If false doesn't returns assets, if it's a location returns assets near to the location given and if it's an object collection returns every object as an array
     * @param mixed $with_promos If false doesn't returns promos, if true returns all active promos, if it's a collection returns every object as an array
     * @return array 
     */
    public function asArray($assetsSearchParams = false, $with_promos = false, $host = null) {
        $affiliate = array(
            'id' => $this->getAlphaId(),
            'category' => array($this->getCategory()->getName(), $this->getCategory()->getAlphaId()),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'slug' => $this->getSlug(),
            'logo' => sfConfig::get('app_main_domain') . '/uploads/' . $this->getLogo(),
            'thumb' => sfConfig::get('app_main_domain') . '/uploads/' . $this->getThumb()
        );

        if ($assetsSearchParams) {
            $assets = $this->assetsAsArray($assetsSearchParams);

            if (count($assets)) {
                $affiliate[$assetsSearchParams['type'] . 's'] = $this->assetsAsArray($assetsSearchParams);
            }
        }

        if ($with_promos) {
            $promos_array = array();

            $promos = $this->getActivePromos();

            foreach ($promos as $promo) {
                $promos_array[$promo->getAlphaId()] = $promo->asArray(false, $host);
            }

            $affiliate['promos'] = $promos_array;
        }

        return $affiliate;
    }

    public function assetsAsArray($searchParams = array()) {
        $assets_array = array();

        switch ($searchParams['type']) {
            case 'place':
                $params = $searchParams;
                $params['in_active_promos'] = true;
                $assets_array = $this->placesAsArray($params);

                break;

            case 'brand':
                $params = $searchParams;
                $params['in_active_promos'] = true;
                $assets_array = $this->brandsAsArray($params);

                break;

            default:
                break;
        }

        return $assets_array;
    }

    public function placesAsArray($params = array()) {
        $q = $this->getAssetsQuery($params);

        $result = $q->execute(array());

        $places = array();

        foreach ($result as $place) {
            $location = $place->getLocation()->getFirst();
            $places[$place->getAlphaId()] = array(
                'id' => $place->getAlphaId(),
                'name' => $place->getName(),
                'location' => array(
                    'address' => $location->getAddress(),
                    'lat' => $location->getLatitude(),
                    'long' => $location->getLongitude(),
                )
            );
        }

        return $places;
    }

    public function brandsAsArray($params = array()) {
        $routing = sfContext::getInstance()->getRouting();

        $params['with_category'] = true;

        $q = $this->getAssetsQuery($params);

        $result = $q->execute(array(), Doctrine::HYDRATE_ARRAY);

        $brands = array();

        foreach ($result as $row) {
            $brands[$row['alpha_id']] = array(
                'id' => $row['alpha_id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'category' => array($row['Category']['name'], $row['Category']['alpha_id']),
                'logo' => $routing->generate('homepage', array(), true) . 'uploads/' . $row['logo'],
                'thumb' => $row['thumb'],
            );
            if (isset($row['Location'])) {
                $brands[$row['alpha_id']]['pos'] = array(
                    'pos_name' => $row['Location']['name'],
                    'pos_address' => $row['Location']['address'],
                    'pos_lat' => $row['Location']['latitude'],
                    'pos_long' => $row['Location']['longitude'],
                );
            }
        }

        return $places;
    }

    public function getPlaces() {
        return $this->getAssetsQuery(array('type' => 'place'))->execute();
    }

    public function getBrands() {
        return $this->getAssetsQuery(array('type' => 'brand'))->execute();
    }

    public function getAssetsQuery($params = array()) {
        $table = Doctrine::getTable('Asset');

        $q = $table->addByAffiliateQuery($this->id);

        if (isset($params['type'])) {
            $q = $table->addByTypeQuery($params['type'], $q);
        }

        if (isset($params['in_active_promos']) && $params['in_active_promos']) {
            $q = $table->addByParticipationInActivePromosQuery($q);
        }

        if (isset($params['city'])) {
            $q = $table->addByCityQuery($params['city'], $q);
        }

        if (isset($params['lat']) && isset($params['long'])) {
            $distance = isset($params['distance']) ? $params['distance'] : 0;

            $q = $table->addByDistanceQuery($params['lat'], $params['long'], $distance, true, $q);
        }

        if (isset($params['with_category']) && $params['with_category']) {
            $q = $table->addCategoryJoinQuery($q);
        }

        return $q;
    }

    public function getActivePromos() {
        return Doctrine::getTable('Promo')->retrieveAffiliateActivePromos($this->id);
    }

    public function save(Doctrine_Connection $conn = null) {
        if (!$this->getHash()) {
            $this->setHash(hash('sha256', time() . rand(11111, 99999)));
        }
        if (!$this->getAlphaId()) {
            $this->setAlphaId(Util::gen_uuid($this->getHash()));
        }

        return parent::save($conn);
    }

}