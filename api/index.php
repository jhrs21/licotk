<?php

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('api', 'prod', false);
//$configuration = ProjectConfiguration::getApplicationConfiguration('api', 'prod', true);
sfContext::createInstance($configuration)->dispatch();
