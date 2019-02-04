<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 19/11/18
 * Time: 12:53
 */

namespace EuroMillions\megamillions\config\routes;


use Phalcon\Mvc\Router\Group as RouterGroup;

class ResultPurchaseRoutes extends RouterGroup
{


    public function initialize()
    {
        // Default paths
        $this->setPaths(
            [
                'module' => 'megamillions',
                'namespace' => 'EuroMillions\megamillions\controllers',
            ]
        );
        // All the routes start with /megamillions
        $this->setPrefix('/megamillions');

        $this->add("/results/success", array(
            "module" => "megamillions",
            'controller' => 'result',
            'action' => 'success',
            'language' => 'en'
        ));



    }

}