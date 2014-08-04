<?php

/**
 * survey actions.
 *
 * @package    elperro
 * @subpackage survey
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */

// Include the Tera-WURFL file
require_once (sfConfig::get('sf_lib_dir') . '/vendor/terawurfl/TeraWurfl.php');

class surveyActions extends sfActions {

    public function executeSurveys(sfWebRequest $request) {
        if (!$hash = $request->getParameter('h', false)) {
            return 'InvalidHash';
        }
        
        if (!$this->participationRequest = Doctrine::getTable('ParticipationRequest')->findOneBy('hash', $hash)){
            return 'InvalidHash';
        }
        
        $user = $this->getUser()->getGuardUser();
        
        if ($user->getId() != $this->participationRequest->getUserId()) {
            $this->getUser()->signOut();
            
            return 'InvalidUser';
        }
        
        if ($this->participationRequest->getUsed()) {
            return 'ParticipationDone';
        }
        
        $via = 'web';
        
        $this->surveysForm = new epSurveysForm(
            null, 
            array(
                'user'      => $user, 
                'surveys'   => $this->participationRequest->getSurveys(),
                'via'       => $via,
                'action'    => $this->participationRequest->getAction(),
                'asset'     => $this->participationRequest->getAsset(),
            ));
        
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->surveysForm->bind($request->getParameter($this->surveysForm->getName()));

            if ($this->surveysForm->isValid()) {
                $this->surveysForm->save();
                
                $this->participationRequest->setUsed(true);
                $this->participationRequest->setUsedAt(date(DateTime::W3C));
                $this->participationRequest->save();

                $points = $this->awardPoints('feedback', $user);
                
                $this->getUser()->setFlash('lt_pts_awarded', '¡Has sido premiado con '. $points .' puntos LealTag por completar la encuesta!');
                
                /**
                 *  Cable para mastercard 
                 * $this->getUser()->setFlash('lt_pts_awarded', '¡Felicitaciones! Ya estás participando en la rifa. <br> Recuerda conservar tu voucher <br> ¡Vamos a vivirlo juntos!');
                 */
                
                
                $this->redirect($this->getController()->genUrl(array('sf_route' => 'user_prizes'), false));
            }
        }
    }
    
    public function executeFeedback(sfWebRequest $request) {
        if ($viewTraditional = $request->getParameter('vt', false)) {
            $this->getUser()->setAttribute('view_traditional', true);
        }
        
        if ($wurflObj = $this->isMobileDevice() && !$this->getUser()->getAttribute('view_traditional', false)) {
            $this->setLayout('mobileLayout');
            $this->setTemplate('webMobileFeedback');
        }
        
        if (!$hash = $request->getParameter('h', false)) {
            return 'InvalidHash';
        }
        
        if (!$this->participationRequest = Doctrine::getTable('ParticipationRequest')->findOneBy('hash', $hash)){
            return 'InvalidHash';
        }
        
        $user = $this->getUser()->getGuardUser();
        
        if ($user->getId() != $this->participationRequest->getUserId()) {
            $this->getUser()->signOut();
            
            return 'InvalidUser';
        }
        
        if ($this->participationRequest->getUsed()) {
            return 'ParticipationDone';
        }
        
        $via = 'web';

        $feedback = new Feedback();

        $feedback->setAction($this->participationRequest->getAction());
        $feedback->setUser($user);
        $feedback->setPromoId($this->participationRequest->getPromoId());
        $feedback->setAssetId($this->participationRequest->getAssetId());
        $feedback->setAffiliateId($this->participationRequest->getPromo()->getAffiliateId());
        
        $this->feedbackForm = new epFeedbackForm($feedback);
        
        if ($surveys = $this->participationRequest->getSurveys()) {
            $this->surveysForm = new epSurveysForm(
                null, 
                array(
                    'user'      => $user, 
                    'surveys'   => $surveys,
                    'via'       => $via,
                    'action'    => $this->participationRequest->getAction(),
                    'asset'     => $this->participationRequest->getAsset(),
                ));
        }
        
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->feedbackForm->bind($request->getParameter($this->feedbackForm->getName()));
            
            if ($surveys) {
                $this->surveysForm->bind($request->getParameter($this->surveysForm->getName()));
            }

            if ($this->feedbackForm->isValid() && ($surveys ?  $this->surveysForm->isValid() : true)) {
                $this->feedbackForm->save();
                
                $points = $this->awardPoints('feedback', $user);
                
                $this->getUser()->setFlash('lt_pts_awarded', '¡Has sido premiado con '. $points .' puntos LT por compartir tu experiencia!');
                
                if ($surveys) {
                    $this->surveysForm->save();
                    
                    $this->getUser()->setFlash('lt_pts_awarded', '¡Has sido premiado con '. $points .' puntos LT por completar la encuesta!');
                    
                    /**
                     *  Cable para mastercard 
                     * $this->getUser()->setFlash('lt_pts_awarded', '¡Felicitaciones! Ya estás participando en la rifa. <br> Recuerda conservar tu voucher <br> ¡Vamos a vivirlo juntos!');
                     */
                }
                
                $this->participationRequest->setUsed(true);
                $this->participationRequest->setUsedAt(date(DateTime::W3C));
                $this->participationRequest->save();
                
                if ($wurflObj && !$this->getUser()->getAttribute('view_traditional', false)) {
                    $this->redirect($this->getController()->genUrl(array('sf_route' => 'homepage'), false));
                }
                
                $this->redirect($this->getController()->genUrl(array('sf_route' => 'user_prizes'), false));
            }
        }
    }
    
    protected function processSurveysForms(sfWebRequest $request, sfFormObject $form, sfGuardUser $user) {
        $form->bind($request->getParameter($form->getName()));
        if ($form->isValid()) {
            $form->save();
            
            $points = $this->awardPoints('feedback', $user);
                
            $this->getUser()->setFlash('lt_pts_awarded', '¡Has sido premiado con ' . $points . ' por completar la encuesta!');
            
            $this->redirect($this->getController()->genUrl(array('sf_route' => 'user_prizes'), false));
        }
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
    
    protected function isMobileDevice() {
        // instantiate the Tera-WURFL object
        $wurflObj = new TeraWurfl();

        // Get the capabilities of the current client.
        $matched = $wurflObj->getDeviceCapabilitiesFromAgent();

        // see if this client is on a wireless device (or if they can't be identified)
        if (!$matched || !$wurflObj->getDeviceCapability("is_wireless_device")) {
            return false;
        }
        
        return $wurflObj;
    }
    
}
