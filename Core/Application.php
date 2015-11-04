<?php

namespace Core;

use Core\Modules\Module;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Core\Wrappers\Twig;
use Core\Modules\Session as Session;
use Core\Modules\Security as Security;
use Core\Wrappers\Routing as Routing;

class Application
{
    /**
     * @var Modules\Module[]
     */
    private $modules = [];

    private $wrappers = [];

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request = null;

    public function __construct()
    {
        // TODO move these hardcoded pieces to a config setting? or add additional modules/wrappers from a config?

        $this->registerModules([
            new Security(),
            new Session(),
        ]);

        $this->registerWrappers([
            new Routing(),
            new Twig()
        ]);
    }

    /**
     * @param array $modules
     *
     * @throws \Exception
     */
    private function registerModules(array $modules)
    {
        foreach ($modules as $module) {
            $this->registerModule($module);
        }
    }

    /**
     * @param Modules\Module $module
     *
     * @throws \Exception
     * @return $this
     */
    private function registerModule($module)
    {
        if (is_object($module) === false || is_a($module, Modules\Module::class, true) === false) {
            var_dump($module);
            throw new \Exception("Tried registering a module that is not an actual module");
        }

        $module->setApplication($this);

        $this->modules[get_class($module)] =& $module;

        return $this;
    }

    /**
     * @param object[] $wrappers
     *
     * @throws \Exception
     */
    private function registerWrappers(array $wrappers)
    {
        // TODO
        foreach ($wrappers as $wrapper) {
            $this->registerWrapper($wrapper);
        }
    }

    /**
     * @param object $wrapper
     *
     * @throws \Exception
     */
    private function registerWrapper($wrapper)
    {
        if (is_object($wrapper) === false) {
            throw new \Exception("Tried registering a wrapper that is no object");
        }

        $this->wrappers[get_class($wrapper)] =& $wrapper;
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