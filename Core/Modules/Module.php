<?php

namespace Core\Modules;

use Core\Application;

class Module
{
    /**
     * @var \Core\Application $application
     */
    protected $application;

    /**
     * Sets the (by-ref) reference to the Application container
     *
     * @param Application $application
     */
    public function setApplication(Application $application)
    {
        $this->application =& $application;
    }
}