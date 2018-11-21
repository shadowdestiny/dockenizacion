<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 20/11/18
 * Time: 07:21 PM
 */

namespace EuroMillions\megamillions\config\routes;
use Phalcon\Mvc\Router\Group as RouterGroup;

class MegaMillionsResultRoutes extends RouterGroup
{
    public function initialize()
    {

        //DRAW HISTORY
        $this->add("/{lottery:(megamillions)+}/results/draw-history", array(
            "module" => "megamillions",
            'controller' => 'megamillions-numbers',
            'action' => 'pastList',
            'language' => 'en'
        ));

        $this->add("/{language:(es|it|nl|ru)+}/{lottery:(megamillions)+}/{result:(resultados|estrazioni|uitslagen|результаты)+}/{lastdraw:(sorteos-anteriores|archivio|trekking-geschiedenislagen|история-розыгрышей)+}", array(
            "module" => "megamillions",
            'controller' => 'megamillions-numbers',
            'action' => 'pastList',
        ));

        //LAST RESULTS
        $this->add("/{lottery:(megamillions)+}/results", array(
            "module" => "megamillions",
            'controller' => 'megamillions-numbers',
            'action' => 'index',
            'language' => 'en'
        ));

        $this->add("/{language:(es|it|nl|ru)+}/{lottery:(megamillions)+}/{result:(resultados|estrazioni|uitslagen|результаты)+}", array(
            "module" => "megamillions",
            'controller' => 'megamillions-numbers',
            'action' => 'index',
        ));

        //PAST DATES
        $this->add("/{lottery:(megamillions)+}/results/draw-history/{date:([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])+)}", array(
            "module" => "megamillions",
            'controller' => 'megamillions-numbers',
            'action' => 'index',
            'language' => 'en'
        ));

        $this->add("/{language:(es|it|nl|ru)+}/{lottery:(megamillions)+}/{result:(resultados|estrazioni|uitslagen|результаты)+}/{lastdraw:(sorteos-anteriores|archivio|trekking-geschiedenislagen|история-розыгрышей)+}/{date:([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])+)}", array(
            "module" => "megamillions",
            'controller' => 'megamillions-numbers',
            'action' => 'index',
        ));
    }
}