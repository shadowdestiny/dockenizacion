<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use EuroMillions\web\vo\dto\base\DTOBase;
use Phalcon\Di;

class EuroMillionsDrawDTO extends DTOBase implements IDto
{

    private $euroMillionsDraw;
    public $id;
    public $regularNumbers;
    public $regularNumbersArray;
    public $luckyNumbers;
    public $luckyNumbersArray;
    public $jackpot;
    public $drawDate;
    public $drawDateParam;
    public $drawDateTranslate;
    public $euroMillionsDrawBreakDownDTO;


    public function __construct( EuroMillionsDrawBreakDownDTO $euroMillionsDrawBreakDownDTO, EuroMillionsDraw $euroMillionsDraw )
    {
        $this->euroMillionsDrawBreakDownDTO = $euroMillionsDrawBreakDownDTO;
        $this->euroMillionsDraw = $euroMillionsDraw;
        $this->exChangeObject();
    }


    public function exChangeObject()
    {
        $this->id = $this->euroMillionsDraw->getId();
        $this->regularNumbers = $this->euroMillionsDraw->getResult()->getRegularNumbers();
        $this->regularNumbersArray = $this->euroMillionsDraw->getResult()->getRegularNumbersArray();
        $this->luckyNumbers = $this->euroMillionsDraw->getResult()->getLuckyNumbers();
        $this->luckyNumbersArray = $this->euroMillionsDraw->getResult()->getLuckyNumbersArray();
        $this->jackpot = $this->euroMillionsDraw->getJackpot()->getAmount();
        $date = $this->euroMillionsDraw->getDrawDate();
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($di->get('session'), $di->get('request')))->get(), $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));
        $this->drawDate = $translationAdapter->query($date->format('l'));
        $this->drawDateTranslate = $date->format($translationAdapter->query('dateformat'));
        $this->drawDateParam = $date->format('Y-m-d');
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}