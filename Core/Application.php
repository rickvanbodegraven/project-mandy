<?php

namespace Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Core\Wrapper\Twig;
use Core\Session as Session;

class Application
{
    private $modules = [];
    private $request = null;

    public function __construct()
    {
        $this->registerModules([
            new Security(),
            new Session(),
            new Routing(),
            new Twig(),
        ]);
    }

    public function registerModules(array $modules)
    {
        foreach ($modules as $module) {
            $this->register($module);
        }
    }

    /**
     * @param $module
     *
     * @throws \Exception
     * @return $this
     */
    public function register($module)
    {
        if (is_object($module) === false) {
            throw new \Exception("Tried registering a module that is not an actual module");
        }

        $this->modules[get_class($module)] = $module;

        return $this;
    }

    /**
     * @return string
     */
    public function handle()
    {
        $this->request = Request::createFromGlobals();

        // TODO use the Routing module to route the request to the correct controller

        // TODO tell that controller to generate the output, then pass that through to the response
//        $response = new Response($input);

    }
}