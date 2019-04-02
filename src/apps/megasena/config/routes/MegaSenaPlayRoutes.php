<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 9/10/18
 * Time: 12:46
 */

namespace EuroMillions\megasena\config\routes;
use Phalcon\Mvc\Router\Group as RouterGroup;

class MegaSenaPlayRoutes extends RouterGroup
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
        // All the routes start with /eurojackpot

        $this->add("/mega-sena/play", array(
            "lottery" => 'megasena',
            "module" => "megasena",
            'controller' => 'play',
            'action' => 'index',
            'language' => 'en'
        ));

        $this->add( '/megasena/cart/profile', [
            "module" => "megasena",
            'lottery' => "megasena",
            'controller' => 'mega-sena-cart',
            'action' => 'profile'
        ]);

        $this->add('/megasena/order', [
            "module" => "megasena",
            'lottery' => 'megasena',
            'controller' => 'mega-sena-order',
            'action' => 'order'
        ]);
    }

}