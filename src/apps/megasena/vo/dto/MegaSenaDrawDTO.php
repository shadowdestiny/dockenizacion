<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 18/06/18
 * Time: 11:34
 */

namespace EuroMillions\megasena\vo\dto;


use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;

class MegaSenaDrawDTO extends DTOBase implements IDto
{

    /** @var MegaSenaDrawBreakDownDTO $megaSenaDrawBreakDownDTO */
    public $megaSenaDrawBreakDownDTO;

    public $resultNumbers;

    public $luckyNumber;

    public $drawDate;

    public $drawDateParam;

    public $drawDateTranslate;

    public $euromillionsDraw;

    private $emTranslationAdapter;


    public function __construct(EuroMillionsDraw $euroMillionsDraw, EmTranslationAdapter $emTranslationAdapter)
    {
        $this->euromillionsDraw = $euroMillionsDraw;
        $this->emTranslationAdapter = $emTranslationAdapter;
        $this->exChangeObject();
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }

    public function exChangeObject()
    {
        $this->luckyNumber = str_replace('0,','',$this->euromillionsDraw->getResult()->getLuckyNumbers());
        $this->resultNumbers = $this->euromillionsDraw->getResult()->getRegularNumbers().','.$this->luckyNumber;
        $date = $this->euromillionsDraw->getDrawDate();
        $this->drawDate = $this->emTranslationAdapter->query($date->format('l'));
        $this->drawDateTranslate = $date->format($this->emTranslationAdapter->query('dateformat'));
        $this->drawDateParam = $date->format('Y-m-d');
        $this->megaSenaDrawBreakDownDTO = new MegaSenaDrawBreakDownDTO($this->euromillionsDraw->getBreakDown());
    }
}