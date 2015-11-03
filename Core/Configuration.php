<?php

namespace Core;

class Configuration
{
    private $configPath = "";
    private $loadedConfigurations = [];

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
    }

    /**
     * Private function to determine if a specific group of configuration settings has already been loaded
     *
     * @param string $group
     * @param string $key
     */
    private function exists($group, $key = "")
    {
        // TODO
    }

    public function load($group)
    {
        // TODO
    }

    public function get($group, $key, $defaultValue = "")
    {
        // TODO
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
        $default = microtime(true); // generate a value that is unique enough

        if ($this->get($group, $key, $default) === $default) {
            return false;
        }

        return true;
    }
}