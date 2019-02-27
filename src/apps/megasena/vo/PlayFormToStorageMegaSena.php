<?php

/**
 * Created by PhpStorm.
 * User: lmarin
 * Date: 27/02/19
 * Time: 04:04 PM
 */


namespace EuroMillions\megasena\vo;

use EuroMillions\web\vo\PlayFormToStorage;

class PlayFormToStorageMegaSena extends PlayFormToStorage
{

    public function toJson()
    {
        /** @var EuroMillionsLine[] $arr_lines */
        $arr_lines = $this->euroMillionsLines;
        $this->euroMillionsLines = null;
        foreach($arr_lines as $lines){
            $this->euroMillionsLines['bets'][] = [
                  'regular' => $lines->getRegularNumbersArray(),
            ];
        }
        return get_object_vars($this);
    }

}