<?php

class epValidatorGenerateCoupon extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addRequiredOption('user');
        $this->addRequiredOption('card');

        $this->setMessage('invalid', 'Error generando el cupon');
        $this->addMessage('invalid_prize', 'Identificador de premio inválido');
        $this->addMessage('diff_users', 'Usted no tiene permitido generar este cupon');
        $this->addMessage('expired_promo', 'Promoción expirada');
        $this->addMessage('invalid_promo', 'Promoción inválida');
        $this->addMessage('insufficient', 'Aún no has acumulado suficientes Tags para reclamar este Premio.');
        $this->addMessage('invalid_params', 'Faltan parámetros obligatorios para procesar la petición');
        $this->addMessage('invalid_api_credentials', 'Error de autenticación de credenciales del API');
        $this->addMessage('runout', 'Se ha agotado la existencia del premio que has solicitado');
        $this->addMessage('timeout', 'No se ha recibido respuesta del servidor de "%affiliate%" en el tiempo esperado, intenta nuevamente más tarde');
        $this->addMessage('connection', 'Ha ocurrido un inconveniente al tratar de comunicarnos con el servidor de "%affiliate%". Por favor, intenta nuevamente más tarde');
        $this->addMessage('prize_runout', 'Se ha agotado la existencia del premio (%prize%) que has solicitado en "%affiliate%"');
        $this->addMessage('prize_not_found', 'No se ha encontrado el premio (%prize%) que has solicitado en "%affiliate%"');
        $this->addMessage('email_bad_format', 'Tu correo electrónico (%email%) no tiene el formato adecuado');
        $this->addMessage('email_not_found', 'Tu correo electrónico (%email%) no está registrado en el servidor de "%affiliate%"');
    }

    protected function doClean($values) {
        $prize_id = isset($values['prize']) ? $values['prize'] : '';
        
        if (!$prize_id) {
            throw new sfValidatorError($this, 'invalid_prize');
        }

        $user = $this->getOption('user');
        $card = $this->getOption('card');

        if ($user->getId() != $card->getUserId()) {
            throw new sfValidatorError($this, 'diff_users');
        }

        $promo = $card->getPromo();

        $date = strtotime(date("Y-m-d H:i:s"));
        if ($date > (strtotime($promo->getExpiresAt()) + 1)) {
            throw new sfValidatorError($this, 'expired_promo');
        }

        if ($date < strtotime($promo->getBeginsAt())) {
            throw new sfValidatorError($this, 'invalid_promo');
        }

        if (!$prize = Doctrine::getTable('PromoPrize')->findOneBy('alpha_id', $prize_id)) {
            throw new sfValidatorError($this, 'invalid_prize');
        }

        if ($prize->getThreshold() > $card->getTickets()->count()) {
            throw new sfValidatorError($this, 'insufficient');
        }
        
        if ($prize->runOut()) {
            throw new sfValidatorError($this, 'runout');
        }
        
        if ($promo->getRedeemAutomated()) {
            $this->doAutomaticRedeem($user, $promo, $prize, $card);
        }

        return array_merge($values, array('user' => $user, 'card' => $card, 'prize' => $prize, 'promo' => $promo));
    }
    
    protected function doAutomaticRedeem(sfGuardUser $user, Promo $promo, PromoPrize $prize, Card $card) {
        $redeemer = new epRedeemer($promo->getRedeemerConfig());
        $result = $redeemer->redeem($user, $prize, $card->getAlphaId());
        
        if (!$result['success']) {
            /* Si el premio ya fue canjeado (código de error 6) no retornar error, la razón para que esto pueda suceder es el timeout en una llamada previa. */
            if (strcasecmp($result['error']['type'], 'RedeemError') == 0 && strcasecmp($result['error']['code'], '006') == 0) {
                return true;
            }
            
            $this->throwAutomaticRedeemError($result['error'], $user, $promo, $prize);
        }
        
        return true;
    }
    
    protected function throwAutomaticRedeemError(array $error, sfGuardUser $user, Promo $promo, PromoPrize $prize) {
        if (strcasecmp($error['type'], 'cUrlError') == 0) {
            switch ($error['code']) {
                case 28:
                    throw new sfValidatorError($this, 'timeout', array('affiliate' => $promo->getAffiliate()->getName()));
                    break;
                default:
                    throw new sfValidatorError($this, 'connection', array('affiliate' => $promo->getAffiliate()->getName()));
            }
        }
        
        if (strcasecmp($error['type'], 'RedeemError') == 0) {
            switch ($error['code']) {
                case '000':
                    throw new sfValidatorError($this, 'invalid_params', array('email' => $user->getEmail()));
                    break;
                case '001':
                    throw new sfValidatorError($this, 'invalid_api_credentials', array('email' => $user->getEmail()));
                    break;
                case '002':
                    throw new sfValidatorError($this, 'email_bad_format', array('email' => $user->getEmail()));
                    break;
                case '003':
                    throw new sfValidatorError($this, 'email_not_found', array('email' => $user->getEmail(), 'affiliate' => $promo->getAffiliate()->getName()));
                    break;
                case '004':
                    throw new sfValidatorError($this, 'prize_runout', array('prize' => $prize->getAlphaId(), 'affiliate' => $promo->getAffiliate()->getName()));
                    break;
                case '005':
                    throw new sfValidatorError($this, 'prize_not_found', array('prize' => $prize->getPrize().' ('.$prize->getAlphaId().')', 'affiliate' => $promo->getAffiliate()->getName()));
                    break;
                case '006':
                    //No mostrar error si la respuesta es que el premio ya ha sido canjeado
                    break;
                default:
                    $apiError = $this->getErrorFromList('api1100');
                    $search = array('%affiliate%');
                    $replace = array($promo->getAffiliate()->getName());
                    $apiError['message'] = str_replace($search, $replace, $apiError['message']);
                    break;
                case 2:
                    throw new sfValidatorError($this, 'email_bad_format', array('email' => $user->getEmail()));
                    break;
                case 3:
                    throw new sfValidatorError($this, 'email_not_found', array('email' => $user->getEmail(), 'affiliate' => $promo->getAffiliate()->getName()));
                    break;
                case 4:
                    throw new sfValidatorError($this, 'prize_runout', array('prize' => $prize->getAlphaId(), 'affiliate' => $promo->getAffiliate()->getName()));
                    break;
                case 5:
                    throw new sfValidatorError($this, 'prize_not_found', array('prize' => $prize->getPrize().' ('.$prize->getAlphaId().')', 'affiliate' => $promo->getAffiliate()->getName()));
                    break;
                case 6:
                    /*No mostrar error si la respuesta es que el premio ya ha sido canjeado*/
                    break;
                default:
                    throw new sfValidatorError($this, 'connection', array('affiliate' => $promo->getAffiliate()->getName()));
            }
        }
        
        throw new sfValidatorError($this, 'connection', array('affiliate' => $promo->getAffiliate()->getName()));
    }
}