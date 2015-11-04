<?php

require_once(APPROOT . '/Core/Modules/Autoloader.php');

// register said autoloader
$autoloader = new \Core\Modules\Autoloader();

// instantiate the application handler now that we know where to find the class
$application = new \Core\Application();

return $application;
