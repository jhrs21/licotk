<?php

/**
 * api actions.
 *
 * @package    elperro
 * @subpackage api
 * @author     Jacobo Martínez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */

class apiActions extends sfActions
{    
    public function executeCategories(sfWebRequest $request) {
        $this->result = array(); 
        
        $this->result['success'] = 0;
        
        $route_params = $this->getRoute()->getParameters();
        
        if(!$user = Doctrine::getTable('sfGuardUser')->findOneBy('alpha_id', $route_params['alpha_id']))
        {
            $this->result['error'] = 'Identificador de usuario inválido';
            
            return 'Error';
        }
        
        if(!$type = $request->getParameter('type',false))
        {
            $this->result['error'] = 'Tipo de categoria inválido';
            
            return 'Error';
        }
        
        if(!(strcasecmp($type, 'place') == 0) && !(strcasecmp($type, 'brand') == 0))
        {
            $this->result['error'] = 'Tipo de categoria inválido';
            
            return 'Error';
        }
        
        $this->result['success'] = 1;
            
        $this->result['categories'] = array();

        $categories = Doctrine::getTable('Category')->retrieveByCategoryType($type);

        foreach ($categories as $category) 
        {
            $this->result['categories'][$category->getAlphaId()] = $category->asArray();
        }
    }
    
    public function executePlacesLeading(sfWebRequest $request) {        
        $this->result = array(); 
        
        $this->result['success'] = 0;
        
        $route_params = $this->getRoute()->getParameters();
        
        if (!$user = Doctrine::getTable('sfGuardUser')->findOneBy('alpha_id', $route_params['alpha_id'])) {
            $this->result['error'] = 'Identificador de usuario inválido';
            
            return 'Error';
        }
        
        $affiliates = Doctrine::getTable('Affiliate')->retrieveWithActivePromos();

        $this->result['success'] = 1;

        $host = $request->getHost();

        foreach ($affiliates as $affiliate) {
            $this->result['affiliates'][$affiliate->getAlphaId()] = $affiliate->asArray(false, false, $host);
        }
    }
    
    public function executePlacesSearch(sfWebRequest $request) {        
        $this->result = array(); 
        
        $this->result['success'] = 0;        
        
        $route_params = $this->getRoute()->getParameters();
        
        if(!$user = Doctrine::getTable('sfGuardUser')->findOneBy('alpha_id', $route_params['alpha_id']))
        {
            $this->result['error'] = 'Identificador de usuario inválido';
            
            return 'Error';
        }
        
        $table = Doctrine::getTable('Affiliate');
        
        $query = $table->addHasAssetTypeQuery('place');

        if($categoryId = $request->getParameter('category', false))
        {
            if(!$category = Doctrine::getTable('Category')->findOneBy('alpha_id',$categoryId))
            {
                $this->result['error'] = 'Identificador de categoria inválido';
                
                return 'Error';
            }

            $query = $table->addByCategoryQuery($category->getId(),$query);
        }

        if($keyword = $request->getParameter('keyword', false))
        {
            $query = $table->addByKeywordQuery($keyword,$query);
        }
        
        if(($lat = $request->getParameter('lat',false)) && ($long = $request->getParameter('long', false)))
        {
            $query = $table->addWithAssetsInRangeQuery($lat, $long, sfConfig::get('app_mobile_app_search_distance_radius'), $query);
        }
        
        $pager = new sfDoctrinePager('Affiliate',sfConfig::get('app_mobile_app_max_affiliates_per_page'));

        $pager->setQuery($query);
        $pager->setPage($request->getParameter('page', 1));
        $pager->init();
        
        $affiliates = $pager->getResults();
        
        $this->result['success'] = 1;
        
        if($affiliates->count()){
            $host = $request->getHost();
            $this->result['affiliates'] = array();
            
            foreach ($affiliates as $affiliate) {
                $this->result['affiliates'][$affiliate->getAlphaId()] = $affiliate->asArray(false, false, $host);
            }
        }
    }
    
    public function executePlacesNearby(sfWebRequest $request) {        
        $this->result = array();
        $this->result['success'] = 0;        
        
        $route_params = $this->getRoute()->getParameters();
        
        if(!$user = Doctrine::getTable('sfGuardUser')->findOneBy('alpha_id', $route_params['alpha_id']))
        {
            $this->result['error'] = 'Identificador de usuario inválido';
            return 'Error';
        }
        
        $table = Doctrine::getTable('Asset');
        
        if(($lat = $request->getParameter('lat',false)) && ($long = $request->getParameter('long', false)))
        {
            $query = $table->addByDistanceQuery($lat, $long, $request->getParameter('distance', sfConfig::get('app_mobile_app_search_distance_radius')), true);
        }
        else 
        {
            $this->result['error'] = 'Faltan parámetros obligatorios para la busqueda por cercanía';
            return 'Error';
        }
        
        $query = $table->addByTypeQuery('place', $query);
        
        $query = $table->addByParticipationInActivePromosQuery($query);

        $this->result['success'] = 1;
        
        $places = $query->execute();
        
        if($places->count()){
            $this->result['places'] = array();
            foreach ($places as $place) {
                $this->result['places'][$place->getAlphaId()] = $place->asArray(); //$place->toArray();
            }
        }
    }
    
    public function executePlace(sfWebRequest $request) {
        $this->result = array(); 
        
        $this->result['success'] = 0;      
        
        $route_params = $this->getRoute()->getParameters();
        
        if(!$user = Doctrine::getTable('sfGuardUser')->findOneBy('alpha_id', $route_params['alpha_id']))
        {
            $this->result['error'] = 'Identificador de usuario inválido';
            
            return 'Error';
        }
        
        if(!$affiliate = Doctrine::getTable('Affiliate')->findOneByAlphaId($request->getParameter('affiliateid', false)))
        {
            $this->result['error'] = 'Identificador de affiliado inválido';
            
            return 'Error';
        }
        
        $params = array();

        $params['type'] = 'place';
        
        if($request->hasParameter('city'))
        {
            $params['city'] = $request->getParameter('city');
        }

        if($request->hasParameter('lat') && $request->hasParameter('long'))
        {
            $params['lat'] = $request->getParameter('lat');
            $params['long'] = $request->getParameter('long');
            
            $params['distance'] = $request->getParameter('distance', 0);
        }

        $this->result['success'] = 1;

        $this->result['affiliate'] = $affiliate->asArray($params, true, $request->getHost());
    }
    
    public function executeBrandsLeading(sfWebRequest $request) {        
        $this->result = array(); 
        
        $this->result['success'] = 0;        
        
        $route_params = $this->getRoute()->getParameters();
        
        if(!$user = Doctrine::getTable('sfGuardUser')->findOneBy('alpha_id', $route_params['alpha_id']))
        {
            $this->result['error'] = 'Identificador de usuario inválido';
            
            return 'Error';
        }
        
        $affiliates = Doctrine::getTable('Affiliate')->retrieveWithActivePromos('brand');

        $this->result['success'] = 1;

        foreach ($affiliates as $affiliate) {
            $brands = $affiliate->getBrands();
            foreach ($brands as $brand) {
                $this->result['brands'][$brand->getAlphaId()] = $brand->asArray();
            }
        }
    }
    
    public function executeBrandsSearch(sfWebRequest $request) {        
        $this->result = array(); 
        
        $this->result['success'] = 0;        
        
        $route_params = $this->getRoute()->getParameters();
        
        if(!$user = Doctrine::getTable('sfGuardUser')->findOneBy('alpha_id', $route_params['alpha_id']))
        {
            $this->result['error'] = 'Identificador de usuario inválido';
            
            return 'Error';
        }
        
        $table = Doctrine::getTable('Asset');
        
        $query = $table->addByTypeQuery('brand');

        if($categoryId = $request->getParameter('category', false))
        {
            if(!$category = Doctrine::getTable('Category')->findOneBy('alpha_id',$categoryId))
            {
                $this->result['error'] = 'Identificador de categoria inválido';
                
                return 'Error';
            }

            $query = $table->addByCategoryQuery($category->getId(),$query);
        }

        if($keyword = $request->getParameter('keyword', false))
        {
            $query = $table->addByKeywordQuery($keyword,$query);
        }
        
        $pager = new sfDoctrinePager('Asset',sfConfig::get('app_mobile_app_max_affiliates_per_page'));

        $pager->setQuery($query);
        $pager->setPage($request->getParameter('page', 1));
        $pager->init();
        
        $affiliates = $pager->getResults();

        $this->result['success'] = 1;

        $host = $request->getHost();
        
        $result = array();
        foreach ($affiliates as $affiliate) {
            $result[$affiliate->getAlphaId()] = $affiliate->asArray(false, false, $host);
        }
        
        if(count($result)){
            $this->result['affiliates'] = $result;
        }
    }
    
