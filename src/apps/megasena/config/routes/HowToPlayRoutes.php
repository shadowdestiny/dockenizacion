<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 20/09/18
 * Time: 10:11
 */

namespace EuroMillions\megasena\config\routes;

use Phalcon\Mvc\Router\Group as RouterGroup;

class HowToPlayRoutes extends RouterGroup
{


    public function initialize()
    {
        // Default paths
        $this->setPaths(
            [
                'module'    => 'megasena',
                'namespace' => 'EuroMillions\megasena\controllers',
            ]
        );

        // All the routes start with /megasena
        //$this->setPrefix('/megasena'); #validate this, since it affects the prefixes that have a name at the beginning

        $this->add("/mega-sena/help",
            array(
                'controller' => 'help',
                'action' => 'index',
                'language' => 'en',
            )
        );

        //*********************MEGASENA ROUTES***********************************//
        $this->add("/mega-sena/how-to-play", array(
            'lottery' => 6,
            'controller' => 'help',
            'action' => 'index',
            'language' => 'en',
        ));

        $this->add("/es/mega-sena/como-se-juega", array(
            'lottery' => 6,
            'controller' => 'help',
            'action' => 'index',
            'language' => 'es',
        ));

        $this->add("/it/mega-sena/come-giocare", array(
            'lottery' => 6,
            'controller' => 'help',
            'action' => 'index',
            'language' => 'it',
        ));

        $this->add("/nl/mega-sena/speluitleg", array(
            'lottery' => 6,
            'controller' => 'help',
            'action' => 'index',
            'language' => 'nl',
        ));

        $this->add("/ru/mega-sena/как-играть", array(
            'lottery' => 6,
            'controller' => 'help',
            'action' => 'index',
            'language' => 'ru',
        ));

    }

}