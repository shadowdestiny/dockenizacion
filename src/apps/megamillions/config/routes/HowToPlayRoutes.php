<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 20/09/18
 * Time: 10:11
 */

namespace EuroMillions\megamillions\config\routes;

use Phalcon\Mvc\Router\Group as RouterGroup;

class HowToPlayRoutes extends RouterGroup
{


    public function initialize()
    {
        // Default paths
        $this->setPaths(
            [
                'module'    => 'megamillions',
                'namespace' => 'EuroMillions\megamillions\controllers',
            ]
        );

        // All the routes start with /megamillions
        $this->setPrefix('/megamillions');

        $this->add("/help",
            array(
                'controller' => 'help',
                'action' => 'index',
                'language' => 'en',
            )
        );

        $this->add("/ru/megamillions/как-играть", array(
            'lottery' => 3,
            'controller' => 'help',
            'action' => 'index',
            'language' => 'ru',
        ));

        $this->add("/es/megamillions/como-se-juega", array(
            'lottery' => 3,
            'controller' => 'help',
            'action' => 'index',
            'language' => 'es',
        ));

        $this->add("/it/megamillions/come-giocare", array(
            'lottery' => 3,
            'controller' => 'help',
            'action' => 'index',
            'language' => 'it',
        ));

        $this->add("/nl/megamillions/speluitleg", array(
            'lottery' => 3,
            'controller' => 'help',
            'action' => 'index',
            'language' => 'nl',
        ));

    }

}