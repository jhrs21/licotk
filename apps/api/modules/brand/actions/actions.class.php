<?php

/**
 * brand actions.
 *
 * @package    elperro
 * @subpackage brand
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class brandActions extends baseEpApiActions {

    /**
     * Executes brands action
     *
     * @param sfRequest $request A request object
     */    
    public function executeBrands(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;

        $route_params = $this->getRoute()->getParameters();
        $apiCredentials = array('apikey' => $route_params['apikey'], 'name' => $route_params['name']);
        $requiredParams = array();

        if (!$this->validateApiRules($apiCredentials, $requiredParams, $request)) {
            return 'Error';
        }

        if (!$user = Doctrine::getTable('sfGuardUser')->findOneByAlphaId($route_params['user_id'])) {
            $this->result['error'] = $this->getErrorFromList('api200');
            return 'Error';
        }
        
        $categoryId = $request->getParameter('category', false);
        $limit = $request->getParameter('limit', sfConfig::get('app_ep_max_affiliates_per_page'));
        $offset = $request->getParameter('offset', 0);
        
        $table = Doctrine::getTable('Asset');
        
        if ($categoryId) {
            if ($category = $this->validateCategory($categoryId)) {
                $categoryId = $category->getId();
                
                $q = $table->getBrandsQuery($categoryId);
                $q->limit($limit);
                $q->offset($offset);
                
                $count = $q->count();
                $brands = $q->execute();

                foreach ($brands as $brand) {
                    $this->result['brands'][$brand->getAlphaId()] = $brand->asArray(false);
                }

                $this->result['pagination'] = array(
                        'limit' => $limit, 
                        'offset' => $offset + $limit, 
                        'more' => $count > $offset + $limit ? 1 : 0
                    );
            } else {
                return 'Error';
            }
        }
        
        $promoted = $table->retrievePromoted(sfConfig::get('app_ep_max_promoted'), 'brand', $categoryId);
        
        foreach ($promoted as $affiliate) {
            $this->result['promoted'][$affiliate->getAlphaId()] = $affiliate->asArray(false, false);
        }
        
//        $affiliates = $table->retrieveSuggested(sfConfig::get('app_ep_max_suggested'), $categoryId);
//        
//        foreach ($affiliates as $affiliate) {
//            $this->result['suggested'][$affiliate->getAlphaId()] = $affiliate->asArray(false, false);
//        }
        
        $categories = Doctrine::getTable('Category')->retrieveWithActivePromos($categoryId, 'brand');
        
        foreach ($categories as $category) {
            $this->result['categories'][$category->getAlphaId()] = $category->asArray();
        }
        
        $this->result['success'] = 1;
    }
}
