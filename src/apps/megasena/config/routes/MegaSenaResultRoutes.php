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

        $this->add("/{language:(es|it|nl|ru)+}/{lottery:(megasena)+}/{result:(resultados|estrazioni|uitslagen|результаты)+}/{lastdraw:(sorteos-anteriores|archivio|trekking-geschiedenislagen|история-розыгрышей)+}", array(
            "module" => "megasena",
            'controller' => 'mega-sena-numbers',
            'action' => 'pastList',
        ));

        //LAST RESULTS
        $this->add("/{lottery:(megasena)+}/results", array(
            "module" => "megasena",
            'controller' => 'mega-sena-numbers',
            'action' => 'index',
            'language' => 'en'
        ));

        $this->add("/{language:(es|it|nl|ru)+}/{lottery:(megasena)+}/{result:(resultados|estrazioni|uitslagen|результаты)+}", array(
            "module" => "megasena",
            'controller' => 'mega-sena-numbers',
            'action' => 'index',
        ));

        //PAST DATES
        /*
        $this->add("/{lottery:(megasena)+}/results/draw-history/{date:([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])+)}", array(
            "module" => "megasena",
            'controller' => 'mega-sena-numbers',
            'action' => 'pastResult',
            'language' => 'en'
        ));
        */
        $this->add("/{language:(es|it|nl|ru)+}/{lottery:(megasena)+}/{result:(resultados|estrazioni|uitslagen|результаты)+}/{lastdraw:(sorteos-anteriores|archivio|trekking-geschiedenislagen|история-розыгрышей)+}/{date:([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])+)}", array(
            "module" => "megasena",
            'controller' => 'mega-sena-numbers',
            'action' => 'index',
        ));

        $this->add("/{lottery:(megasena)+}/results/draw-history/:params", array(
            "module" => "megasena",
            'controller' => 'mega-sena-numbers',
            'action' => 'pastResult',
            'params' => 2,
            'language' => 'en',
        ));

        //DRAW HISTORY
        $this->add("/{lottery:(megasena)+}/results/draw-history", array(
            "module" => "megasena",
            'controller' => 'mega-sena-numbers',
            'action' => 'pastList',
            'language' => 'en'
        ));
    }
}