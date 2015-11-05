<?php

namespace Core\Wrappers;

use Controllers;
use Core\Controller;
use Core\Modules\Configuration;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;

/**
 * Router class --- Takes care of linking request paths to the correct handlers with their arguments
 * @package Config
 */
class Routing
{
    private $routes;
    private $context;
    private $matcher;

    /**
     * Routing wrapper constructor
     */
    public function __construct()
    {
        $this->routes = Configuration::instance()->settings('routes');
        $this->context = new RequestContext();
    }

    /**
     * @param string $path
     * @param string $handlerString
     *
     * @return Route
     */
    public static function any($path, $handlerString)
    {
        return self::createRoute($path, $handlerString);
    }

    /**
     * @param string   $path
     * @param   string $handlerString
     * @param array    $methods
     *
     * @return Route
     */
    private static function createRoute($path, $handlerString, $methods = [])
    {
        $handlerParts = self::parseHandlerString($handlerString);

        return new Route($path, $handlerParts, [], [], '', [], $methods);
    }

    /**
     * @param string $handlerString
     *
     * @return array["_controller", "_action"]
     */
    private static function parseHandlerString($handlerString)
    {
        $parts = explode("@", $handlerString);

        if (count($parts) === 2) {
            // controller and action
            return ["_controller" => $parts[0], "_action" => $parts[1]];
        }

        // just the controller
        return ["_controller" => $handlerString, "_action" => "index"];
    }

    /**
     * @param string $path
     * @param string $handlerString
     *
     * @return Route
     */
    public static function get($path, $handlerString)
    {
        return self::createRoute($path, $handlerString, ["GET"]);
    }

    /**
     * @param string $path
     * @param string $handlerString
     *
     * @return Route
     */
    public static function post($path, $handlerString)
    {
        return self::createRoute($path, $handlerString, ["POST"]);
    }

    /**
     * @param Request $request
     *
     * @return Controller
     */
    public function getController(Request $request)
    {
        // build the context from the Request that was passed
        $this->context->fromRequest($request);

        $this->matcher = new UrlMatcher($this->routes, $this->context);

        try {
            $match = $this->matcher->match($request->getPathInfo());
        } catch (\Exception $ex) {
            if ($ex instanceof ResourceNotFoundException) {
                // TODO
                echo "404 Not Found";
                return;
            }

            if ($ex instanceof MethodNotAllowedException) {
                // TODO
                echo "Current request method is NOT allowed";
                return;
            }
        } finally {

        }

        var_dump($match);


        // TODO implement logic that determines the controller to instantiate

//        return new HomeController();
    }
}
