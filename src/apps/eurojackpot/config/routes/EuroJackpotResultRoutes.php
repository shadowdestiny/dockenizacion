<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 20/11/18
 * Time: 07:21 PM
 */

namespace EuroMillions\eurojackpot\config\routes;
use Phalcon\Mvc\Router\Group as RouterGroup;

class EuroJackpotResultRoutes extends RouterGroup
{
    public function initialize()
    {

        $this->add("/{language:(es|it|nl|ru)+}/{lottery:(eurojackpot)+}/{result:(resultados|estrazioni|uitslagen|результаты)+}/{lastdraw:(sorteos-anteriores|archivio|trekking-geschiedenislagen|история-розыгрышей)+}", array(
            "module" => "eurojackpot",
            'controller' => 'euro-jackpot-numbers',
            'action' => 'pastList',
        ));

        //LAST RESULTS
        $this->add("/{lottery:(eurojackpot)+}/results", array(
            "module" => "eurojackpot",
            'controller' => 'euro-jackpot-numbers',
            'action' => 'index',
            'language' => 'en'
        ));

        $this->add("/{language:(es|it|nl|ru)+}/{lottery:(eurojackpot)+}/{result:(resultados|estrazioni|uitslagen|результаты)+}", array(
            "module" => "eurojackpot",
            'controller' => 'euro-jackpot-numbers',
            'action' => 'index',
        ));

        //PAST DATES
        /*
        $this->add("/{lottery:(eurojackpot)+}/results/draw-history/{date:([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])+)}", array(
            "module" => "eurojackpot",
            'controller' => 'euro-jackpot-numbers',
            'action' => 'pastResult',
            'language' => 'en'
        ));
        */
        $this->add("/{language:(es|it|nl|ru)+}/{lottery:(eurojackpot)+}/{result:(resultados|estrazioni|uitslagen|результаты)+}/{lastdraw:(sorteos-anteriores|archivio|trekking-geschiedenislagen|история-розыгрышей)+}/{date:([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])+)}", array(
            "module" => "eurojackpot",
            'controller' => 'euro-jackpot-numbers',
            'action' => 'index',
        ));

        $this->add("/{lottery:(eurojackpot)+}/results/draw-history/:params", array(
            "module" => "eurojackpot",
            'controller' => 'euro-jackpot-numbers',
            'action' => 'pastResult',
            'params' => 2,
            'language' => 'en',
        ));

        //DRAW HISTORY
        $this->add("/{lottery:(eurojackpot)+}/results/draw-history", array(
            "module" => "eurojackpot",
            'controller' => 'euro-jackpot-numbers',
            'action' => 'pastList',
            'language' => 'en'
        ));
    }
}