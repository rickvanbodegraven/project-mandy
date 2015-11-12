<?php

namespace Core;


class View
{
    protected $parameters = [];

    public function __construct()
    {

    }

    /**
     * @param array $parameters
     */
    public function render($parameters)
    {
        $parameters = array($parameters);
    }
}
