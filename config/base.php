<?php
return [
    // not yet required or used by anything
    'bundle' => '',

    // lets the Routing component know where to look for controller classes
    'controllerNamespace' => '\Controllers',

    // lets the Routing component know where to look for the routes it needs to handle
    'routeFile' => 'routes.php',

    // provides the directory the Routing component needs to save compiled matchers
    'routeCacheDir' => APPROOT . '/storage/cache/'
];
