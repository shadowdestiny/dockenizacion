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

#Validate this call and these settings are in the HotToPlayRoutes class
/*$megasena->add("/megasena/how-to-play", array(
    "module" => "megasena",
    'lottery' => 3,
    'controller' => 'help',
    'action' => 'index',
    'language' => 'en',
));

$megasena->add("/ru/megasena/как-играть", array(
    "module" => "megasena",
    'lottery' => 3,
    'controller' => 'megasena-help',
    'action' => 'index',
    'language' => 'ru',
));

$megasena->add("/es/megasena/como-se-juega", array(
    "module" => "megasena",
    'lottery' => 3,
    'controller' => 'megasena-help',
    'action' => 'index',
    'language' => 'es',
));

$megasena->add("/it/megasena/come-giocare", array(
    "module" => "megasena",
    'lottery' => 3,
    'controller' => 'megasena-help',
    'action' => 'index',
    'language' => 'it',
));

$megasena->add("/nl/megasena/speluitleg", array(
    "module" => "megasena",
    'lottery' => 3,
    'controller' => 'megasena-help',
    'action' => 'index',
    'language' => 'nl',
));*/

$router->mount($megasena);
