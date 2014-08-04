<?php

/**
 * html_static actions.
 *
 * @package    elperro
 * @subpackage html_static
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
// Include the Tera-WURFL file
require_once (sfConfig::get('sf_lib_dir') . '/vendor/terawurfl/TeraWurfl.php');

class html_staticActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        if ($viewTraditional = $request->getParameter('vt', false)) {
            $this->getUser()->setAttribute('view_traditional', true);
        }

        if ($wurflObj = $this->isMobileDevice() && !$this->getUser()->getAttribute('view_traditional', false)) {
            $this->setLayout('mobileLayout');
            $this->setTemplate('webMobile');
        }
    }

    public function executeWebMobile(sfWebRequest $request) {
        $this->setLayout('mobileLayout');
    }

    public function executeHowtoUser(sfWebRequest $request) {
    }

    public function executeHowtoAffiliate(sfWebRequest $request) {
    }

    public function executeAyudaIndex(sfWebRequest $request) {
        
    }

    public function executeCrearPromo(sfWebRequest $request) {
        
    }

    public function executeBuenaPromo(sfWebRequest $request) {
        
    }

    public function executeIndepabis(sfWebRequest $request) {
        
    }

    public function executeEmpleado(sfWebRequest $request) {
        
    }

    public function executeAumentoVentas(sfWebRequest $request) {
        
    }

    public function executeWhereAreWe(sfWebRequest $request) {
        $category = $request->getParameter('category', false);

        $table = Doctrine::getTable('Affiliate');

        $this->pager = new sfDoctrinePager('Affiliate', sfConfig::get('app_ep_max_affiliates_per_page', 20));

        if ($category) {
            if ($categoryObj = $this->validateCategory($category)) {
                $category = $categoryObj->getId();
                $this->pager->setQuery($table->getWithActivePromosQuery($category));
            } else {
                return 'Error';
            }
        } else {
            $this->pager->setQuery($table->getWithActivePromosQuery());
        }

        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();

        //$this->promotedAffiliates = $table->retrievePromoted(sfConfig::get('app_ep_max_promoted'), $categoryId);
        //$this->suggestedAffiliates = $table->retrieveSuggested(sfConfig::get('app_ep_max_suggested'), $categoryId);

        $this->categories = Doctrine::getTable('Category')->retrieveWithActivePromos($category);

        if ($request->isXmlHttpRequest()) {
            $this->setLayout(false);
        }

    }

    public function executeShowAffiliate(sfWebRequest $request) {
        $this->affiliate = $this->getRoute()->getObject();
        if (!$this->program = $this->affiliate->getActivePromos()->getFirst()){
		error_log("intentando acceder a: $this->affiliate\n",3,"/var/tmp/error-show-affiliate.log");
		$this->forward('html_static','whereAreWe');
	}	
    }

    public function executeFaq(sfWebRequest $request) {
        
    }

    public function executeContact(sfWebRequest $request) {
        
    }

    public function executePrivacyPolicy(sfWebRequest $request) {
        
    }

    public function executePruebaEmail(sfWebRequest $request) {
        self::sendWelcomeMail();
    }

    protected function validateCategory($category, $type = null) {
        if (is_null($type)) {
            if (!$category = Doctrine::getTable('Category')->findOneBySlug($category)) {
                return false;
            }
        } else if (!$category = Doctrine::getTable('Category')->findOneBySlugAndCategoryType($category, $type)) {
            return false;
        }
        return $category;
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

    protected function sendWelcomeMail() {
        $this->mail(array(
            'subject' => sfContext::getInstance()->getI18N()->__('Bienvenido a LealTag'),
            'fullname' => 'Octavio Azpurua',
            'email' => 'josehriera@gmail.com',
            'parameters' => array('fullname' => 'Octavio', 'email' => 'oazpurua@gmail.com', 'route1' => 'http://www.lealtag.com', 'route2' => 'http://www.lealtag.com', 'route3' => 'http://www.lealtag.com', 'feedback' => 'asd', 'asset' => 'asd'),
            'text' => 'email/pruebaEmailTemplate',
            'html' => 'email/pruebaEmailTemplate'
        ));
    }

    protected function mail($options) {
        $required = array('subject', 'parameters', 'email', 'fullname', 'html', 'text');

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
        $address = array('email' => 'no-reply@lealtag.com', 'fullname' => sfContext::getInstance()->getI18N()->__('Lealtag'));
        $message->setFrom(array($address['email'] => $address['fullname']));
        $message->setTo(array($options['email'] => $options['fullname']));
        $this->getMailer()->send($message);
    }
    
    public function executePrivacyPolicyMC(sfWebRequest $request) {
        $this->setLayout(false);
    }

}
