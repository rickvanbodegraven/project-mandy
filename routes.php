<?php
use Core\Wrappers\Routing;
use Symfony\Component\Routing\RouteCollection;

$collection = new RouteCollection();

$collection->add('default',   Routing::any('/', 'HomeController@index'));
$collection->add('home',      Routing::any('/home', 'HomeController@index'));
$collection->add('home-item', Routing::any('/home/{itemName}', 'HomeController@item'));

return $collection;
