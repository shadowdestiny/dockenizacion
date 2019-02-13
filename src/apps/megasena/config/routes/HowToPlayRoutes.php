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
                'module'    => 'eurojackpot',
                'namespace' => 'EuroMillions\eurojackpot\controllers',
            ]
        );

        // All the routes start with /eurojackpot
        //$this->setPrefix('/eurojackpot'); #validate this, since it affects the prefixes that have a name at the beginning

        $this->add("/eurojackpot/help",
            array(
                'controller' => 'help',
                'action' => 'index',
                'language' => 'en',
            )
        );

        //*********************EUROJACKPOT ROUTES***********************************//
        $this->add("/eurojackpot/how-to-play", array(
            'lottery' => 5,
            'controller' => 'help',
            'action' => 'index',
            'language' => 'en',
        ));

        $this->add("/es/eurojackpot/como-se-juega", array(
            'lottery' => 5,
            'controller' => 'help',
            'action' => 'index',
            'language' => 'es',
        ));

        $this->add("/it/eurojackpot/come-giocare", array(
            'lottery' => 5,
            'controller' => 'help',
            'action' => 'index',
            'language' => 'it',
        ));

        $this->add("/nl/eurojackpot/speluitleg", array(
            'lottery' => 5,
            'controller' => 'help',
            'action' => 'index',
            'language' => 'nl',
        ));

        $this->add("/ru/eurojackpot/как-играть", array(
            'lottery' => 5,
            'controller' => 'help',
            'action' => 'index',
            'language' => 'ru',
        ));

    }

}