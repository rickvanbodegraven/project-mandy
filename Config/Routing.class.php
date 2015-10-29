<?php

namespace Config;

/**
 * Router class --- Takes care of linking request paths to the correct handlers with their arguments
 * @package Config
 */
class Routing
{
    private $routes = [];

    private $acceptedMethods = [ "POST" => 0, "GET" => 1 ];

    /**
     * Constructor
     */
    public function __construct()
    {
        $requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);

        if (isset($this->acceptedMethods[$requestMethod]) === false) {
            throw new \Core\Exception\WrongRequestMethodException("Request method (" . $requestMethod .") not supported");
        }
    }
}
