<?php

class adminConfiguration extends sfApplicationConfiguration {
    
    /**
     * Get the routing for the given application 
     * 
     * Optionally set context values, using the $context parameter 
     * 
     * @see sfWebRequest::getRequestContext() 
     * 
     * @param string $application 
     * @param array $context 
     * 
     * @return sfRouting 
     * 
     * TO-DO: Agregar Lazzy Loading a esta función ver:
     * 1- http://symfony-blog.driebit.nl/2011/03/accessing-the-front-end-routing-from-the-back-end-application/
     * 2- http://symfony-blog.driebit.nl/2011/03/accessing-the-frontend-i18n-from-the-backend-application/
     * 3- http://en.wikipedia.org/wiki/Lazy_initialization#PHP
     */
    public function getRouting($application, array $context = array()) {
        $current_application = sfContext::getInstance()->getConfiguration()->getApplication();

        sfContext::switchTo($application);

        $factories = sfFactoryConfigHandler::getConfiguration(ProjectConfiguration::getActive()->getConfigPaths('config/factories.yml'));

        $class = $factories['routing']['class'];
        $params = $factories['routing']['param'];

        $params['context'] = array_merge(sfContext::getInstance()->getRequest()->getRequestContext(), $context);

        $routing = new $class($this->getEventDispatcher(), null, $params);

        sfContext::switchTo($current_application);

        return $routing;
    }
    
    public function configure() {
        
    }

}
