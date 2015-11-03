<?php

namespace Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use \Core\Wrapper\Twig;

class Application
{
    private $modules = [];
    private $request = null;

    public function __construct()
    {
        // as early in the chain as possible, activate security functions and headers, before we process any user input
        $this->register(new Security())
             ->register(new Session()) // start up session handling
             ->register(new Routing()) // register router
             ->register(new Twig());
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

        $input = $this->request->get('name', 'Default');

        $response = new Response($input);

        // TODO, TBI
        $response->send();
    }

}