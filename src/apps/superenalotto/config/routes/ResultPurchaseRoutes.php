<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 19/11/18
 * Time: 12:53
 */

namespace EuroMillions\superenalotto\config\routes;


use Phalcon\Mvc\Router\Group as RouterGroup;

class ResultPurchaseRoutes extends RouterGroup
{


    public function initialize()
    {
        // Default paths
        $this->setPaths(
            [
                'module' => 'superenalotto',
                'namespace' => 'EuroMillions\superenalotto\controllers',
            ]
        );
        // All the routes start with /superenalotto

        $this->add("/super-enalotto/results/success", array(
            "module" => "superenalotto",
            'controller' => 'result',
            'action' => 'success',
            'language' => 'en'
        ));
    }

}