    public function executeBrand(sfWebRequest $request) {
        $this->result = array(); 
        
        $this->result['success'] = 0;      
        
        $route_params = $this->getRoute()->getParameters();
        
        if(!$user = Doctrine::getTable('sfGuardUser')->findOneBy('alpha_id', $route_params['alpha_id']))
        {
            $this->result['error'] = 'Identificador de usuario inválido';
            
            return 'Error';
        }
        
        $brand = Doctrine::getTable('Asset')->findOneByAlphaId($request->getParameter('brand', false));
        
        if(!$brand || !$brand->isBrand())
        {
            $this->result['error'] = 'Identificador de Marca inválido';
            
            return 'Error';
        }

        $this->result['success'] = 1;

        $this->result['brand'] = $brand->asArray(true);
    }
    
    public function executeUserStuff(sfWebRequest $request) {
        $this->result = array();
        
        $this->result['success'] = 0;
        
        $route_params = $this->getRoute()->getParameters();
        
        if(!$user = Doctrine::getTable('sfGuardUser')->findOneBy('alpha_id', $route_params['alpha_id']))
        {
            $this->result['error'] = 'Identificador de usuario inválido';
        }
        else
        {
            $this->result['success'] = 1;
            
            $this->result['pts_lt'] = (int) $user->getMainPocket()->getBalance();
            
            $cards = $user->getCards();

            foreach($cards as $card)
            {
                $this->result['cards'][$card->getAlphaId()] = $card->asArray(true, true, $request->getHost());
            }
        }
    }
    
    public function executeUserTicket(sfWebRequest $request) {
        $this->result = array();
        
        $this->result['success'] = 0;
        
        $route_params = $this->getRoute()->getParameters();
        
        if(!$user = Doctrine::getTable('sfGuardUser')->findOneByAlphaId($route_params['alpha_id'])){            
            $this->result['error'] = 'Identificador de usuario inválido';
            
            return 'Error';
        }
        
        $promocode = Doctrine::getTable('PromoCode')->retrievePromoCode($request->getParameter('promocode','empty'));

        if(!$promocode){
            $this->result['error'] = 'Código de promoción inválido.';
            
            return 'Error';
        }
        
        if(!$promocode->getPromo()->isActive()){
            $this->result['error'] = 'El periodo para acumular Tags en la promoción "'.$promocode->getPromo().'" ya ha terminado.';
            
            return 'Error';
        }
        
        if(!$promocode->isActive()){
            if($promocode->hasStatus('used')){
                $this->result['error'] = 'El código de promoción inidicado ya ha sido utilizado.';
            }
            else{
                $this->result['error'] = 'Código de promoción inválido';
            }
            
            return 'Error';
        }
        
        $promo = $promocode->getPromo();

        if($promo->getMaxUses() > 0 && $user->getCompleteParticipationsNumber($promo->getId()) == $promo->getMaxUses()){
            $this->result['error'] =    'La promoción "'.$promo.'" admite un máximo de '.
                                        ($promo->getMaxUses() == 1 ? 'una participación' : $promo->getMaxUses().' participaciones').
                                        ' por cliente y usted ya '.($promo->getMaxUses() == 1 ? 'la' : 'las').
                                        ' ha realizado. Si tiene un premio sin canjear aún puede canjearlo.';
            return 'Error';
        }

        if($promo->getMaxDailyTags() > 0 && $user->countTodayTickets($promo->getId()) == $promo->getMaxDailyTags()){
            $this->result['error'] =    'La promoción "'.$promo.'" le permite acumular un máximo de '.
                                        $promo->getMaxDailyTags().' Tag(s) por día y ya lo(s) has alcanzado.';
            return 'Error';
        }
        
        $needs_validation = $promocode->hasType('validation_required');
        
        if($needs_validation){
            $validationcode = Doctrine::getTable('ValidationCode')->retrieveValidationCode($request->getParameter('vcode','NA'),$promocode->getId());

            if(!$validationcode || !$validationcode->getActive()){                        
                $this->result['error'] = 'Código de Activación inválido';
                return 'Error';
            }
            else if($validationcode->getUsed()){                        
                $this->result['error'] = 'El Código de Activación ya ha sido utilizado.';
                return 'Error';
            }
            
            $validationcode->setUsed(true);
            $validationcode->setUser($user);
        }

        $ticket = new Ticket();

        $ticket->setUser($user);        
        $ticket->setPromo($promo);
        $ticket->setPromoCode($promocode);
        $ticket->setCache($request->getParameter('cache',0));

        if($request->hasParameter('lat') && $request->hasParameter('long')){
            /*CUANDO SE DEFINA HACE FALTA METER LA VALIDACIÓN EN FUNCIÓN DE LA UBICACIÓN*/
            $ticket->setLatitude($request->getParameter('lat'));
            $ticket->setLongitude($request->getParameter('long'));
        }

        if(!$card = $user->hasActiveCard($promo->getId())){
            $card = new Card();
            $card->setUser($user);
            $card->setPromo($promo);
            $card->setStatus('active');
        }

        $ticket->setCard($card);

        if($card->hasReachedTheLimit()){
            $card->setStatus('complete');
            $card->setCompletedAt(date(DateTime::W3C));
            
            //// FUNCION DE TD
            if($card->getPromo()->getAffiliate()->getSlug()=="tudescuenton"){
                $card->setStatus('redeemed');
                $prize = Doctrine::getTable('PromoPrize')->
                        findOneByPromoId($promo->getId());
                
                $coupon = new Coupon();
                $coupon->setUser($user);
                $coupon->setPromo($promo);
                $coupon->setPrize($prize);
                $coupon->setCard($card);
                $coupon->setStatus('used');
                $coupon->setExpiresAt($promo->getExpiresAt());

                $coupon->save();
                //TE PREMIO
                $values=array();
                $values['hash']=$user->getHash();
                $values['email']=$user->getEmailAddress();
                $values['promo']=$promo->getId();
                $values['premio']=$prize->getId();
                $tdApi = new epTDApi(); 
                $result = $tdApi->tdSendPrize($values);
                
            }
            //// FIN FUNCION DE TD
        }

        if($needs_validation){
            $validationcode->save();
            $ticket->setValidationCode($validationcode);
        }

        $card->save();

        $ticket->save();

        if($promocode->hasType('single_use'))
        {
            $promocode->setStatus('used');
            $promocode->setUsedAt(date(DateTime::W3C));

            $promocode->save();
        }

        if (!$subscription = $user->isSubscribed($promo->getAffiliateId()))
        {
            $subscription = new Subscription();
            $subscription->setUser($user);
            $subscription->setAffiliateId($promo->getAffiliateId());
            $subscription->setAssetId($promocode->getAssetId());
        }
        else
        {
            if(!$subscription = $user->isSubscribed($promocode->getAssetId(),"asset"))
            {
                $subscription->setAssetId($promocode->getAssetId());
            }
        }

        $subscription->setStatus('active');
        $subscription->setLastInteraction(date(DateTime::W3C));

        $subscription->save();
        
        $pts = $this->awardPoints('tag', $user);

        $this->result['success'] = 1;

        $this->result['pts_lt'] = (int) $user->getMainPocket()->getBalance();
        
        $this->result['message'] = '¡Hemos registrado tu Tag exitosamente!.|Has sido premiado con '.$pts.' ptos LT por realizar un Tag.|¿Qué tal ha sido tu experiencia';
        
        $this->result['message'] = $this->result['message'].' '.
                                    ($promocode->getAsset()->isPlace() ? 'en' : 'con').' '.$promocode->getAsset().'?';

        $this->result['card'] = $card->asArray(true, true, $request->getHost(), true);
    }
    
