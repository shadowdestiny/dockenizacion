<?php

namespace EuroMillions\megamillions\config;

use Phalcon\Mvc\Router\Group;



$megamillions = new Group(array('module' => 'megamillions'));

$megamillions->add(
    "/:controller/:action",
    array(
        'module' => 'megamillions',
        "controller" => 1,
        "action" => 2
    )
);

$megamillions->add("/megamillions/how-to-play", array(
    "module" => "megamillions",
    'lottery' => 3,
    'controller' => 'help',
    'action' => 'index',
    'language' => 'en',
));

$megamillions->add("/ru/megamillions/как-играть", array(
    "module" => "megamillions",
    'lottery' => 3,
    'controller' => 'megamillions-help',
    'action' => 'index',
    'language' => 'ru',
));

$megamillions->add("/es/megamillions/como-se-juega", array(
    "module" => "megamillions",
    'lottery' => 3,
    'controller' => 'megamillions-help',
    'action' => 'index',
    'language' => 'es',
));

$megamillions->add("/it/megamillions/come-giocare", array(
    "module" => "megamillions",
    'lottery' => 3,
    'controller' => 'megamillions-help',
    'action' => 'index',
    'language' => 'it',
));

$megamillions->add("/nl/megamillions/speluitleg", array(
    "module" => "megamillions",
    'lottery' => 3,
    'controller' => 'megamillions-help',
    'action' => 'index',
    'language' => 'nl',
));

$router->mount($megamillions);
