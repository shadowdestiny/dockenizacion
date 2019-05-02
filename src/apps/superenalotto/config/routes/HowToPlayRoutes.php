<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 20/09/18
 * Time: 10:11
 */

namespace EuroMillions\superenalotto\config\routes;

use Phalcon\Mvc\Router\Group as RouterGroup;

class HowToPlayRoutes extends RouterGroup
{


    public function initialize()
    {
        // Default paths
        $this->setPaths(
            [
                'module'    => 'superenalotto',
                'namespace' => 'EuroMillions\superenalotto\controllers',
            ]
        );

        // All the routes start with /superenalotto
        //$this->setPrefix('/superenalotto'); #validate this, since it affects the prefixes that have a name at the beginning

        $this->add("/superenalotto/help",
            array(
                'controller' => 'help',
                'action' => 'index',
                'language' => 'en',
            )
        );

        //*********************SUPERENALOTTO ROUTES***********************************//
        $this->add("/superenalotto/how-to-play", array(
            'lottery' => 6,
            'controller' => 'help',
            'action' => 'index',
            'language' => 'en',
        ));

        $this->add("/es/superenalotto/como-se-juega", array(
            'lottery' => 6,
            'controller' => 'help',
            'action' => 'index',
            'language' => 'es',
        ));

        $this->add("/it/superenalotto/come-giocare", array(
            'lottery' => 6,
            'controller' => 'help',
            'action' => 'index',
            'language' => 'it',
        ));

        $this->add("/nl/superenalotto/speluitleg", array(
            'lottery' => 6,
            'controller' => 'help',
            'action' => 'index',
            'language' => 'nl',
        ));

        $this->add("/ru/superenalotto/как-играть", array(
            'lottery' => 6,
            'controller' => 'help',
            'action' => 'index',
            'language' => 'ru',
        ));

    }

}