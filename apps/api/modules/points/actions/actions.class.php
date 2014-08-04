<?php

/**
 * points actions.
 *
 * @package    elperro
 * @subpackage points
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class pointsActions extends baseEpApiActions {

    public function executeIndex(sfWebRequest $request) {
        $this->isAuthenticated($request->getParameter('apikey'), $request->getParameter('asset'));
        $data = array('working!');
        return self::_return($data);
    }

    public function executeGetUserPoints(sfWebRequest $request) {
        $route_params = $this->getRoute()->getParameters();
        $data = array('success' => 0);
        $user = Doctrine::getTable('sfGuardUser')->findOneByHash($route_params['user_id']);
        $currency = Doctrine::getTable('Currency')->findOneByAlphaId($route_params['currency']);

        $userHavePermissions = self::checkPermissions($route_params['asset'], $currency);
        if (!$userHavePermissions['success']) {
            $data = $userHavePermissions;
            return self::_return($data);
        }
        
        if(!$this->isAuthenticated($route_params['apikey'], $route_params['asset'])){
            $data['error'] = $this->getErrorFromList('api2016');
            return self::_return($data);
        }

        if (!$user) {
            $data['error'] = $this->getErrorFromList('api2001');
            return self::_return($data);
        }

        $pocket = self::getPocket($currency->getId(), $user->getId());
        if (!$pocket) {
            $pocket = self::createPocket($currency->getId(), $user->getId());
        }

        $data['asset'] = $route_params['asset'];
        $data['user'] = array("hash" => $user->getHash());
        $data['user']['totalPoints'] = $pocket->getBalance() == null ? 0 : $pocket->getBalance();
        $data['success'] = 1;
        return self::_return($data);
    }

    public function executeCreateValuePerAction(sfWebRequest $request) {
        $route_params = $request->getParameterHolder()->getAll();
        $data = array('success' => 0);
        $this->result = array();

        $currency = Doctrine::getTable('Currency')->findOneByAlphaId($route_params['currency']);
        $asset = Doctrine::getTable('Asset')->findOneByAlphaId($route_params['asset']);
        $userHavePermissions = self::checkPermissions($asset->getAlphaId(), $currency);
        if (!$userHavePermissions['success']) {
            $data = $userHavePermissions;
            return self::_return($data);
        }
        
        if(!$this->isAuthenticated($route_params['apikey'], $route_params['asset'])){
            $data['error'] = $this->getErrorFromList('api2016');
            return self::_return($data);
        }

        if (!$this->paramsAreOk(array('action_name', 'value'), $request)) {
            $data['error'] = $this->getErrorFromList('api2012');
            $data['error'] = $this->result['error']['message'];
            return self::_return($data);
        }

        $currencyAsset = self::getCurrencyAsset($asset->getAlphaId(), $currency);
        //Verify action_name for that asset is unique
        $actionRepeated = Doctrine_Query::create()
                ->from('ValuePerAction vpa')
                ->leftJoin('vpa.CurrencyAsset ca')
                ->where('ca.currency_id=?', $currency->getId())
                ->andWhere('vpa.action=?', $route_params['action_name'])
                ->fetchOne();

        if ($actionRepeated) {
            $data['error'] = $this->getErrorFromList('api2015');
            return self::_return($data);
        }

        $action = $this->createAction($route_params['action_name'], $route_params['value'], $currencyAsset);

        $data['action'] = array("alpha_id" => $action->getAlphaId());
        $data['action']['action_name'] = $action->getAction();
        $data['action']['value'] = $action->getValue();

        $data['success'] = 1;
        return self::_return($data);
    }

    public function executeEditValuePerAction(sfWebRequest $request) {
        $route_params = $request->getParameterHolder()->getAll();
        $data = array('success' => 0);

        $currency = Doctrine::getTable('Currency')->findOneByAlphaId($route_params['currency']);
        $userHavePermissions = self::checkPermissions($route_params['asset'], $currency);
        if (!$userHavePermissions['success']) {
            $data = $userHavePermissions;
            return self::_return($data);
        }
        
        if(!$this->isAuthenticated($route_params['apikey'], $route_params['asset'])){
            $data['error'] = $this->getErrorFromList('api2016');
            return self::_return($data);
        }

        $action = Doctrine::getTable('ValuePerAction')->findOneByAlphaId($route_params['action_per_value']);
        if (array_key_exists('action_name', $route_params)) {
            $action->setAction($route_params['action_name']);
        }
        if (array_key_exists('value', $route_params)) {
            $action->setValue($route_params['value']);
        }

        $action->save();

        $data['action'] = array("alpha_id" => $action->getAlphaId());
        $data['action']['action_name'] = $action->getAction();
        $data['action']['value'] = $action->getValue();

        $data['success'] = 1;
        return self::_return($data);
    }

    public function executeEditCurrency(sfWebRequest $request) {
        $route_params = $request->getParameterHolder()->getAll();
        $data = array('success' => 0);

        $currency = Doctrine::getTable('Currency')->findByAlphaId($route_params['currency']);

        $asset = Doctrine::getTable('Asset')->findOneByAlphaId($route_params['asset']);
        $currencyAssets = Doctrine::getTable('CurrencyAsset')->findByAssetId($asset->getId());
        if ($currencyAssets->count() > 1) {
            $data['error'] = $this->getErrorFromList('api2002');
            return self::_return($data);
        }

        if ($currency->getIsSuper()) {
            $data['error'] = $this->getErrorFromList('api2003');
            return self::_return($data);
        }

        $userHavePermissions = self::checkPermissions($route_params['asset'], $currency);
        if (!$userHavePermissions['success']) {
            $data = $userHavePermissions;
            return self::_return($data);
        }
        
        if(!$this->isAuthenticated($route_params['apikey'], $route_params['asset'])){
            $data['error'] = $this->getErrorFromList('api2016');
            return self::_return($data);
        }

        if (array_key_exists('name', $route_params)) {
            $currency->setName($route_params['name']);
        }
        if (array_key_exists('symbol', $route_params)) {
            $currency->setSymbol($route_params['symbol']);
        }

        $currency->save();

        $data['currency'] = array("alpha_id" => $currency->getAlphaId());
        $data['currency']['name'] = $currency->getName();
        $data['currency']['symbol'] = $currency->getSymbol();

        $data['success'] = 1;
        return self::_return($data);
    }

    public function executeGetActions(sfWebRequest $request) {
        $route_params = $request->getParameterHolder()->getAll();
        $data = array('success' => 0);
        
        $asset = Doctrine::getTable('Asset')->findOneByAlphaId($route_params['asset']);
        if (!$asset) {
            $data['error'] = $this->getErrorFromList('api2004');
            return self::_return($data);
        }
        
        if(!$this->isAuthenticated($route_params['apikey'], $route_params['asset'])){
            $data['error'] = $this->getErrorFromList('api2016');
            return self::_return($data);
        }

        $actions = Doctrine_Query::create()
                ->from('ValuePerAction vpa')
                ->leftJoin('vpa.CurrencyAsset ca')
                ->where('ca.asset_id=?', $asset->getId());
        $actions = $actions->execute();

        foreach ($actions as $action) {
            $currencySymbol = $action->getCurrencyAsset()->getCurrency()->getSymbol();
            $data[$currencySymbol][$action->getAlphaId()] = array("action" => $action->getAction(), "value" => $action->getValue());
        }
        $data['success'] = 1;
        return self::_return($data);
    }

    public function executeConsumeUserPoints(sfWebRequest $request) {
        $route_params = $request->getParameterHolder()->getAll();
        $data = array('success' => 0);
        $this->result = "";

        if (!$this->paramsAreOk(array('currency', 'user_id', 'asset', 'points'), $request)) {
            $data['error'] = $this->getErrorFromList('api2012');
            $data['error'] = $this->result['error']['message'];
            return self::_return($data);
        }

        $user = Doctrine::getTable('sfGuardUser')->findOneByHash($route_params['user_id']);
        $currency = Doctrine::getTable('Currency')->findOneByAlphaId($route_params['currency']);

        $userHavePermissions = self::checkPermissions($route_params['asset'], $currency);
        if (!$userHavePermissions['success']) {
            $data = $userHavePermissions;
            return self::_return($data);
        }
        
        if(!$this->isAuthenticated($route_params['apikey'], $route_params['asset'])){
            $data['error'] = $this->getErrorFromList('api2016');
            return self::_return($data);
        }

        if (!$user) {
            $data['error'] = $this->getErrorFromList('api2001');
            return self::_return($data);
        }

        $pocket = self::getPocket($currency->getId(), $user->getId());
        if (!$pocket) {
            $data['error'] = $this->getErrorFromList('api2005');
            return self::_return($data);
        }
        
        if ($pocket->getBalance() < $route_params['points']) {
            $data['error'] = $this->getErrorFromList('api2006');
            return self::_return($data);
        }

        $pocket->setBalance($pocket->getBalance() - $route_params['points']);
        $pocket->save();

        $ca = self::getCurrencyAsset($route_params['asset'], $currency);
        if($currency->getIsSuper()){
            $action = Doctrine_Query::create()
                    ->from('ValuePerAction vpa')
                    ->where('vpa.action = ?', 'consume')
                    ->andWhere('vpa.is_super = 1')
                    ->fetchOne();
        }else{
            $action = Doctrine_Query::create()
                    ->from('ValuePerAction vpa')
                    ->where('vpa.action = ?', 'consume')
                    ->andWhere('currency_asset_id = ?', $ca->getId())
                    ->fetchOne();
        }
            
        if (!$action) {
            $data['error'] = $this->getErrorFromList('api2009');
            return self::_return($data);
        }

        $flow = new Flow();
        $flow->setDirection('outcome');
        $flow->setAmount($route_params['points']);
        $flow->setBalance($pocket->getBalance());
        $flow->setAction($action);
        $flow->setUser($user);
        $flow->setPocket($pocket);
        $flow->setCurrency($currency);
        $flow->save();
        
        $data['user'] = $user->getHash();
        $data['currency'] = array("id" => $currency->getAlphaId(), "symbol" => $currency->getSymbol());
        $data['balance'] = $pocket->getBalance();
        $data['action'] = array("id" => $action->getAlphaId(), "name" => $action->getAction(), "description" => $action->getDescription());

        $data['success'] = 1;
        return self::_return($data);
    }

    //Necesita verificación de administrador
    protected function executeDiscountUserPoints(sfWebRequest $request) {
        $route_params = $request->getParameterHolder()->getAll();
        $data = array('success' => 0);

        $user = Doctrine::getTable('sfGuardUser')->findOneByHash($route_params['user_id']);
        $currency = Doctrine::getTable('Currency')->findOneByAlphaId($route_params['currency']);

        $userHavePermissions = self::checkPermissions($route_params['asset'], $currency);
        if (!$userHavePermissions['success']) {
            $data = $userHavePermissions;
            return self::_return($data);
        }
        
        if(!$this->isAuthenticated($route_params['apikey'], $route_params['asset'])){
            $data['error'] = $this->getErrorFromList('api2016');
            return self::_return($data);
        }

        if (!$user) {
            $data['error'] = $this->getErrorFromList('api2001');
            return self::_return($data);
        }

        $pocket = self::getPocket($currency->getId(), $user->getId());
        if (!$pocket) {
            $data['error'] = $this->getErrorFromList('api2005');
            return self::_return($data);
        }

        if ($pocket->getBalance() < $route_params['points']) {
            $data['error'] = $this->getErrorFromList('api2006');
            return self::_return($data);
        }

        $ca = self::getCurrencyAsset($route_params['asset'], $currency);
        $action = Doctrine_Query::create()
                ->from('ValuePerAction vpa')
                ->where('vpa.action = ?', 'discount')
                ->andWhere('currency_asset_id = ?', $ca->getId())
                ->fetchOne();

        if (!$action) {
            $data['error'] = $this->getErrorFromList('api2010');
            return self::_return($data);
        }

        $pocket->setBalance($pocket->getBalance() - $route_params['points']);
        $pocket->save();

        $flow = new Flow();
        $flow->setDirection('outcome');
        $flow->setAmount($route_params['points']);
        $flow->setBalance($pocket->getBalance());
        $flow->setAction($action);
        $flow->setUser($user);
        $flow->setPocket($pocket);
        $flow->setCurrency($currency);
        $flow->save();

        $data['user'] = $user->getHash();
        $data['currency'] = array("id" => $currency->getAlphaId(), "symbol" => $currency->getSymbol());
        $data['balance'] = $pocket->getBalance();
        $data['action'] = array("id" => $action->getAlphaId(), "name" => $action->getAction(), "description" => $action->getDescription());

        $data['success'] = 1;
        return self::_return($data);
    }

    public function executeAddUserPoints(sfWebRequest $request) {
        $route_params = $request->getParameterHolder()->getAll();
        $data = array('success' => 0);
        $this->result = array();

        $user = Doctrine::getTable('sfGuardUser')->findOneByHash($route_params['user_id']);
        $currency = Doctrine::getTable('Currency')->findOneByAlphaId($route_params['currency']);

        $userHavePermissions = self::checkPermissions($route_params['asset'], $currency);
        if (!$userHavePermissions['success']) {
            $data = $userHavePermissions;
            return self::_return($data);
        }
        
        if(!$this->isAuthenticated($route_params['apikey'], $route_params['asset'])){
            $data['error'] = $this->getErrorFromList('api2016');
            return self::_return($data);
        }

        if (!$user) {
            $data['error'] = $this->getErrorFromList('api2001');
            return self::_return($data);
        }

        if (!$this->paramsAreOk(array('user_id', 'currency', 'asset', 'action_name'), $request)) {
            $data['error'] = $this->getErrorFromList('api2012');
            $data['error'] = $this->result['error']['message'];
            return self::_return($data);
        }

        // error cuando la moneda tiene is_super=1 ya que no hay un currency_asset asociado
        $ca = self::getCurrencyAsset($route_params['asset'], $currency);
        $action = Doctrine_Query::create()
                ->from('ValuePerAction vpa')
                ->where('vpa.action = ?', $route_params['action_name'])
                ->andWhere('currency_asset_id = ?', $ca->getId())
                ->fetchOne();

        if (!$action) {
            $data['error'] = $this->getErrorFromList('api2011');
            $data['error']['message'] = str_replace('%action%', $route_params['action_name'], $data['error']['message']);
            return self::_return($data);
        }

        $pocket = self::getPocket($currency->getId(), $user->getId());
        if (!$pocket) {
            $pocket = self::createPocket($currency->getId(), $user->getId(), $ca->getId());
        }

        $pocket->setBalance($pocket->getBalance() + $action->getValue());
        $pocket->save();

        $flow = new Flow();
        $flow->setDirection('income');
        $flow->setAmount($action->getValue());
        $flow->setBalance($pocket->getBalance());
        $flow->setAction($action);
        $flow->setUser($user);
        $flow->setPocket($pocket);
        $flow->setCurrency($currency);
        $flow->save();

        $data['user'] = $user->getHash();
        $data['currency'] = array("id" => $currency->getAlphaId(), "symbol" => $currency->getSymbol());
        $data['balance'] = $pocket->getBalance();
        $data['action'] = array("id" => $action->getAlphaId(), "name" => $action->getAction(), "description" => $action->getDescription());

        $data['success'] = 1;
        return self::_return($data);
    }

    public function  executeGetFlow(sfWebRequest $request) {
        $route_params = $request->getParameterHolder()->getAll();
        $data = array('success' => 0);

        $user = Doctrine::getTable('sfGuardUser')->findOneByHash($route_params['user_id']);
        $currency = Doctrine::getTable('Currency')->findOneByAlphaId($route_params['currency']);

        $userHavePermissions = self::checkPermissions($route_params['asset'], $currency);
        if (!$userHavePermissions['success']) {
            $data = $userHavePermissions;
            return self::_return($data);
        }
        
        if(!$this->isAuthenticated($route_params['apikey'], $route_params['asset'])){
            $data['error'] = $this->getErrorFromList('api2016');
            return self::_return($data);
        }

        if (!$user) {
            $data['error'] = $this->getErrorFromList('api2001');
            return self::_return($data);
        }

        $pocket = self::getPocket($currency->getId(), $user->getId());
        if (!$pocket) {
            $pocket = self::createPocket($currency->getId(), $user->getId());
        }

        $query = Doctrine_Query::create()
                ->from('Flow f')
                ->leftJoin('f.Action vpa')
                ->where('f.user_id = ?', $user->getId())
                ->andWhere('currency_id = ?', $currency->getId())
                ->andWhere('pocket_id = ?', $pocket->getId())
                ->orderBy('f.created_at DESC')
                ->limit(10)
                ->execute();

        $movements = array();
        foreach ($query as $move) {
            $movements[$move->getId()] = array('date' => $move->getCreatedAt(),
                'points' => $move->getAmount(),
                'direction' => $move->getDirection(),
                'balance' => $move->getBalance(),
                'action' => $move->getAction()->getAction());
        }

        $data['user'] = $user->getHash();
        $data['currency'] = array("id" => $currency->getAlphaId(), "symbol" => $currency->getSymbol());
        $data['flow'] = $movements;

        $data['success'] = 1;
        return self::_return($data);
    }

    public function executeCreateCurrency(sfWebRequest $request) {
        $route_params = $request->getParameterHolder()->getAll();
        $data = array('success' => 0);
        $this->result = array();

        $asset = Doctrine::getTable('Asset')->findOneByAlphaId($route_params['asset']);

        if (!$asset) {
            $data['error'] = $this->getErrorFromList('api2013');
            return self::_return($data);
        }
        
        if(!$this->isAuthenticated($route_params['apikey'], $route_params['asset'])){
            $data['error'] = $this->getErrorFromList('api2016');
            return self::_return($data);
        }

        if (!$this->paramsAreOk(array('name', 'symbol'), $request)) {
            $data['error'] = $this->getErrorFromList('api2012');
            $data['error'] = $this->result['error']['message'];
            return self::_return($data);
        }

        try {
            $currency = new Currency();
            $currency->setAlphaId(Util::GenSecret(10, 0));
            $currency->setName($route_params['name']);
            $currency->setSymbol($route_params['symbol']);
            $currency->save();
        } catch (Exception $exc) {
            $data['error'] = $this->getErrorFromList('api2000');
            return self::_return($data);
        }

        $ca = $this->createCurrencyAsset($currency, $asset);

        if (!$ca) {
            $data['error'] = $this->getErrorFromList('api2014');
            return self::_return($data);
        }

        $this->createAction('consume', '0', $ca);

        $data['currency'] = array("id" => $currency->getAlphaId(), "name" => $currency->getName(), "symbol" => $currency->getSymbol());

        $data['success'] = 1;
        return self::_return($data);
    }

    public function executeGetWallet(sfWebRequest $request) {
        $route_params = $request->getParameterHolder()->getAll();
        $data = array('success' => 0);

        $user = Doctrine::getTable('sfGuardUser')->findOneByHash($route_params['user_id']);

        if (!$user) {
            $data['error'] = $this->getErrorFromList('api2001');
            return self::_return($data);
        }

        $query = Doctrine_Query::create()
                ->from('Pocket p')
                ->leftJoin('p.Currency c')
                ->andWhere('p.user_id=?', $user->getId())
                ->execute();

        foreach ($query as $p) {
            $currency = $p->getCurrency();
            $userHavePermissions = self::checkPermissions($route_params['asset'], $currency);
            if ($userHavePermissions['success']) { //El API client tiene permiso para ver ese currency
                $data['wallet'][$currency->getAlphaId()] = array("name" => $currency->getName(), "symbol" => $currency->getSymbol(), "balance" => $p->getBalance());
            }
        }
        
        if(!$this->isAuthenticated($route_params['apikey'], $route_params['asset'])){
            $data['error'] = $this->getErrorFromList('api2016');
            return self::_return($data);
        }

        $data['success'] = 1;
        return self::_return($data);
    }

    public function executeCreateProduct(sfWebRequest $request) {
        $route_params = $request->getParameterHolder()->getAll();
        $data = array('success' => 0);
        $this->result = array();

        $asset = Doctrine::getTable('Asset')->findOneByAlphaId($route_params['asset']);

        if (!$asset) {
            $data['error'] = $this->getErrorFromList('api2013');
            return self::_return($data);
        }
        
        if(!$this->isAuthenticated($route_params['apikey'], $route_params['asset'])){
            $data['error'] = $this->getErrorFromList('api2016');
            return self::_return($data);
        }

        if (!$this->paramsAreOk(array('name', 'value'), $request)) {
            $data['error'] = $this->getErrorFromList('api2012');
            $data['error'] = $this->result['error']['message'];
            return self::_return($data);
        }

        try {
            $product = new PointsProduct();
            $product->setAlphaId(Util::GenSecret(10, 0));
            $product->setName($route_params['name']);
            $product->setValue($route_params['value']);
            if (array_key_exists('description', $route_params)) {
                $product->setDescription($route_params['description']);
            }
            $product->setAsset($asset);
            $product->save();
        } catch (Exception $exc) {
            $data['error'] = $this->getErrorFromList('api2000');
            return self::_return($data);
        }

        $data['product'] = array("id" => $product->getAlphaId(), "name" => $product->getName(), "value" => $product->getValue());
        if (array_key_exists('description', $route_params)) {
            $data['product']['description'] = $route_params['description'];
        }

        $data['success'] = 1;
        return self::_return($data);
    }

    public function executeAddAssetToCurrency(sfWebRequest $request) {
        $route_params = $request->getParameterHolder()->getAll();
        $data = array('success' => 0);

        $currency = Doctrine::getTable('Currency')->findOneByAlphaId($route_params['currency']);
        $asset = Doctrine::getTable('Asset')->findOneByAlphaId($route_params['asset']);

        if (!$asset) {
            $data['error'] = $this->getErrorFromList('api2013');
            return self::_return($data);
        }

        if(!$this->isAuthenticated($route_params['apikey'], $route_params['asset'])){
            $data['error'] = $this->getErrorFromList('api2016');
            return self::_return($data);
        }
        
        $ca = $this->createCurrencyAsset($currency, $asset);

        if (!$ca) {
            $data['error'] = $this->getErrorFromList('api2014');
            return self::_return($data);
        }

        $query = Doctrine_Query::create()
                ->from('CurrencyAsset')
                ->where('currency_id = ?', $currency->getId())
                ->execute();

        foreach ($query as $q) {
            $ass = $q->getAsset();
            $assets[$ass->getAlphaId()] = $ass->getName();
        }
        $data['currency'] = array($currency->getAlphaId() => $assets);

        $data['success'] = 1;
        return self::_return($data);
    }

    public function executeGetCurrencies(sfWebRequest $request) {
        $route_params = $request->getParameterHolder()->getAll();
        $data = array('success' => 0);

        $asset = Doctrine::getTable('Asset')->findOneByAlphaId($route_params['asset']);

        if (!$asset) {
            $data['error'] = $this->getErrorFromList('api2013');
            return self::_return($data);
        }
        
        if(!$this->isAuthenticated($route_params['apikey'], $route_params['asset'])){
            $data['error'] = $this->getErrorFromList('api2016');
            return self::_return($data);
        }

        $query = Doctrine_Query::create()
                ->from('CurrencyAsset')
                ->where('asset_id = ?', $asset->getId());
        $groups = $query->execute();

        $currencies = array();

        foreach ($groups as $member) {
            $currency = $member->getCurrency();
            array_push($currencies, array("id" => $currency->getAlphaId(), "name" => $currency->getName(), "symbol" => $currency->getSymbol()));
        }

        $data['groups'] = $currencies;

        $data['success'] = 1;
        return self::_return($data);
    }

    // Verifico si el establecimento tiene permisos sobre ese currency (si comparten el mismo CurrencyAsset)
    protected function checkPermissions($asset_alphaId, $currency) {
        $data = array('success' => 0);
        $data['error'] = $this->getErrorFromList('api2008');

        if($currency && $currency->getIsSuper()) // La moneda es para todos los establecimientos, nada que verificar
            return array('success' => 1);
        
        $asset = Doctrine::getTable('Asset')->findOneByAlphaId($asset_alphaId);
        if ($asset && $currency) {
            $currencyAsset = Doctrine_Query::create()
                    ->from('CurrencyAsset ca')
                    ->where('ca.currency_id=?', $currency->getId())
                    ->andWhere('ca.asset_id=?', $asset->getId());
            $currencyAsset = $currencyAsset->fetchOne();
            if ($currencyAsset) {
                return array('success' => 1);
            }
            else
                $data['error'] = $this->getErrorFromList('api2007');
        }
        return $data;
    }

    protected function getCurrencyAsset($asset_alphaId, $currency) {
        $asset = Doctrine::getTable('Asset')->findOneByAlphaId($asset_alphaId);
        if ($asset) {
            $currencyAsset = Doctrine_Query::create()
                    ->from('CurrencyAsset ca')
                    ->where('ca.currency_id=?', $currency->getId())
                    ->andWhere('ca.asset_id=?', $asset->getId());
            $currencyAsset = $currencyAsset->fetchOne();
            if (!$currencyAsset) {
                return false;
            }
        }
        else
            return false;

        return $currencyAsset;
    }

    // Funcion que dado un currency_id y un user_id devuelve el Doctrine_Object Pocket solicitado COMO UN ARRAY
    protected function getPocket($currency_id, $user_id) {
        $temp = Doctrine_Query::create()
                ->from('Pocket p')
                ->where('p.currency_id=?', $currency_id)
                ->andWhere('p.user_id=?', $user_id);
        $pocket = $temp->fetchOne();
        return $pocket;
    }

    // Siempre se habia pensado que el pocket era inherente al usuario, pero dado que ahora tenemos elBuho, esto no es cierto.
    protected function createPocket($currency_id, $user_id, $currencyAsset_id = NULL) {
        $pocket = new Pocket();
        $pocket->setBalance(0);
        $pocket->setIsSuper(0);
        $pocket->setCurrencyId($currency_id);
        $pocket->setUserId($user_id);
        $pocket->setCurrencyAssetId($currencyAsset_id);
        return $pocket;
    }

    protected function createCurrencyAsset($currency, $asset) {
        $ca = self::getCurrencyAsset($asset->getAlphaId(), $currency);
        if ($ca) {
            return false;
        }

        $ca = new CurrencyAsset();
        $ca->setCurrency($currency);
        $ca->setAsset($asset);
        $ca->save();

        return $ca;
    }

    protected function createAction($action_name, $value, $currencyAsset) {
        $action = new ValuePerAction();
        $action->setAction($action_name);
        $action->setValue($value);
        if ($currencyAsset)
            $action->setCurrencyAssetId($currencyAsset->getId());
        $action->setAlphaId(Util::GenSecret(10, 0));
        $action->save();

        return $action;
    }
    
    protected function isAuthenticated($apikey, $asset) {
        $asset = Doctrine::getTable('Asset')->findOneByAlphaId($asset);
        $apiClient = Doctrine::getTable('ApiUser')->findOneByName($asset->getSlug());
        return $apiClient->getApiKey() == $apikey;
    }

    protected function _return($data) {
        $this->getResponse()->setContentType('application/json');
        return $this->renderText(json_encode($data));
    }

}
