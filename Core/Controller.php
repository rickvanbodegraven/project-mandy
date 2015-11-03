<?php

namespace Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    private $request;
    private $response;

    public function __construct(Request $request, Response $response = null)
    {
        $this->request = $request;
    }

    public abstract function index();
}
