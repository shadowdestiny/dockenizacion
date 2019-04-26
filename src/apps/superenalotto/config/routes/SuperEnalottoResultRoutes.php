<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 20/11/18
 * Time: 07:21 PM
 */

namespace EuroMillions\superenalotto\config\routes;
use Phalcon\Mvc\Router\Group as RouterGroup;

class SuperEnalottoResultRoutes extends RouterGroup
{
    public function initialize()
    {

        //LAST RESULTS
        $this->add("/{lottery:(super-enalotto)+}/results", array(
            "module" => "superenalotto",
            'controller' => 'super-enalotto-numbers',
            'action' => 'index',
            'language' => 'en'
        ));

        $this->add("/{language:(es|it|nl|ru)+}/{lottery:(super-enalotto)+}/{result:(resultados|estrazioni|uitslagen|результаты)+}", array(
            "module" => "superenalotto",
            'controller' => 'super-enalotto-numbers',
            'action' => 'index',
        ));

        //PAST DATES
        $this->add("/{lottery:(super-enalotto)+}/results/draw-history-page/{date:([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])+)}", array(
            "module" => "superenalotto",
            'controller' => 'super-enalotto-numbers',
            'action' => 'pastResult',
            'language' => 'en'
        ));

        $this->add("/{language:(es|it|nl|ru)+}/{lottery:(super-enalotto)+}/{result:(resultados|estrazioni|uitslagen|результаты)+}/{lastdraw:(sorteos-anteriores|archivio|trekking-geschiedenis|история-розыгрышей)+}/{date:([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])+)}", array(
            "module" => "superenalotto",
            'controller' => 'super-enalotto-numbers',
            'action' => 'pastResult',
        ));

        //DRAW HISTORY
        $this->add("/{lottery:(super-enalotto)+}/results/draw-history-page", array(
            "module" => "superenalotto",
            'controller' => 'super-enalotto-numbers',
            'action' => 'pastList',
            'language' => 'en'
        ));

        $this->add("/{language:(es|it|nl|ru)+}/{lottery:(super-enalotto)+}/{result:(resultados|estrazioni|uitslagen|результаты)+}/{lastdraw:(sorteos-anteriores|archivio|trekking-geschiedenis|история-розыгрышей)+}", array(
            "module" => "superenalotto",
            'controller' => 'super-enalotto-numbers',
            'action' => 'pastList',
        ));
    }
}