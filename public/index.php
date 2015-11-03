<?php
// bootstrap the application, load all of the modules and set up routing

// we define the application root for use throughout the application
define('APPROOT', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..'));

/** @var $application \Core\Application */
$application = require_once(APPROOT . '/bootstrap.php');

// kick off the application, let the frontcontroller handle the request and push out the response
$application->handle();
