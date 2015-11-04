<?php

namespace Core\Modules;

/**
 * The configuration class provides lazily-loaded configuration management.
 * @package Core\Modules
 */
class Configuration extends Module
{
    private $configPath = "";
    private $configFileExtension = ".php";
    private $loadedConfigurations = [];
    private static $instance = null;

    /**
     * Configuration constructor.
     *
     * @param $configurationFilesDirectory
     * @throws \InvalidArgumentException
     */
    public function __construct($configurationFilesDirectory)
    {
        if (is_dir($configurationFilesDirectory) === false) {
            throw new \InvalidArgumentException("Given directory does not exist: {$configurationFilesDirectory}");
        }

        $this->configPath = $configurationFilesDirectory;

        // singleton
        if (self::$instance === null) {
            self::$instance =& $this;
        }
    }

    public static function &instance()
    {
        return self::$instance;
    }

    /**
     * Private function to determine if a specific group of configuration settings has already been loaded
     *
     * @param string $group
     *
     * @return bool
     */
    private function loaded($group)
    {
        if (isset($this->loadedConfigurations[$group]) === false) {
            return false;
        }

        return true;
    }

    /**
     * @param string $group
     * @param string $key
     * @param mixed $value
     */
    public function set($group, $key, $value)
    {
        // TODO maybe throw an exception if the group (or key) was not defined (or loaded yet)?

        $this->loadedConfigurations[$group][$key] = $value;
    }

    /**
     * Retrieve a specific configuration element
     *
     * @param string $group
     * @param string $key
     * @param string $defaultValue
     *
     * @return mixed
     */
    public function setting($group, $key, $defaultValue = "")
    {
        if ($this->loaded($group) === false) {
            $this->load($group);
            // TODO throw an exception (or bubble it up) if we can't load the group
        }

        if ($this->has($group, $key)) {
            return $this->loadedConfigurations[$group][$key];
        }

        return $defaultValue;
    }

    public function settings($group)
    {
        if ($this->loaded($group) === false) {
            $this->load($group);
        }

        return $this->loadedConfigurations[$group];
    }

    /**
     * Load a specific configuration file
     *
     * @param string $group
     */
    private function load($group)
    {
        $constructedFileName = "{$this->configPath}/{$group}{$this->configFileExtension}";

        if (is_file($constructedFileName) === false) {
            throw new \InvalidArgumentException("Loading of configuration file '{$group}' failed, file does not exist");
        }

        $this->loadedConfigurations[$group] = require_once($constructedFileName);
    }

    /**
     * Private function to determine if a specific configuration key has been set
     *
     * @param string $group
     * @param string $key
     *
     * @return bool
     */
    private function has($group, $key)
    {
        if ($this->loaded($group) === false) {
            return false;
        }

        $default = microtime(true); // generate a value that is unique enough

        if ($this->setting($group, $key, $default) === $default) {
            return false;
        }

        return true;
    }
}
