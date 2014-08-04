<?php

/**
 * Description of components
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class html_staticComponents extends sfComponents {

    public function executeFeatured() {
        $this->affiliates = Doctrine::getTable('Affiliate')->retrievePromoted(3);
    }

}
