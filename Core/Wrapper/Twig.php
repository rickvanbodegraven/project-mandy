<?php

namespace Core\Wrapper;

/**
 * Wrapper for Twig. Handles configuration and such.
 * @package Core\Wrapper
 */
class Twig
{
    private $templatePath = "";
    private $cachePath = "";
    private $loader = null;

    public function __construct()
    {
        $this->readConfig();

        $this->loader = new \Twig_Loader_Filesystem($this->templatePath);

        $this->environment = new \Twig_Environment($this->loader, [
            'cache' => $this->cachePath
        ]);
    }

    private function readConfig()
    {

    }
}