    public function executeUserAcquire(sfWebRequest $request) {
        $this->result = array();
        
        $this->result['success'] = 0;
        
        $route_params = $this->getRoute()->getParameters();
        
        if(!$user = Doctrine::getTable('sfGuardUser')->findOneByAlphaId($route_params['alpha_id']))
        {
            $this->result['error'] = 'Identificador de usuario inválido';
            return 'Error';
        }
        
        if(!$promo = Doctrine::getTable('Promo')->findOneByAlphaId($request->getParameter('promoid','empty')))
        {
            $this->result['error'] = 'Identificador de promoción inválido';
            return 'Error';
        }
        
        if(!$card = Doctrine::getTable('Card')->
                findOneByAlphaIdAndUserIdAndPromoId($request->getParameter('cardid','empty'), $user->getId(), $promo->getId()))
        {
            $this->result['error'] = 'Identificador de tarjeta inválido';
            return 'Error';
        }
        
        if(!$prize = Doctrine::getTable('PromoPrize')->
                findOneByAlphaIdAndPromoId($request->getParameter('conditionid','empty'),$promo->getId()))
        {
            $this->result['error'] = 'Identificador de premio inválido';
            return 'Error';
        }
        
        if($prize->runOut())
        {
            $this->result['error'] = 'Se ha agotado la existencia del premio que has solicitado';
            return 'Error';
        }
            
        if($prize->getThreshold() > $card->getTickets()->count()){
            $this->result['error'] = 'Aún no has acumulado suficientes Tags para reclamar este Premio.';
            return 'Error';
        }
        
        if(!$coupon = Doctrine::getTable('Coupon')->findOneByCardId($card->getId()))
        {
            $card->setStatus('exchanged');

            if($prize->getThreshold() < $card->getTickets()->count())
            {
                $newcard = new Card();

                $newcard->setUser($user);
                $newcard->setPromo($promo);
                $newcard->setStatus('active');

                $i = 0;

                foreach($card->getTickets() as $key => $ticket) 
                {
                    if($prize->getThreshold() > $i)
                    {
                        $ticket->setUsed(true);
                        $ticket->setUsedAt(date(DateTime::W3C));

                        $card->getTickets()->add($ticket, $key);

                        $i++;
                    }
                    else
                    {
                        $newcard->getTickets()->add($ticket);
                    }
                }

                $newcard->save();

                $this->result['newcard'] = $newcard->asArray(true, true, $request->getHost());
            } 

            $card->save();

            $coupon = new Coupon();

            $coupon->setUser($user);
            $coupon->setPromo($promo);
            $coupon->setPrize($prize);
            $coupon->setCard($card);
            $coupon->setStatus('active');
            $coupon->setExpiresAt($promo->getExpiresAt());

            $coupon->save();
            
            $prize->setDelivered($prize->getDelivered() + 1);
            $prize->save();

            $this->result['card'] = $card->asArray();
        }

        $this->result['success'] = 1;

        $this->result['coupon'] = $card->getCoupon()->asArray();
    }
    
    public function executeUserLogin(sfWebRequest $request) {
        $this->result = array();
        
        $this->result['success'] = 0;
        
        if(!$request->hasParameter('email') || !$request->hasParameter('password'))
        {
            $this->result['error'] = 'Faltan parámetros obligatorios (email y/o contraseña)';
        
            return 'Error';
        }
        
        $email = $request->getParameter('email');
        
        $password = $request->getParameter('password');
        
        $buhoValues = array('email' => $email, 'password' => $password);
        
        $buho = new epBuhoApi();
        
        $result = $buho->buhoLogin($buhoValues);
        
        if(!$result['success']){
            if(array_key_exists('u9906',$result['user'])){
                if($user = Doctrine::getTable('sfGuardUser')->findOneBy('email_address', $email))
                {            
                    $this->result['success'] = 1;
                    $this->result['user'] = $user->asArray();
                    
                    return 'Success';
                }
                
                $this->result['error'] = 'Aún no has verificado tu cuenta de TuDescuenton.com, debes verificarla antes de poder usarla para ingresar a LealTag.';
                return 'Error';
            }
            else if(array_key_exists('u0000',$result['user'])){
                $this->result['error'] = 'Email y/o contraseña invalido.';
                return 'Error';
            }
            
            $this->result['error'] = 'Ha ocurrido un error al tratar de iniciar tu sesión. Por favor, contactanos a la dirección soporte@lealtag.com';
            return 'Error';
        }

        if(!$user = Doctrine::getTable('sfGuardUser')->findOneBy('email_address', $email))
        {
            $user = $this->createUser(array('form_data' => $buhoValues, 'buho_data' => $result['user']));
        }
        else
        {
            $user = $this->updateUser(array('form_data' => $buhoValues, 'buho_data' => $result['user']), $user);
        }
        
        $this->result['success'] = 1;
        $this->result['user'] = $user->asArray();
    }
    
    public function executeUserSignin(sfWebRequest $request) {
        $this->result = array();
        
        $this->result['success'] = 0;
        
        if(!$request->hasParameter('email') || !$request->hasParameter('password')
            || !$request->hasParameter('gender') || !$request->hasParameter('birthdate')
            || !$request->hasParameter('first_name') || !$request->hasParameter('last_name')
        )
        {
            $this->result['error'] = 'Falta información obligatoria.';
        }
        else
        {
            $values = array();
            
            $values['first_name'] = $request->getParameter('first_name');
            $values['last_name'] = $request->getParameter('last_name');
            $values['email'] = $request->getParameter('email');
            $values['password'] = $request->getParameter('password');
            $values['gender'] = $request->getParameter('gender');
            $values['birthdate'] = $request->getParameter('birthdate');
            
            $form = new epMobileUserApplyForm(array(), array(), false);
            
            $form->bind($values);
            
            if ($form->isValid()) 
            {
                $values = $form->getValues();
                
                $buhoValues = array();
                $buhoValues['full_name'] = $values['first_name'].' '.$values['last_name'];
                $buhoValues['email'] = $values['email'];
                $buhoValues['password'] = $values['password'];
                $buhoValues['birthday'] = $values['birthdate'];
                $buhoValues['identifier'] = $values['id_number'];
                
                $buho = new epBuhoApi();
                
                $result = $buho->buhoCreateUser($buhoValues);
                
                //  Verificar si el usuario es registrado en El Buho sin inconvenientes
                if($result['success'])
                {   //  Registrar el nuevo usuario de forma local y 
                    //  asignar el hash retornado por El Buho
                    $guid = "n" . $result['user']['validator'];
                    $form->setValidate($guid);
                    $form->setUserHash($result['user']['hash']);
                    $form->save();
                    try 
                    {
                        $profile = $form->getObject();
                        $this->sendVerificationMail($profile);
                        $this->result['success'] = 1;
                        $this->result['user'] = $profile->getUser()->asArray();
                    } 
                    catch (Exception $e) 
                    {
                        $profile = $form->getObject();
                        $user = $profile->getUser();
                        $user->delete();

                        $this->result['error'] = 'Error al enviar correo de verificación. Por favor, intenta nuevamente más tarde.';
                    }
                }
                else
                {
                    if($result['user']['u0205']){
                        $this->result['error'] = 'Ya tienes una cuenta creada en TuDescuenton.com, utiliza el mismo email y contraseña para ingresar a LealTag.';
                        return 'Error';
                    }
                    
                    $this->result['error'] = 'Ha ocurrido un error al tratar de crear tu cuenta. Por favor, contactanos a la dirección soporte@lealtag.com';

                    return 'Error';
                }
            }
            else
            {
                $errors = array();
                
                foreach ($form->getErrorSchema()->getErrors() as $key => $error) {
                    $errors .= $key.': '.$error->getMessage().' ';
                }
                
                $this->result['error'] = $errors;
            }
        }
    }
    
