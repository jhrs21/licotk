<?php

/**
 * sfApply actions.
 *
 * @package    5seven5
 * @subpackage sfApply
 * @author     Tom Boutell, tom@punkave.com
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */

// Autoloader can't find this
require_once(sfConfig::get('sf_plugins_dir').'/sfDoctrineApplyPlugin/modules/sfApply/lib/BasesfApplyComponents.class.php');

class sfApplyComponents extends BasesfApplyComponents
{
    public function executeApply(sfWebRequest $request)
    {
        $this->form = $this->newForm('sfApplyApplyForm');
    }
    
    protected function newForm($className, $object = null)
    {
        $key = "app_sfApplyPlugin_$className" . "_class";
        
        $class = sfConfig::get($key, $className);
        
        if ($object !== null)
        {
            return new $class($object);
        }
        
        return new $class;
    }
}
