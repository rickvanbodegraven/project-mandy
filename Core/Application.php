<?php

namespace Core;

use Core\Modules\Configuration;
use Core\Modules\Module;
use Core\Modules\Security as Security;
use Core\Modules\Session as Session;
use Core\Wrappers\Routing as Routing;
use Core\Wrappers\Blaze;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * Application constructor.
     */
    public function __construct()
    {
        // TODO move these hardcoded pieces to a config setting? or add additional modules/wrappers from a config?

        // TODO perhaps implement a way to also provide an alias for a module or a wrapper?

        $this->registerModules([
            new Security(),
            new Session(),
            new Configuration(APPROOT . "/config")
        ]);

        $this->registerWrappers([
            new Routing(),
            new Blaze()
        ]);
    }

    /**
     * @param $object
     *
     * @return string
     */
    private function getClassName($object)
    {
        $reflectionClass = new \ReflectionClass($object);

        return $reflectionClass->getShortName();
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
            throw new \Exception("Tried registering a module that is not an actual module");
        }

        $module->setApplication($this);

        $this->modules[$this->getClassName($module)] =& $module;

        return $this;
    }

    /**
     * @param object[] $wrappers
     *
     * @throws \Exception
     */
    private function registerWrappers(array $wrappers)
    {
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

        $this->wrappers[$this->getClassName($wrapper)] =& $wrapper;
    }

    /**
     * @param string $wrapperName
     *
     * @return mixed
     */
    public function getWrapper($wrapperName)
    {
        // TODO implement failsafe (check if wrapper is set)
        return $this->wrappers[$wrapperName];
    }

    /**
     * @param string $moduleName
     *
     * @return Module
     */
    public function getModule($moduleName)
    {
        // TODO implement failsafe (check if module is set)
        return $this->modules[$moduleName];
    }

    /**
     * Handle the current request by telling the correct controller to generate a response
     */
    public function handle()
    {
        $this->request = Request::createFromGlobals();

        /** @var Routing $routing */
        $routing = $this->getWrapper('Routing');

        /** @var Response $response */
        $response = $routing->getControllerResponse($this->request);

        // push out the response
        $response->send();

        // TODO should we make some sort of hooking system just before the response is pushed out?

        // TODO should figure out if we need to do something about output buffering
    }
}
