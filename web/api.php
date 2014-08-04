<?php


require_once(dirname(__FILE__).'/../apiConfig/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('api', 'prod', false);
sfContext::createInstance($configuration)->dispatch();
