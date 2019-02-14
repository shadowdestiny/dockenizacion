<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 19/11/18
 * Time: 12:53
 */

namespace EuroMillions\eurojackpot\config\routes;


use Phalcon\Mvc\Router\Group as RouterGroup;

class ResultPurchaseRoutes extends RouterGroup
{


    public function initialize()
    {
        // Default paths
        $this->setPaths(
            [
                'module' => 'eurojackpot',
                'namespace' => 'EuroMillions\eurojackpot\controllers',
            ]
        );
        // All the routes start with /eurojackpot
        $this->setPrefix('/eurojackpot');

        $this->add("/results/success", array(
            "module" => "eurojackpot",
            'controller' => 'result',
            'action' => 'success',
            'language' => 'en'
        ));
    }

}