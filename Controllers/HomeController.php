<?php

namespace Controllers;

use Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        echo "This is the response from the index function of the homecontroller";
    }

    public function item($itemName)
    {
        echo "response for item function of homecontroller, you passed parameter {$itemName}";
    }
}