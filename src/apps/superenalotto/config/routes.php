<?php

namespace EuroMillions\superenalotto\config;

use Phalcon\Mvc\Router\Group;

$superenalotto = new Group(array('module' => 'superenalotto'));

$superenalotto->add(
    "/:controller/:action",
    array(
        'module' => 'superenalotto',
        "controller" => 1,
        "action" => 2
    )
);

$router->mount($superenalotto);
