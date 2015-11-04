<?php

namespace Core\Wrappers;

use Controllers;
use Core\Controller;
use Core\Modules\Configuration;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

/**
 * Router class --- Takes care of linking request paths to the correct handlers with their arguments
 * @package Config
 */
class Routing
{
    private $routes;
    private $context;
    private $matcher;

    public function __construct()
    {
        $this->routes = Configuration::instance()->settings('routes');
        $this->context = new RequestContext();
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

        echo $this->matcher->match($request->getPathInfo());



        // TODO implement logic that determines the controller to instantiate

        return new HomeController();
    }
}
