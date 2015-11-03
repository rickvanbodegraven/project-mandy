<?php

namespace Core;

/**
 * Router class --- Takes care of linking request paths to the correct handlers with their arguments
 * @package Config
 */
class Routing
{
    private $routes = ['GET' => [], 'POST' => []];

    private $acceptedMethods = ['POST' => 0, 'GET' => 1];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);

        // TODO automatically read some sort of configuration file to set up all routes ?
    }

    public function post($route)
    {
        // TODO, TBI
    }

    public function get($route)
    {
        // TODO, TBI
    }

    public function any($route)
    {
        // TODO, TBI
    }

    /**
     *
     */
    public function handleRequest()
    {
        // check if the current request method is allowed
        if (isset($this->acceptedMethods[$this->requestMethod]) === false) {
            throw new \Core\Exception\WrongRequestMethodException("Request method (" . $this->requestMethod . ") not supported");
        }

        // TODO, TBI

        return "";
    }
}
