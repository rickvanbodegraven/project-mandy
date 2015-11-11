<?php

namespace Core;

use Symfony\Component\HttpFoundation\Request;

abstract class Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public abstract function index();
}