    public function executeUserUpdate(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;
        
        $route_params = $this->getRoute()->getParameters();
        
        if(!$user = Doctrine::getTable('sfGuardUser')->findOneByAlphaId($route_params['alpha_id'])){
            $this->result['error'] = 'Identificador de usuario inválido';
            return 'Error';
        }
        
        if(!$user->checkPassword($request->getParameter('password',''))){
            $this->result['error'] = 'Contraseña incorrecta';
            return 'Error';
        }
        
        $values = array();
        
        $values['id'] = $user->getUserProfile()->getId();
        $values['first_name'] = $request->getParameter('first_name');
        $values['last_name'] = $request->getParameter('last_name');
        $values['gender'] = $request->getParameter('gender');
        $values['birthdate'] = $request->getParameter('birthdate');
        $values['id_number'] = $request->getParameter('id_number');
        
        $formOptions = array();
        
        if($request->getParameter('npassword',false)){
           $values['password'] = $request->getParameter('npassword');
           $formOptions['update_password'] = true;
        }
        
        $form = new epMobileUserApplyForm($user->getUserProfile(), $formOptions, false);

        $form->bind($values);
        if ($form->isValid()) 
        {
            $formValues = $form->getValues();
                
            $buhoValues = array();
            $buhoValues['hash'] = $user->getHash();
            $buhoValues['email'] = $user->getEmailAddress();
            $buhoValues['full_name'] = $formValues['first_name'].' '.$formValues['last_name'];
            $buhoValues['identifier'] = array_key_exists('id_number', $formValues) ? (is_null($formValues['id_number']) ? '' : $formValues['id_number']) : '';
            $buhoValues['mobile_phone'] = array_key_exists('phone', $formValues) ? (is_null($formValues['phone']) ? '' : $formValues['phone']) : '';
            $buhoValues['birthday'] = $formValues['birthdate'];

            $buho = new epBuhoApi();

            $result = $buho->buhoUpdateUser($buhoValues);

            if(!$result['success']){
                if(array_key_exists('u9906',$result['user'])){
                    $this->result['error'] = 'Aún no has verificado tu cuenta, verificala para pode modificar tus datos.';
                    return 'Error';
                }
                
                $this->result['error'] = 'Ha ocurrido un error al tratar de actualizar tus datos. Por favor, contactanos a la dirección soporte@lealtag.com';
                return 'Error';
            }
            
            if(array_key_exists('password', $formValues)){
                $result = $buho->buhoResetPassword($buhoValues);

                if(!$result['success']){
                    $this->result['error'] = 'Ha ocurrido un error en la solicitud para modificar tu contraseña. Por favor, contactanos a la dirección soporte@lealtag.com';
                    return 'Error';
                }
                
                $buhoValues['validator'] = $result['user']['validator'];
                $buhoValues['new_password'] = $formValues['password'];
                
                $result = $buho->buhoUpdatePassword($buhoValues);
                
                if(!$result['success']){
                    $this->result['error'] = 'Ha ocurrido un error al tratar de modificar tu contraseña. Por favor, contactanos a la dirección soporte@lealtag.com';
                    return 'Error';
                }
            }
            
            $form->save();

            $profile = $form->getObject();
            
            $this->result['success'] = 1;

            $this->result['user'] = $profile->getUser()->asArray();
        }
        else
        {
            $errors = array();

            foreach ($form->getErrorSchema()->getErrors() as $key => $error) {
                $errors[$key] = $error->getMessage();
            }

            $this->result['error'] = $errors;
            
            return 'Error';
        }
    }
    
    public function executeUserFeedback(sfWebRequest $request) {
        $this->result = array();
        
        $this->result['success'] = 0;
        
        $route_params = $this->getRoute()->getParameters();
        
        if(!$user = Doctrine::getTable('sfGuardUser')->findOneByAlphaId($route_params['alpha_id']))
        {
            $this->result['error'] = 'Identificador de usuario inválido';
            
            return 'Error';
        }
        
        $valoration = $request->getParameter('valoration', false);
        
        if(($valoration === false) || ($valoration < 0  || $valoration > 2))
        {
            $this->result['error'] = 'Parámetro de valoración inválido';
            
            return 'Error';
        }
        
        $message = $request->getParameter('msg', false);
        
        if($message && strlen($message) > 255){
            $this->result['error'] = 'Tu mensaje es muy largo, el máximo de caracteres para el mensaje es de 255 caracteres.';
            
            return 'Error';
        }
        
        $action = $request->getParameter('after_action', false);
        
        if(($action === false) || (strcasecmp($action, 'tag') != 0 && strcasecmp($action, 'redeem') != 0)){            
            $this->result['error'] = 'Parámetro de acción inválido';
            
            return 'Error';
        }
        
        $feedback = new Feedback();
        
        if(strcasecmp($action, 'tag') == 0)
        {
            $promocode = Doctrine::getTable('PromoCode')->retrievePromoCode($request->getParameter('promo',false));
            
            if(!$promocode)
            {
                $this->result['error'] = 'Código de promoción inválido.';

                return 'Error';
            }
            
            $feedback->setAssetId($promocode->getAssetId());
            
            $promo = $promocode->getPromo();
            
            $this->result['message'] = 'Hemos registrado exitosamente tu opinión sobre: '.$promocode->getAsset().'.';
        }
        else
        {
            $promo = Doctrine::getTable('Promo')->findOneBy('alpha_id',$request->getParameter('promo','empty'));
            
            if(!$promo)
            {
                $this->result['error'] = 'Identificador de promoción inválido.';

                return 'Error';
            }
            
            $this->result['message'] = 'Hemos registrado exitosamente tu opinión.';
        }
        
        $feedback->setUserId($user->getId());
        $feedback->setValoration($valoration);
        $feedback->setMessage($message);
        $feedback->setAction($action);
        $feedback->setAffiliateId($promo->getAffiliateId());
        $feedback->setPromoId($promo->getId());
        
        $feedback->save();
        
        $pts = $this->awardPoints('feedback', $user);
        
        $this->result['success'] = 1;
        
        $this->result['message'] = $this->result['message'].'|Has sido premiado con '.$pts.' ptos LT por compartir tu opinión.';
        
        $this->result['pts_lt'] = (int) $user->getMainPocket()->getBalance();
    }

