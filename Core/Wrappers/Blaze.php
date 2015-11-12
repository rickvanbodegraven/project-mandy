<?php

namespace Core\Wrappers;

// for the life of me I don't grasp why Twig uses this horrible naming convention
use Twig_Loader_Filesystem as TwigLoaderFilesystem;
use Twig_Environment as TwigEnvironment;

/**
 * Templating engine wrapper for Twig. Cleverly named as a portmanteau of Blade and Razor.
 * Handles configuration and such.
 *
 * @package Core\Wrappers
 */
class Blaze
{
    private $templatePath = "";
    private $cachePath = "";

    private $environment;

    /**
     * @var TwigLoaderFilesystem
     */
    private $loader = null;

    /**
     * Twig constructor.
     */
    public function __construct()
    {
        $this->loader = new TwigLoaderFilesystem($this->templatePath);
        $this->environment = new TwigEnvironment($this->loader, ['cache' => $this->cachePath]);
    }
}
