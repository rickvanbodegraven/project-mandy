<?php

namespace Core;

class Application
{
    private $modules = [];

    public function __construct()
    {

    }

    /**
     * @param $module
     * @throws \Exception
     * @return $this
     */
    public function register($module)
    {
        if (is_object($module) === false) {
            throw new \Exception("Tried registering a module that is not an actual module");
        }

        $this->modules[ get_class($module) ] = $module;

        return $this;
    }

    /**
     * @return string
     */
    public function handleRequest()
    {
        // TODO, TBI
        return "";
    }

}