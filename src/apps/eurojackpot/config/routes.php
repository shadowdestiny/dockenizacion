<?php

namespace EuroMillions\eurojackpot\config;

use Phalcon\Mvc\Router\Group;



$eurojackpot = new Group(array('module' => 'eurojackpot'));

$eurojackpot->add(
    "/:controller/:action",
    array(
        'module' => 'eurojackpot',
        "controller" => 1,
        "action" => 2
    )
);

#Validate this call and these settings are in the HotToPlayRoutes class
/*$eurojackpot->add("/eurojackpot/how-to-play", array(
    "module" => "eurojackpot",
    'lottery' => 3,
    'controller' => 'help',
    'action' => 'index',
    'language' => 'en',
));

$eurojackpot->add("/ru/eurojackpot/как-играть", array(
    "module" => "eurojackpot",
    'lottery' => 3,
    'controller' => 'eurojackpot-help',
    'action' => 'index',
    'language' => 'ru',
));

$eurojackpot->add("/es/eurojackpot/como-se-juega", array(
    "module" => "eurojackpot",
    'lottery' => 3,
    'controller' => 'eurojackpot-help',
    'action' => 'index',
    'language' => 'es',
));

$eurojackpot->add("/it/eurojackpot/come-giocare", array(
    "module" => "eurojackpot",
    'lottery' => 3,
    'controller' => 'eurojackpot-help',
    'action' => 'index',
    'language' => 'it',
));

$eurojackpot->add("/nl/eurojackpot/speluitleg", array(
    "module" => "eurojackpot",
    'lottery' => 3,
    'controller' => 'eurojackpot-help',
    'action' => 'index',
    'language' => 'nl',
));*/

$router->mount($eurojackpot);
