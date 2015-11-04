<?php

namespace Core\Modules;

/**
 * Class autoloader implementation
 * @package Core
 */
class Autoloader
{
    private $locations = [];
    private $rootDirectory = "";

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->locations = ['Controllers', 'Core', 'Models', 'Views'];

        // use the approot configuration constant
        $this->rootDirectory = APPROOT;

        // register our own implementation of the autoloader
        $this->register();

        // and now also register the Composer autoloader
        require_once("{$this->rootDirectory}/vendor/autoload.php");
    }

    /**
     * Register the autoloader, which is a multi-platform case-insensitive re-implementation of the default PHP version
     *
     * @return void
     */
    private function register()
    {
        spl_autoload_register(function ($className) {

            $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);

            $file = "{$this->rootDirectory}/{$className}.php";

            if (file_exists($file) === true) {
                require_once($file);
            }
        });
    }
}
