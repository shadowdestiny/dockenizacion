<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 9/10/18
 * Time: 12:46
 */

namespace EuroMillions\eurojackpot\config\routes;
use Phalcon\Mvc\Router\Group as RouterGroup;

class EuroJackpotPlayRoutes extends RouterGroup
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

        $this->add("/play", array(
            "module" => "eurojackpot",
            'controller' => 'play',
            'action' => 'index',
            'language' => 'en'
        ));

        $this->add( '/cart/profile', [
            "module" => "eurojackpot",
            'lottery' => "eurojackpot",
            'controller' => 'mega-millions-cart',
            'action' => 'profile'
        ]);

        $this->add('/order', [
            "module" => "eurojackpot",
            'lottery' => 'eurojackpot',
            'controller' => 'mega-millions-order',
            'action' => 'order'
        ]);
    }

}