<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 20/11/18
 * Time: 07:21 PM
 */

namespace EuroMillions\megasena\config\routes;
use Phalcon\Mvc\Router\Group as RouterGroup;

class MegaSenaResultRoutes extends RouterGroup
{
    public function initialize()
    {

        //LAST RESULTS
        $this->add("/{lottery:(mega-sena)+}/results", array(
            "module" => "megasena",
            'controller' => 'mega-sena-numbers',
            'action' => 'index',
            'language' => 'en'
        ));

        $this->add("/{language:(es|it|nl|ru)+}/{lottery:(mega-sena)+}/{result:(resultados|estrazioni|uitslagen|результаты)+}", array(
            "module" => "megasena",
            'controller' => 'mega-sena-numbers',
            'action' => 'index',
        ));

        //PAST DATES
        $this->add("/{lottery:(mega-sena)+}/results/draw-history-page/{date:([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])+)}", array(
            "module" => "megasena",
            'controller' => 'mega-sena-numbers',
            'action' => 'pastResult',
            'language' => 'en'
        ));

        $this->add("/{language:(es|it|nl|ru)+}/{lottery:(mega-sena)+}/{result:(resultados|estrazioni|uitslagen|результаты)+}/{lastdraw:(sorteos-anteriores|archivio|trekking-geschiedenis|история-розыгрышей)+}/{date:([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])+)}", array(
            "module" => "megasena",
            'controller' => 'mega-sena-numbers',
            'action' => 'pastResult',
        ));

        //DRAW HISTORY
        $this->add("/{lottery:(mega-sena)+}/results/draw-history-page", array(
            "module" => "megasena",
            'controller' => 'mega-sena-numbers',
            'action' => 'pastList',
            'language' => 'en'
        ));

        $this->add("/{language:(es|it|nl|ru)+}/{lottery:(mega-sena)+}/{result:(resultados|estrazioni|uitslagen|результаты)+}/{lastdraw:(sorteos-anteriores|archivio|trekking-geschiedenis|история-розыгрышей)+}", array(
            "module" => "megasena",
            'controller' => 'mega-sena-numbers',
            'action' => 'pastList',
        ));
    }
}