<?php

/**
 * Description of cssAdderFilter
 *
 * @author zero0x on 2008-05-10 can be found at http://snippets.symfony-project.org/snippet/306
 */
 
class cssAdderFilter extends sfFilter
{
    public function execute( $filterChain )
    {
        $context = $this->getContext();
        $request = $context->getRequest();
        $response = $context->getResponse();
        
        $module = $request->getParameter("module");
        $module_file = $module . '.css';
        $main_module_file =  $module . '/main.css';
        $action_file = $module . '/' . $request->getParameter("action") . '.css';
        $web_css_dir = sfConfig::get('sf_web_dir') . '/css/';
        
        if( is_readable( $web_css_dir . $module_file ) )
        {
            $response->addStylesheet($module_file);
        }
        
        if( is_readable( $web_css_dir . $main_module_file ) )
        {
            $response->addStylesheet($main_module_file);
        }
        
        if( is_readable( $web_css_dir . $action_file ) )
        {
            $response->addStylesheet($action_file);
        }
 
        $filterChain->execute();
    }
}