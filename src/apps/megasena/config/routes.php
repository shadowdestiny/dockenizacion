<?php

namespace EuroMillions\megasena\config;

use Phalcon\Mvc\Router\Group;

$megasena = new Group(array('module' => 'megasena'));

$megasena->add(
    "/:controller/:action",
    array(
        'module' => 'megasena',
        "controller" => 1,
        "action" => 2
    )
);

$router->mount($megasena);
