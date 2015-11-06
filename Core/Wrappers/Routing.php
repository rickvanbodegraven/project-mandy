<?php

namespace Core\Wrappers;

use Controllers;
use Core\Modules\Configuration;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @param string $path
     * @param string $handlerString
     * @param array  $methods
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

        $controllerNamespace = Configuration::instance()->setting('base', 'controllerNamespace');

        if (count($parts) === 2) {
            // controller and action
            return ["_controller" => "{$controllerNamespace}\\{$parts[0]}", "_action" => $parts[1]];
        }

        // just the controller
        return ["_controller" => "{$controllerNamespace}\\{$handlerString}", "_action" => "index"];
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
     * @return Response
     */
    public function getControllerResponse(Request $request)
    {
        // build the context from the Request that was passed
        $this->context->fromRequest($request);

        $this->matcher = new UrlMatcher($this->routes, $this->context);

        // default controller
        $match = [ "_controller" => '\Controllers\HomeController', "_action" => "index" ];

        try {
            $match = $this->matcher->matchRequest($request);
        } catch (\Exception $ex) {
            if ($ex instanceof ResourceNotFoundException) {
                // TODO
                echo "404 Not Found";
            } elseif ($ex instanceof MethodNotAllowedException) {
                // TODO
                echo "Current request method is not allowed for this route";
            }
        }

        $controller = new $match['_controller']($request);
        $action = $match['_action'];

        // TODO implement a function to create a new array with just the arguments


        // we pass $match as last parameter because it also contains all key-value pairs for the arguments of the action
        $responseText = call_user_func_array([ $controller, $action ], $match);

//        $responseText = (new $match['_controller']($this->request))->$match['_action']($match['arguments']);
//        $responseText = call_user_func_array("{$match['_controller']}->{$match['_action']}", []); //$match['arguments']);

        $response = new Response($responseText);

        return $response;
    }
}
