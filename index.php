<?php

// bootstrap the application, load all of the modules and set up routing
$application = require_once('bootstrap.php');

try {
    $response = $application->handleRequest();
} catch (\Exception $ex) {
    die($ex->getMessage());
}

echo $response;
