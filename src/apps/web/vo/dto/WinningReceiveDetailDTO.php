<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;

class WinningReceiveDetailDTO extends DTOBase implements IDto
{

    private $euroMillionsDraw;
    private $bet;
    public $drawDate;
    public $draw;
    public $regularNumbers;
    public $luckyNumbers;
    public $matchNumbers;
    public $matchLucky;
    public $type;

    public function __construct( EuroMillionsDraw $draw, Bet $bet )
    {
        $this->euroMillionsDraw = $draw;
        $this->bet = $bet;
        $this->exChangeObject();
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }

    public function exChangeObject()
    {
        $this->draw = $this->euroMillionsDraw->getResult()->getRegularNumbers() . ' ' . $this->euroMillionsDraw->getResult()->getLuckyNumbers();
        $this->regularNumbers = $this->bet->getPlayConfig()->getLine()->getRegularNumbers();
        $this->luckyNumbers = $this->bet->getPlayConfig()->getLine()->getLuckyNumbers();
        $this->matchNumbers = $this->bet->getMatchNumbers();
        $this->matchLucky = $this->bet->getMatchStars();
        $this->drawDate = $this->bet->getPlayConfig()->getStartDrawDate()->format('Y-m-d');
        $this->type = 'winning_receive';
    }
}