    public function executeGetPromoCode(sfWebRequest $request) {
        require(sfConfig::get('sf_lib_dir').'/vendor/phpqrcode/qrlib.php');
        
        $this->result = array();
        $this->result['success'] = 0;
        
        $route_params = $this->getRoute()->getParameters();
        
        $apikey = $route_params['apikey'];
        $name = $route_params['name'];
        $asset_alpha_id = $route_params['asset_alpha_id'];
        $promo_alpha_id = $route_params['promo_alpha_id'];
        
        if(!self::checkApikey($apikey,$name)){
            $this->result['error'] = "No coinciden usuarios";
            return;
        }
        try{
            $promo_code=Doctrine_Query::create()
                ->from('PromoCode pc')
                ->leftJoin('pc.Promo p')
                ->leftJoin('pc.Asset a')
                ->where('p.alpha_id=?',$promo_alpha_id)
                ->andWhere('a.alpha_id=?',$asset_alpha_id)
                ->andWhere('pc.status="active"')
                ->andWhere('pc.type="validation_required"')
                ->execute();
            
            $theOne = $promo_code->getFirst();
            $validation_code = new ValidationCode();
            $validation_code->setActive(true);
            $validation_code->setPromoCodeId($promo_code[0]->getId());
            $validation_code->setCode(Util::GenSecret(5, 0));
            $validation_code->setSerial(Util::GenSecret(5, 1));
            $validation_code->save();
            $suffix = '/qr-pc/'.$theOne->getSerial().'-'.$validation_code->getSerial().'.png';
            $filename = sfConfig::get('sf_web_dir').$suffix;
            $data = "http://www.lealtag.com/".$theOne->getAlphaId()."?vcode=".$validation_code->getCode();
            $errorCorrectionLevel = "L";
            $matrixPointSize = "8";
            ob_end_clean();
            QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize,2,true);
            $this->result['promo_code']="http://".$request->getHost().$suffix;
            $this->result['validation_code']=$validation_code->getCode();
            $this->result['success'] = 1;
        }catch(Exception $e){
            if ($e->getCode()==23000)   // error code when duplicating primary key
            {
                 $this->result['error']="error con la BD";
                 return;
            }     
            else 
            {
                throw $e;
            }
        }
    }
    
    public function executeGetPromoCodeTest(sfWebRequest $request) {
        require(sfConfig::get('sf_lib_dir').'/vendor/phpqrcode/qrlib.php');
        
        $this->result = array();
        $this->result['success'] = 0;
        
        $route_params = $this->getRoute()->getParameters();
        
        $apikey = $route_params['apikey'];
        $name = $route_params['name'];
        $asset_alpha_id=$route_params['asset_alpha_id'];
        $promo_alpha_id=$route_params['promo_alpha_id'];
        
        if(!self::checkApikey($apikey,$name)){
            $this->result['error'] = "No coinciden usuarios";
            return;
        }
            $suffix = '/qr-pc/TEST-TEST.png';
            $filename = sfConfig::get('sf_web_dir').$suffix;
            $data = "http://www.lealtag.com/1234567890VC?vcode=123RT";
            $errorCorrectionLevel = "L";
            $matrixPointSize = "8";
            ob_end_clean();
            QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize,2,true);
            $this->result['promo_code']="http://".$request->getHost().$suffix;
            $this->result['validation_code']="123RT";
            $this->result['success'] = 1;
    }
    
    public function executeGetPromos(sfWebRequest $request) {
        $this->result = array();
        
        $this->result['success'] = 0;
        
        $route_params = $this->getRoute()->getParameters();
        
        $apikey = $route_params['apikey'];
        $name = $route_params['name'];
        $affiliate_alpha_id=$route_params['affiliate_alpha_id'];
        if(!self::checkApikey($apikey,$name)){
            $this->result['error'] = "No coinciden";
            return;
        }
        
        $promos=Doctrine_Query::create()
                ->from('Promo p')
                ->leftJoin('p.Affiliate a')
                ->where('a.alpha_id=?',$affiliate_alpha_id)
                ->execute();
        
        $promo_array = array();
        foreach($promos as $promo){
            array_push($promo_array,$promo->getName());
        }
        $this->result['promos']=$promo_array;
    }
    
    public function executeGetCodes(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;
        
        $route_params = $this->getRoute()->getParameters();
        
        $apikey         = $route_params['apikey'];
        $name           = $route_params['name'];
        $asset_alpha_id = $route_params['asset'];
        $promo_alpha_id = $route_params['promo'];
        $quantity       = $request->getParameter('quantity',10);
        
        if(!self::checkApikey($apikey,$name)){
            $this->result['error'] = array(
                'message' => 'Error de autenticación de usuario del API.',
                'type' => 'ApiError',
                'code' => 'api000'
            );
            return 'Error';
        }
        
        $promo_code = Doctrine_Query::create()
                ->from('PromoCode pc')
                ->leftJoin('pc.Promo p')
                ->leftJoin('pc.Asset a')
                ->where('p.alpha_id=?', $promo_alpha_id)
                ->andWhere('a.alpha_id=?', $asset_alpha_id)
                ->andWhere('pc.digital=1')
                ->andWhere('pc.type="validation_required"')
                ->fetchOne();
        
        if(!$promo_code){
            $this->result['error'] = array(
                'message' => 'El comercio no esta asociado a la promoción indicada o la promoción es ináctiva.',
                'type' => 'PromoCodeError',
                'code' => 'pc000'
            );
            return 'Error';
        }
        
        $this->result['promo_code'] = $promo_code->getAlphaId();
        
        $this->result['vcodes'] = array();
        
        $collection = new Doctrine_Collection('ValidationCode');
        
        for($i = 0; $i < $quantity; $i++){
            $validation_code = new ValidationCode();
            $validation_code->setActive(true);
            $validation_code->setPromoCodeId($promo_code->getId());
            $collection->add($validation_code);
        }
        
        $collection->save();
        
        foreach ($collection as $validation_code) {
            $this->result['vcodes'][] = $validation_code->getCode();
        }
        
        $this->result['success'] = 1;
    }
    
    public function executeGetAsset(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;
        
        $route_params = $this->getRoute()->getParameters();
        
        if(!$this->validateApiRules($route_params['apikey'],$route_params['name'])){
            return 'Error';
        }
        
        if(!$asset = $this->validateAsset($route_params['asset'])){
            return 'Error';
        }
        
        $this->result['asset'] = $asset->asArray(true);
        
        $this->result['success'] = 1;
    }
    
    public function executeCheckInTag(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;
        
        $tagSource = 'tablet';
        
        $route_params = $this->getRoute()->getParameters();
        
        if(!$this->validateApiRules($route_params['apikey'],$route_params['name'])){
            return 'Error';
        }
        
        if(!$asset = $this->validateAsset($route_params['asset'])){
            return 'Error';
        }
        
        $email = $request->getParameter('email',false);
        $membershipCardId = $request->getParameter('mcard',false);
        
        if(!$membershipCardId && !$email){
            $this->result['error'] = array(
                'message' => 'Faltan parametros requeridos para procesar la petición (email o mcard)',
                'type' => 'ApiError',
                'code' => 'api001'
            );
            return 'Error';
        }
        else if($membershipCardId){
            if(!$user = $this->validateMembershipCardForTag($membershipCardId, $email, $asset->getId())){
                return 'Error';
            }
            $tagSource = 'tablet_card';
        }
        else{
            if(!$user = $this->handleUserCheckInByEmail($email)){
                return 'Error';
            }
            $tagSource = 'tablet_email';
        }
        
        $asset = $this->validateAsset($route_params['asset']);
        
        if(!$result = $this->validateTag($user, $route_params['promocode'], $route_params['vcode'], $asset)){
            return 'Error';
        }
        
        $card = $this->registerTag($user, $result['promo'], $result['promocode'], $result['vcode']);
        
        $this->result['card'] = $card->asArray(true, false, $request->getHost(), true);
        
        $this->result['user'] = $user->asArray();
        
        $this->result['success'] = 1;
    }
    
    public function executeCheckInStuff(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;
        
        $route_params = $this->getRoute()->getParameters();
        
        if (!$this->validateApiRules($route_params['apikey'],$route_params['name'])) {
            return 'Error';
        }
        
        if (!$asset = $this->validateAsset($route_params['asset'])) {
            return 'Error';
        }
        
        $email = $request->getParameter('email',false);
        $mCardId = $request->getParameter('mcard',false);
        
        if (!$mCardId && !$email) {
            $this->result['error'] = array(
                'message' => 'Faltan parametros requeridos para procesar la petición (email o mcard)',
                'type' => 'ApiError',
                'code' => 'api001'
            );
            return 'Error';
        }
        else if ($mCardId) {
            if (!$mCard = $this->validateMembershipCard($mCardId)) {
                return 'Error';
            }
            
            $user = $mCard->getUser();
        }
        else{
            if(!$user = Doctrine::getTable('sfGuardUser')->findOneBy('email_address', $email)){
                $this->result['error'] = array(
                    'message' => 'Correo electrónico inválido',
                    'type' => 'UserError',
                    'code' => 'ue001'
                );
                return 'Error';
            }
        }
        
        $this->result['success'] = 1;
            
        //$this->result['pts_lt'] = (int) $user->getMainPocket()->getBalance();
        $this->result['user'] = $user->asArray();

        $cards = $user->getCardsRelatedTo($asset->getId());

        foreach ($cards as $card) {
            $this->result['cards'][$card->getAlphaId()] = $card->asArray(true, false, $request->getHost());
        }
    }
    
    public function executeCheckInRedeem(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;
        
        $route_params = $this->getRoute()->getParameters();
        
        if (!$this->validateApiRules($route_params['apikey'],$route_params['name'])) {
            return 'Error';
        }
        
        if (!$asset = $this->validateAsset($route_params['asset'])) {
            return 'Error';
        }
        
        $userId = $request->getParameter('user',false);
        $cardId = $request->getParameter('card',false);
        $prizeId = $request->getParameter('prize',false);
        $serial = $request->getParameter('serial',false);
        $password = $request->getParameter('password',false);
        
        if ($cardId && $prizeId && $userId) {
            if (!$user = Doctrine::getTable('sfGuardUser')->findOneBy('alpha_id', $userId)) {
                $this->result['error'] = array(
                    'message' => 'Identificador de usuario inválido.',
                    'type' => 'UserError',
                    'code' => 'ue000'
                );
                return 'Error';
            }
            
            if (!$result = $this->validateAcquiringCoupon($user->getId(), $cardId, $prizeId)) {
                return 'Error';
            }
            
            $result = $this->registerCoupon($user, $result['card'], $result['promo'], $result['prize'], $asset);
            
            $coupon = $result['coupon'];
        }
        else if ($serial && $password) {
            if (!$coupon = $this->validateCoupon($serial, $password, $asset)) {
               return 'Error'; 
            }
        }
        else {
            $this->result['error'] = array(
                'message' => 'Faltan parametros requeridos para procesar la petición',
                'type' => 'ApiError',
                'code' => 'api001'
            );
            return 'Error';
        }
        
        $coupon = $this->redeemCoupon($coupon);
        
        $this->result['message'] = '¡Tu premio ha sido canjeado exitosamente!';
        
        $this->result['prize'] = $coupon->getPrize()->getPrize();
        
        $this->result['success'] = 1;
        
    }
    
    public function executeReplaceMembershipCard(sfWebRequest $request) {
        $this->result = array();
        $this->result['success'] = 0;
        
        $route_params = $this->getRoute()->getParameters();
        
        if(!$user = Doctrine::getTable('sfGuardUser')->findOneBy('alpha_id', $route_params['user'])){
            $this->result['error'] = array(
                'message' => 'Identificador de usuario inválido',
                'type' => 'UserError',
                'code' => 'ue000'
            );
            return 'Error';
        }
        
        if(!$membershipCard = $this->validateMembershipCard($route_params['mcard'])){
            return 'Error';
        }
        
        if($membershipCard->hasStatus('active')){
            $this->result['error'] = array(
                'message' => 'Intento de reemplazar Carnet por uno ya activo.',
                'type' => 'MembershipCardError',
                'code' => 'mc002'
            );
            return 'Error';
        }
        
        if(!$asset = $this->validateAsset($route_params['asset'])){
            return 'Error';
        }
        
        $membershipCard = $this->manageUserMembershipCard($user, $membershipCard, $asset, true);
        
        $this->result['success'] = 1;
    }
    
    protected function validateMembershipCard($membershipCardId) {
        if(!$membershipCard = Doctrine::getTable('MembershipCard')->findOneBy('alpha_id', $membershipCardId)){
            $this->result['error'] = array(
                'message' => 'Carnet Inválido.',
                'type' => 'MembershipCardError',
                'code' => 'mc000'
            );
            return false;
        }

        if($membershipCard->hasStatus('inactive')){
            $this->result['error'] = array(
                'message' => 'Carnet Inválido.',
                'type' => 'MembershipCardError',
                'code' => 'mc001'
            );
            return false;
        }

        return $membershipCard;
    }
    
    protected function validateMembershipCardForTag($membershipCardId, $email = false, $asset = false) {
        if(!$membershipCard = $this->validateMembershipCard($membershipCardId)){
            return false;
        }

         if($membershipCard->hasStatus('active')){
            $user = $membershipCard->getUser();          
        }
        else if($membershipCard->hasStatus('unassigned')){
            if(!$email){
                $this->result['error'] = array(
                    'message' => 'No se ha indicado el correo electrónico del usuario al que vincular el carnet.',
                    'type' => 'MembershipCardError',
                    'code' => 'mc002'
                );
                return false;
            }
            else {
                if(!$user = $this->handleUserCheckInByEmail($email)){
                    return false;
                }
            }
            
            $membershipCard = $this->manageUserMembershipCard($user, $membershipCard, $asset);
        }

        return $user;
    }
    
    protected function validateTag(sfGuardUser $user, $promocode = '', $vcode = '', $asset = false) {
        if(!$promocode = Doctrine::getTable('PromoCode')->retrievePromoCode($promocode)){
            $this->result['error'] = array(
                'message' => 'Código de promoción inválido.',
                'type' => 'PromoCodeError',
                'code' => 'pc000'
            );
            return false;
        }
        
        if($asset){
            if($promocode->getAssetId() != $asset->getId()){
                $this->result['error'] = array(
                    'message' => 'Código de promoción no corresponde al establecimiento.',
                    'type' => 'PromoCodeError',
                    'code' => 'pc001'
                );
                return false;
            }
        }
        
        if(!$promocode->isActive()){
            $this->result['error'] = array(
                'message' => 'Código de promoción inactivo.',
                'type' => 'PromoCodeError',
                'code' => 'pc002'
            );
            return false;
        }
        
        if(!$promocode->getPromo()->isActive()){
            $this->result['error'] = array(
                'message' => 'El periodo para acumular Tags en la promoción "'.$promocode->getPromo().'" ya ha terminado.',
                'type' => 'PromoError',
                'code' => 'pro001'
            );
            return false;
        }
        
        $promo = $promocode->getPromo();

        if($promo->getMaxUses() > 0 && $user->getCompleteParticipationsNumber($promo->getId()) == $promo->getMaxUses()){
            $this->result['error'] = array(
                'message' => 'La promoción "'.$promo.'" admite un máximo de '.
                            ($promo->getMaxUses() == 1 ? 'una participación' : $promo->getMaxUses().' participaciones').
                            ' por cliente y ya '.($promo->getMaxUses() == 1 ? 'la' : 'las').
                            ' has realizado. Si tiene un premio sin canjear aún puede canjearlo.',
                'type' => 'PromoError',
                'code' => 'pro002'
            );
            return false;
        }

        if($promo->getMaxDailyTags() > 0 && $user->countTodayTickets($promo->getId()) == $promo->getMaxDailyTags()){
            $this->result['error'] = array(
                'message' => 'La promoción "'.$promo.'" le permite acumular un máximo de '.
                                $promo->getMaxDailyTags().' Tag(s) por día y ya lo(s) has alcanzado.',
                'type' => 'PromoError',
                'code' => 'pro002'
            );
            return false;
        }
        
        $validationcode = null;
        
        if($promocode->hasType('validation_required')){
            $validationcode = Doctrine::getTable('ValidationCode')->retrieveValidationCode($vcode,$promocode->getId());
            if(!$validationcode || !$validationcode->getActive() || $validationcode->getUsed()){
                $this->result['error'] = array(
                    'message' => 'Código de Activación inválido',
                    'type' => 'ValidationCodeError',
                    'code' => 'vc000'
                );
                return false;
            }
            
            $validationcode->setUsed(true);
            $validationcode->setUser($user);
            $validationcode->save();
        }
        
        return array('promo' => $promo, 'promocode' => $promocode, 'vcode' => $validationcode);
    }
    
    protected function validateAcquiringCoupon($userId, $cardId, $prizeId) {
        if (!$card = Doctrine::getTable('Card')->findOneByAlphaIdAndUserId($cardId, $userId)) {
            $this->result['error'] = array(
                'message' => 'Identificador de tarjeta inválido.',
                'type' => 'CardError',
                'code' => 'ce000'
            );
            return false;
        }
        
        if ($card->hasStatus('exchanged') || $card->hasStatus('redeemed') || $card->hasStatus('canceled') || $card->hasStatus('expired')) {
            $this->result['error'] = array(
                'message' => 'El premio ya ha sido canjeado o ha expirado',
                'type' => 'CardError',
                'code' => 'ce001'
            );
            return false;
        }
        
        if (!$prize = Doctrine::getTable('PromoPrize')->findOneByAlphaId($prizeId)) {
            $this->result['error'] = array(
                'message' => 'Identificador de premio inválido.',
                'type' => 'PrizeError',
                'code' => 'pe000'
            );
            return false;
        }
        
        $promo = $card->getPromo();
        
        if ($promo->getId() != $prize->getPromoId()) {
            $this->result['error'] = array(
                'message' => 'El premio no corresponde a la promoción asociada a la tarjeta.',
                'type' => 'RedeemError',
                'code' => 're001'
            );
            return false;
        }
        
        if (!$promo->redeemPeriodStarted()) {
            $this->result['error'] = array(
                'message' => 'El periodo de canje de la promoción aún no ha iniciado.',
                'type' => 'RedeemError',
                'code' => 're002'
            );
            return false;
        }
        
        if ($promo->isExpired()) {
            $this->result['error'] = array(
                'message' => 'El periodo de canje de la promoción ya ha finalizado.',
                'type' => 'RedeemError',
                'code' => 're003'
            );
            return false;
        }
        
        if ($prize->runOut()) {
            $this->result['error'] = array(
                'message' => 'Se ha agotado la existencia del premio solicitado.',
                'type' => 'PrizeError',
                'code' => 'pe002'
            );
            return false;
        }
            
        if ($prize->getThreshold() > $card->getTickets()->count()) {
            $this->result['error'] = array(
                'message' => 'Aún no has acumulado suficientes Tags para reclamar este Premio.',
                'type' => 'RedeemError',
                'code' => 're004'
            );
            return false;
        }
        
        return array('card' => $card, 'promo' => $promo, 'prize' => $prize);
    }
    
    protected function validateCoupon($serial,$password, Asset $asset = null) {
        if (!$coupon = Doctrine::getTable('Coupon')->findOneBy('serial',$serial)) {
            $this->result['error'] = array(
                'message' => 'Serial de premio inválido',
                'type' => 'CouponError',
                'code' => 'coe000'
            );
            return false;
        }
        
        if(!is_null($asset)){
            if (!in_array($asset->getId(), $coupon->getPromo()->getAssets()->getPrimaryKeys())) {
                $this->result['error'] = array(
                    'message' => 'El premio no puede ser canjeado en este establecimiento',
                    'type' => 'CouponError',
                    'code' => 'coe001'
                );
                return false;
            }
        }
        
        if($coupon->hasStatus('used')) {
            $this->result['error'] = array(
                'message' => 'El premio ya ha sido canjeado',
                'type' => 'CouponError',
                'code' => 'coe002'
            );
            return false;
        }
        
        if ($coupon->isExpired()) {
            $this->result['error'] = array(
                'message' => 'El premio ya ha expirado',
                'type' => 'CouponError',
                'code' => 'coe003'
            );
            return false;
        }
        
        if (!$coupon->getPromo()->redeemPeriodStarted()) {
            $this->result['error'] = array(
                'message' => 'Aún no ha comenzado el periodo de canje para este premio',
                'type' => 'CouponError',
                'code' => 'coe004'
            );
            return false;
        }
        
        // password is ok?
        if ($coupon->checkPassword($password)) {
            return $coupon;
        }
        
        $this->result['error'] = array(
            'message' => 'El password del premio es inválido',
            'type' => 'CouponError',
            'code' => 'coe005'
        );
        return false;
    }
    
    protected function redeemCoupon(Coupon $coupon, Asset $asset = null) {
        $coupon->setStatus('used');
        $coupon->getCard()->setStatus('redeemed');
        
        if (!is_null($asset)) {
            $coupon->setRedeemedAt($asset);
        }
        
        $coupon->save();
        
        return $coupon;
    }

    protected function validateAsset($asset) {
        if(!$asset = Doctrine::getTable('Asset')->findOneBy('alpha_id', $asset)){
            $this->result['error'] = array(
                'message' => 'Identificador Inválido.',
                'type' => 'AssetError',
                'code' => 'as000'
            );
        }
        return $asset;
    }

    protected function registerTag(sfGuardUser $user, Promo $promo, PromoCode $pcode, ValidationCode $vcode = null, $source = 'other', $cache = false) {
        $promo = $pcode->getPromo();

        $ticket = new Ticket();

        $ticket->setUser($user);
        $ticket->setPromo($promo);
        $ticket->setPromoCode($pcode);
        $ticket->setCache($cache);
        $ticket->setVia($source);
        
        if(!is_null($vcode)){
            $ticket->setValidationCode($vcode);
            $vcode->setUsed(true);
            $vcode->setUser($user);
        }

        if (!$card = $user->hasActiveCard($promo->getId())) {
            $card = new Card();
            $card->setUser($user);
            $card->setPromo($promo);
            $card->setStatus('active');
        }

        $ticket->setCard($card);

        if ($card->hasReachedTheLimit()){
            $card->setStatus('complete');
        }

        $card->save();

        $ticket->save();
        
        $pts = $this->awardPoints('tag', $user);
        
        $this->manageSubscription($user, $promo->getAffiliateId(), $pcode->getAssetId());
        
        return $card;
    }
    
    protected function registerCoupon(sfGuardUser $user, Card $card, Promo $promo, PromoPrize $prize) {
        $card->setStatus('exchanged');
        
        $newcard = false;

        if($prize->getThreshold() < $card->getTickets()->count())
        {
            $newcard = new Card();

            $newcard->setUser($user);
            $newcard->setPromo($promo);
            $newcard->setStatus('active');

            $i = 0;

            foreach($card->getTickets() as $key => $ticket) 
            {
                if($prize->getThreshold() > $i)
                {
                    $ticket->setUsed(true);
                    $ticket->setUsedAt(date(DateTime::W3C));

                    $card->getTickets()->add($ticket, $key);

                    $i++;
                }
                else
                {
                    $newcard->getTickets()->add($ticket);
                }
            }

            $newcard->save();
        } 

        $card->save();

        $coupon = new Coupon();

        $coupon->setUser($user);
        $coupon->setPromo($promo);
        $coupon->setPrize($prize);
        $coupon->setCard($card);
        $coupon->setStatus('active');
        $coupon->setExpiresAt($promo->getExpiresAt());

        $coupon->save();

        $prize->setDelivered($prize->getDelivered() + 1);
        $prize->save();
        
        return array('coupon' => $coupon, 'card' => $card, 'newcard' => $newcard);
        
    }
    
    protected function validateUserByEmail($email, $buhoCheck = false) {
        if (!preg_match(sfValidatorEmail::REGEX_EMAIL, $email)) {
            $this->result['error'] = array(
                'message' => 'Correo electrónico inválido.',
                'type' => 'UserError',
                'code' => 'ue001'
            );
            return false;
        }
        
        if (!$user = Doctrine::getTable('sfGuardUser')->findOneBy('email_address', $email)) {
            if ($buhoCheck) {
                $buho = new epBuhoApi();
                $result = $buho->buhoGetUser(array('email' => $email));

                if (!$result['success']) {
                        $this->result['error'] = array(
                        'message' => 'Correo electrónico no registrado.',
                        'type' => 'UserError',
                        'code' => 'ue002'
                    );
                    return false;
                }
                else {
                    $user = $this->createUserViaCheckIn($result['user']);
                }
            }
            else {
               $this->result['error'] = array(
                    'message' => 'Correo electrónico no registrado.',
                    'type' => 'UserError',
                    'code' => 'ue002'
                );
                return false; 
            }
        }
        
        return $user;
    }

    protected function manageSubscription(sfGuardUser $user, $affiliate, $asset) {
        $subscription = $user->isSubscribedTo(array('affiliate'=>$affiliate,'asset'=>$asset));
        
        if (!$subscription)
        {
            if(!$subscription = $user->isSubscribedTo(array('affiliate'=>$affiliate),'affiliate')){
                $subscription = new Subscription();
                $subscription->setUser($user);
                $subscription->setAffiliateId($affiliate);
                $subscription->setAssetId($asset);
            }
            else{
                if(is_null($subscription->getAssetId())){
                    $subscription->setAssetId($asset);
                }
            }
        }

        $subscription->setStatus('active');
        $subscription->setLastInteraction(date(DateTime::W3C));

        $subscription->save();
    }
    
    protected function manageUserMembershipCard(sfGuardUser $user, MembershipCard $membershipCard, $asset, $replace = false) {
        $mcard = $user->getMembershipCard();
        
        if(!$mcard || $replace){
            $membershipCard->setStatus('active');
            $membershipCard->setUser($user);
            $membershipCard->setAssetId($asset);
            $membershipCard->setValidate(self::createGuid());
            $membershipCard->save();

            if($replace && $mcard){
                $mcard->setStatus('inactive');
                $mcard->save();
            }
            
            try{
                $this->sendMembershipCardVerificationMail($user, $membershipCard, $replace);
            }
            catch (Exception $e){
                $this->result['error'] = array(
                    'message' => 'La tarjeta se asignó exitosamente, pero ha ocurrido un error al intentar enviar correo electrónico de validación de tarjeta al usuario.',
                    'type' => 'MailerError',
                    'code' => 'me000'
                );
                return false;
            }
            
            $this->result['mcard'] = array(
                    'id' => $membershipCard->getAlphaId(),
                    'status' => $membershipCard->getStatus(),
                    'assigned' => 1,
                    'message' => 'Sea asignado el carnet '.$membershipCard->getAlphaId().' a tu cuenta, pronto recibirás un correo electrónico que te permitirá activarlo.'
                );
        }
        else{
            $this->result['mcard'] = array(
                    'id' => $membershipCard->getAlphaId(),
                    'status' => $membershipCard->getStatus(),
                    'assigned' => 0,
                    'message' => 'El carnet '.$mcard->getAlphaId().
                                ' esta asociado a tu cuenta, para reemplezarlo por el carnet '.
                                $membershipCard->getAlphaId().' confirma a continuación.'
                );
        }
        
        return $membershipCard;
    }
    
    protected function handleUserCheckInByEmail($email) {
        if(!preg_match(sfValidatorEmail::REGEX_EMAIL, $email)){
            $this->result['error'] = array(
                'message' => 'Correo electrónico inválido.',
                'type' => 'MembershipCardError',
                'code' => 'mc003'
            );
            return false;
        }
        
        if(!$user = Doctrine::getTable('sfGuardUser')->findOneBy('email_address', $email)){
            $buho = new epBuhoApi();

            $result = $buho->buhoGetUser(array('email' => $email));

            if(!$result['success']){
                $user = new sfGuardUser();
                $user->setEmailAddress($email);
                $user->save();
                
                $profile = new UserProfile();
                $profile->setEmail($email);
                $profile->setValidate('n'.self::createGuid());
                $profile->setUser($user);
                $profile->save();
                
                try {
                    $this->sendUserEmailOnlyVerificationMail($profile);
                    return $user;
                } 
                catch (Exception $e) {
                    $user->delete();
                    $this->result['error'] = array(
                        'message' => 'Error al intentar enviar correo electrónico de verificación al usuario.',
                        'type' => 'MailerError',
                        'code' => 'me000'
                    );
                    return false;
                }
            }
            else{
                $user = $this->createUserViaCheckIn($result['user']);
            }
        }
        
        return $user;
    }

    protected function awardPoints($action, sfGuardUser $user) {
        $mainVpa = Doctrine::getTable('ValuePerAction')->retrieveByAction($action);
        $mainCurrency = Doctrine::getTable('Currency')->retrieveMain();
        $mainPocket = $user->getMainPocket();
        
        $this->registerFlow('income', $action, $user, $mainVpa, $mainCurrency, $mainPocket);
        
        return $mainVpa->getValue();
    }
    
    protected function registerFlow($direction, $description, sfGuardUser $user, ValuePerAction $vpa, Currency $currency, Pocket $pocket) {
        $flow = new Flow();
        
        $flow->setDirection($direction);
        $flow->setDescription($description);
        $flow->setUser($user);
        $flow->setAction($vpa);
        $flow->setCurrency($currency);
        $flow->setPocket($pocket);
        $flow->setAmount($vpa->getValue());
        
        $balance = strcasecmp('income', $direction) == 0 ? $pocket->getBalance() + $vpa->getValue() : $pocket->getBalance() - $vpa->getValue();
        
        $flow->setBalance($balance);
        
        $flow->save();
    }
    
    protected function updateUser($data, sfGuardUser $user) {
        $user->setHash($data['buho_data']['hash']);
        $user->setPassword($data['form_data']['password']);
        $user->getUserProfile()->setFullname($data['buho_data']['full_name']);
        $user->getUserProfile()->setBirthdate(array_key_exists('birthday', $data['buho_data']) ? $data['buho_data']['birthday'] : null);
        $user->getUserProfile()->setIdNumber(array_key_exists('identifier', $data['buho_data']) ? $data['buho_data']['identifier'] : null);
        $user->getUserProfile()->setPhone(array_key_exists('mobile_phone', $data['buho_data']) ? $data['buho_data']['mobile_phone'] : null);
        
        $user->save();
        
        return $user;
    }
    
    protected function createUser($data) {        
        $user = new sfGuardUser();
        $user->setIsActive(true);
        $user->addGroupByName('licoteca_users');
        $user->setEmailAddress($data['form_data']['email']);
        $user->setPassword($data['form_data']['password']);
        $user->setHash($data['buho_data']['hash']);
        $user->save();
        
        $profile = new UserProfile();
        $profile->setUser($user);
        $profile->setEmail($data['form_data']['email']);
        $profile->setFullname($data['buho_data']['full_name']);
        
        if(array_key_exists('birthday', $data['buho_data'])) {
            $profile->setBirthdate($data['buho_data']['birthday']);
        }
        if(array_key_exists('identifier', $data['buho_data'])) {
            $profile->setIdNumber($data['buho_data']['identifier']);
        }
        if(array_key_exists('mobile_phone', $data['buho_data'])) {
            $profile->setPhone($data['buho_data']['mobile_phone']);
        }
        
        $profile->save();
        
        try {
            $this->sendWelcomeMail($profile);

            return $user;
        } 
        catch (Exception $e) {
            return 'MailerError';
        }
    }
    
    protected function createUserViaCheckIn($data) {
        $user = new sfGuardUser();
        $user->setIsActive(true);
        $user->addGroupByName('licoteca_users');
        $user->setEmailAddress($data['email']);
        if (array_key_exists('password', $data)) {
            $user->setPassword($data['password'], true);
        }
        $user->setSalt($data['salt']);
        $user->setAlgorithm('sha1');
        $user->setHash($data['hash']);
        $user->save();
        
        $profile = new UserProfile();
        $profile->setUser($user);
        $profile->setEmail($data['email']);
        $profile->setFullname($data['full_name']);
        
        if(!$data['verified']){
            $profile->setValidate('n'.$data['validator']);
        }
        
        if(array_key_exists('birthday', $data)) {
            $profile->setBirthdate($data['birthday']);
        }
        if(array_key_exists('identifier', $data)) {
            $profile->setIdNumber($data['identifier']);
        }
        if(array_key_exists('mobile_phone', $data)) {
            $profile->setPhone($data['mobile_phone']);
        }
        if(array_key_exists('gender', $data)) {
            $profile->setGender($data['gender']);
        }
        
        $profile->save();
        
        try {
            if(!$data['verified']){
                $this->sendVerificationMail($profile);
            }
            else{
                $this->sendWelcomeMail($profile);
            }
            
            return $user;
        } 
        catch (Exception $e) {
            if(!$data['verified']){
                $user->delete();
                $this->result['error'] = array(
                    'message' => 'Error al intentar enviar correo electrónico de verificación al usuario.',
                    'type' => 'MailerError',
                    'code' => 'me000'
                );
            }
            else{
                $this->result['error'] = array(
                    'message' => 'Error al intentar enviar correo electrónico de bienvenida al usuario.',
                    'type' => 'MailerError',
                    'code' => 'me000'
                );
            }
            
            return false;
        }
    }
    
    protected function sendWelcomeMail($profile) {
        $this->mail(array(
                'subject' => sfContext::getInstance()->getI18N()->__('Bienvenido a LealTag'),
                'fullname' => $profile->getFullname(),
                'email' => $profile->getEmail(),
                'parameters' => array(
                    'fullname' => $profile->getFullname()
                ),
                'text' => 'email/sendWelcomeText',
                'html' => 'email/sendWelcome'
            ));
    }
        
    protected function sendVerificationMail($profile) {
        $route = $this->getContext()->getRouting()->generate('validate', array('validate' => $profile->getValidate()), true);
        
        $this->mail(array(
                'subject' => sfContext::getInstance()->getI18N()->__('Bienvenido a LealTag - Verifica tu cuenta'),
                'fullname' => $profile->getFullname(),
                'email' => $profile->getEmail(),
                'parameters' => array(
                        'fullname' => $profile->getFullname(),
                        'route1' => $route,
                        'name' => $profile->getUser()->getFirstName(),
                        'gender' => $profile->getGender()
                ),
                'text' => 'email/sendValidateNewText',
                'html' => 'email/sendValidateNew'
            ));
    }
    
    protected function sendUserEmailOnlyVerificationMail($profile) {
        $route = $this->getContext()->getRouting()->generate('validate', array('validate' => $profile->getValidate()), true);
        
        $this->mail(array(
                'subject' => sfContext::getInstance()->getI18N()->__('Bienvenido a LealTag - Verifica tu cuenta y completa tus datos'),
                'fullname' => $profile->getFullname(),
                'email' => $profile->getEmail(),
                'parameters' => array(
                        'fullname' => $profile->getFullname(), 
                        'route1' => $route,
                        'name' => $profile->getUser()->getFirstName(),
                        'gender' => $profile->getGender()
                ),
                'text' => 'email/sendValidateNewText',
                'html' => 'email/sendValidateNew'
            ));
    }
    
    protected function sendMembershipCardVerificationMail(sfGuardUser $user, MembershipCard $mcard, $replace = false) {        
        $this->mail(array(
                'subject' => sfContext::getInstance()->getI18N()->__('¡Valida tu Tarjeta de LealTag!'),
                'fullname' => $user->getUserProfile()->getFullname(),
                'email' => $user->getUserProfile()->getEmail(),
                'parameters' => array(
                        'user' => $user->getAlphaId(),
                        'fullname' => $user->getUserProfile()->getFullname(), 
                        'validate' => $mcard->getValidate(),
                        'mcard' => $mcard->getAlphaId(),
                        'replace' => $replace,
                    ),
                'text' => 'sendValidateMembershipCardText',
                'html' => 'sendValidateMembershipCard'
            ));
    }
    
    protected function mail($options) {
        $required = array('subject', 'parameters', 'email', 'html', 'text');
        
        foreach ($required as $option) {
            if (!isset($options[$option])) {
                throw new sfException("Required option $option not supplied to sfApply::mail");
            }
        }
        $message = $this->getMailer()->compose();
        $message->setSubject($options['subject']);

        // Render message parts
        $message->setBody($this->getPartial($options['html'], $options['parameters']), 'text/html');
        $message->addPart($this->getPartial($options['text'], $options['parameters']), 'text/plain');
        $address = $this->getFromAddress();
        $message->setFrom(array($address['email'] => $address['fullname']));
        $message->setTo(array($options['email'] => array_key_exists('fullname', $options) ? $options['fullname'] : $options['email']));
        $this->getMailer()->send($message);
    }

    static protected function createGuid() {
        $guid = "";
        
        for ($i = 0; ($i < 8); $i++) 
        {
            $guid .= sprintf("%02x", mt_rand(0, 255));
        }
        
        return $guid;
    }
    
    protected function getFromAddress() {
        $from = sfConfig::get('app_sfApplyPlugin_from', false);
        
        if (!$from)
        {
            throw new Exception('app_sfApplyPlugin_from is not set');
        }
        // i18n the full name
        return array('email' => $from['email'], 'fullname' => sfContext::getInstance()->getI18N()->__($from['fullname']));
    }
    
    protected function validateApiRules($apikey, $name) {
        if(!self::checkApikey($apikey,$name)){
            $this->result['error'] = array(
                'message' => 'Error de autenticación de usuario del API.',
                'type' => 'ApiError',
                'code' => 'api000'
            );
            return false;
        }
        
        return true;
    }

    static protected function checkApikey($apikey, $name) {
        if(!$apiUser = Doctrine::getTable('ApiUser')->findOneByName($name)){
            return false;
        }
        
        $key = sha1($apiUser->getSalt().$apikey);
        
        return strcmp($apiUser->getApikey(), $key) == 0;
        
    }
}
