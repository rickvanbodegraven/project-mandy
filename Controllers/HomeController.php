<?php

namespace Controllers;

use Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        echo $this->dummyResponse(__METHOD__);
    }

    public function item($itemName)
    {
        echo $this->dummyResponse(__METHOD__ . ' with ' . $itemName);
    }
}