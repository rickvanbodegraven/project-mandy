<?php

use \Symfony\Component\Routing\RouteCollection;
use \Core\Wrappers\Routing;

$collection = new RouteCollection();

$collection->add('default', Routing::any('/', 'HomeController@index'));
$collection->add('home', Routing::any('/home', 'HomeController@index'));

$collection->add('faq', Routing::any('/faq', 'FaqController@index'));


return $collection;
