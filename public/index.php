<?php
// bootstrap the application, load all of the modules and set up routing

/** @var $application \Core\Application */
$application = require_once('../bootstrap.php');

// kick off the application, let the frontcontroller handle the request and push out the response
$application->handle();
