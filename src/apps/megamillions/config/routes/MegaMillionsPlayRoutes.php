<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 9/10/18
 * Time: 12:46
 */

namespace EuroMillions\megamillions\config\routes;
use Phalcon\Mvc\Router\Group as RouterGroup;

class MegaMillionsPlayRoutes extends RouterGroup
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

        $this->add("/play", array(
            "module" => "megamillions",
            'controller' => 'play',
            'action' => 'index',
            'language' => 'en'
        ));
    }

}