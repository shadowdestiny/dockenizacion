<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 19/11/18
 * Time: 12:53
 */

namespace EuroMillions\megasena\config\routes;


use Phalcon\Mvc\Router\Group as RouterGroup;

class ResultPurchaseRoutes extends RouterGroup
{


    public function initialize()
    {
        // Default paths
        $this->setPaths(
            [
                'module' => 'megasena',
                'namespace' => 'EuroMillions\megasena\controllers',
            ]
        );
        // All the routes start with /megasena

        $this->add("/megasena/results/success", array(
            "module" => "megasena",
            'controller' => 'result',
            'action' => 'success',
            'language' => 'en'
        ));
    }

}