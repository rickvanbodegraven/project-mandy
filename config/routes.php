<?php
return [
    'home/index'  => [ \Controllers\HomeController::class, "index" ],
    'home/{:any}' => \Controllers\HomeController::class,
];
