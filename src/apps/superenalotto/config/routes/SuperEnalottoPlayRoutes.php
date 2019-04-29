<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 9/10/18
 * Time: 12:46
 */

namespace EuroMillions\superenalotto\config\routes;
use Phalcon\Mvc\Router\Group as RouterGroup;

class SuperEnalottoPlayRoutes extends RouterGroup
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
        // All the routes start with /eurojackpot

        $this->add("/superenalotto/play", array(
            "lottery" => 'superenalotto',
            "module" => "superenalotto",
            'controller' => 'play',
            'action' => 'index',
            'language' => 'en'
        ));

        $this->add( '/superenalotto/cart/profile', [
            "module" => "superenalotto",
            'lottery' => "superenalotto",
            'controller' => 'super-enalotto-cart',
            'action' => 'profile'
        ]);

        $this->add('/superenalotto/order', [
            "module" => "superenalotto",
            'lottery' => 'superenalotto',
            'controller' => 'super-enalotto-order',
            'action' => 'order'
        ]);
    }

}