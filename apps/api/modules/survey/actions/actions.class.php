<?php

/**
 * survey actions.
 *
 * @package    elperro
 * @subpackage survey
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class surveyActions extends baseEpApiActions {

    /**
     * Executes surveyApplication action
     *
     * @param sfRequest $request A request object
     */
    public function executeSurveyApplication(sfWebRequest $request) {
        $this->getResponse()->setContentType('application/json');
        
        $this->result = array('success' => 0);
        
        $route_params = $this->getRoute()->getParameters();
        $apiCredentials = array('apikey' => $route_params['apikey'], 'name' => $route_params['name']);
        $requiredParams = array('act','user','promo');

        if (!$this->validateApiRules($apiCredentials, $requiredParams, $request)) {
            return $this->renderText(json_encode($this->result));
        }

        if (!$asset = $this->validateAsset($route_params['asset'])) {
            return $this->renderText(json_encode($this->result));
        }
        
        if (!$promo = $this->validatePromo($request->getParameter('promo'))) {
            return $this->renderText(json_encode($this->result));
        }
        
        if (!$user = $this->validateUserByAlphaId($request->getParameter('user'))) {
            return $this->renderText(json_encode($this->result));
        }
        
        if (!$act = $request->getParameter('act', false)) {
            return $this->renderText(json_encode($this->result));
        }
        
        $surveysForms = new epSurveysForm(
                null, 
                array(
                    'user'                  => $user,
                    'asset'                 => $asset,
                    'surveys'               => $this->getSurveys($promo, $asset), 
                    'via'                   => 'app_tablet',
                    'action'                => $act,
                    'usesAlphaId'           => true,
                    'disableCSRFProtection' => true,
            ));
        
//        $surveysForms = new epSurveysForm(
//                null, 
//                array(
//                    'user'                  => $user,
//                    'asset'                 => $asset,
//                    'surveys'               => Doctrine::getTable('Survey')->findBy('alpha_id', sfConfig::get('app_mastercard_survey_id', 'id_cableado')),
//                    'via'                   => 'app_tablet',
//                    'action'                => $act,
//                    'usesAlphaId'           => true,
//                    'disableCSRFProtection' => true,
//            ));
        
        $surveysForms->bind($request->getParameter($surveysForms->getName()));
        
        if ($surveysForms->isValid()) {
            $values = $surveysForms->getValues();
            
            $surveysForms->save();
            
            $points = $this->awardPoints('feedback', $user);
            
            $this->result['message'] = '¡Gracias por participar en nuestra encuesta! Por completar la encuesta has sido premiado con '.$points.' puntos LT';
            
            /**
             *  Este cable es para definir el mensaje si el usuario elige la opción afirmativa en la encuesta de mastercard
             * 
            if (isset($values[sfConfig::get('app_mastercard_survey_id')]['items'][sfConfig::get('app_mastercard_item_id')]['answer'])) {
                if (strcasecmp(
                        $values[sfConfig::get('app_mastercard_survey_id')]['items'][sfConfig::get('app_mastercard_item_id')]['answer'],
                        sfConfig::get('app_mastercard_affirmative_option_id')
                        ) == 0
                    ) {
                    $this->result['message'] = 'No olvides guardar el voucher original de tu compra y revisa tu email para más instrucciones. ¡Vamos a vivirlo juntos!';
                }
                else {
                    $this->result['message'] = 'Sigue acumulando "Tags" en tu tarjeta LealTag';
                }
            }
            else {
                $this->result['message'] = '¡Gracias por participar en nuestra encuesta! Por completar la encuesta has sido premiado con '.$points.' puntos LT';
            }
             */
        }
        else {
            $this->result['error'] = $this->getErrorFromList('api1300');
            
            $errors = $surveysForms->getErrorSchema();
            
            $this->result['error']['errors'] = $this->errorDrillDown($errors);
            
            return $this->renderText(json_encode($this->result));
        }
        
        $this->result['success'] = 1;
        
        return $this->renderText(json_encode($this->result));
    }
    
    protected function errorDrillDown(sfValidatorError $error) {
        $errorFormat = array();
        
        if ($error instanceof sfValidatorErrorSchema) {
            foreach ($error->getErrors() as $key => $e) { 
                $errorFormat[$key] = $this->errorDrillDown($e);
            }
            
            return $errorFormat;
        }
        
        $errorFormat['code'] = $error->getCode();
        $errorFormat['message'] = $error->getMessage();
        
        return $errorFormat;
    }

}
