<?php

namespace Core\Wrappers;

use Controllers;
use Core\Modules\Configuration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;

/**
 * Router class --- Takes care of linking request paths to the correct handlers with their arguments
 * @package Config
 */
class Routing
{
    /**
     * @var RouteCollection
     */
    private $routes;

    /**
     * @var RequestContext
     */
    private $context;

    /**
     * @var PhpFileLoader
     */
    private $loader;

    /**
     * Routing wrapper constructor
     */
    public function __construct()
    {
        // grab the location of the routes-file
        $routeFile = Configuration::instance()->setting('base', 'routeFile', 'routes.php');

        // tell the loader to look for said file in the approot
        $locator = new FileLocator(APPROOT);

        // create a loader for a php file that'll return an array
        $this->loader = new PhpFileLoader($locator);

        // load the actual routes from the file
        $this->routes = $this->loader->load($routeFile);

        // create a fresh context for usage later on in the lifecycle
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

        // instantiate the router with the correct settings so it can automatically perform caching
        $router = new Router(
            $this->loader,
            Configuration::instance()->setting('base', 'routeFile', 'routes.php'),
            ['cache_dir' => Configuration::instance()->setting('base', 'routeCacheDir')],
            $this->context
        );

        // default controller
        $match = ["_controller" => '\Controllers\HomeController', "_action" => "index"];

        try {
            $match = $router->matchRequest($request);
        } catch (\Exception $ex) {
            if ($ex instanceof ResourceNotFoundException) {
                // TODO improve this
                return new Response("404 Not Found", 404);
            } elseif ($ex instanceof MethodNotAllowedException) {
                // TODO improve this
                return new Response("Current request method is not allowed for this route", 401);
            }
        }

        // dynamically instantiate a new controller and pass it the Request object
        $controller = new $match['_controller']($request);
        $action = $match['_action'];

        // parse out all parameters (those keys that do not start with an underscore)
        $parameters = $this->getParameters($match);

        // we pass $match as last parameter because it also contains all key-value pairs for the arguments of the action
        $responseText = call_user_func_array([$controller, $action], $parameters);

        // wrap the response that was generated by the controller
        $response = new Response($responseText);

        return $response;
    }

    /**
     * @param array $match
     *
     * @return array
     */
    private function getParameters(array $match)
    {
        $parameters = [];

        foreach ($match as $key => $value) {
            if (substr($key, 0, 1) !== "_") {
                $parameters[$key] = $value;
            }
        }

        return $parameters;
    }
}
