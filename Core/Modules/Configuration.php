<?php

namespace Core\Modules;

/**
 * The configuration class provides lazily-loaded configuration management.
 * @package Core\Modules
 */
class Configuration extends Module
{
    private static $instance = null;
    private $configPath = "";
    private $configFileExtension = ".php";
    private $loadedConfigurations = [];

    /**
     * Configuration constructor.
     *
     * @param $configurationFilesDirectory
     *
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

    /**
     * Singleton
     *
     * @return Configuration
     */
    public static function &instance()
    {
        return self::$instance;
    }

    /**
     * @param string $group
     * @param string $key
     * @param mixed  $value
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
    public function setting($group, $key, $defaultValue = null)
    {
        if ($this->loaded($group) === false) {
            $this->load($group);
            // TODO throw an exception (or bubble it up) if we can't load the group
        }

        $randomValue = microtime(true);

        $retrievedValue = $this->get($group, $key, $randomValue);

        if ($retrievedValue !== $randomValue) {
            return $retrievedValue;
        }

        return $defaultValue;
    }

    /**
     * @param string $group
     * @param string $key
     * @param mixed  $defaultValue
     *
     * @return mixed
     */
    private function get($group, $key, $defaultValue)
    {
        if (isset($this->loadedConfigurations[$group]) && isset($this->loadedConfigurations[$group][$key])) {
            return $this->loadedConfigurations[$group][$key];
        }

        return $defaultValue;
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
     * @param string $group
     *
     * @return mixed
     */
    public function settings($group)
    {
        if ($this->loaded($group) === false) {
            $this->load($group);
        }

        return $this->loadedConfigurations[$group];
    }
}
