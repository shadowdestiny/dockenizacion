<?php


namespace EuroMillions\admin\vo\dto;

use EuroMillions\admin\interfaces\IDto;
use EuroMillions\admin\vo\dto\base\DTOBase;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\dto\PowerBallDrawBreakDownDTO;
use EuroMillions\megasena\vo\dto\MegaSenaDrawBreakDownDTO;
use EuroMillions\megamillions\vo\dto\MegaMillionsDrawBreakDownDTO;
use EuroMillions\eurojackpot\vo\dto\EuroJackpotDrawBreakDownDTO;

class DrawDTO extends DTOBase implements IDto
{

    /** @var EuroMillionsDraw $draw */
    private $draw;
    public $id;

    public $draw_date;
    public $draw_date_formatted;
    public $jackpot;
    public $regular_numbers;
    public $lucky_numbers;
    public $category_name;

    public $break_down;

    public function __construct(EuroMillionsDraw $draw)
    {
        $this->draw = $draw;
        $this->exChangeObject();
    }

    /**
     * @return mixed
     */
    public function getDrawDate()
    {
        return $this->draw_date;
    }

    /**
     * @param mixed $draw_date
     */
    public function setDrawDate($draw_date)
    {
        $this->draw_date = $draw_date;
    }

    /**
     * @return mixed
     */
    public function getJackpot()
    {
        return $this->jackpot;
    }

    /**
     * @param mixed $jackpot
     */
    public function setJackpot($jackpot)
    {
        $this->jackpot = $jackpot;
    }

    /**
     * @return mixed
     */
    public function getRegularNumbers()
    {
        return $this->regular_numbers;
    }

    /**
     * @param mixed $regular_numbers
     */
    public function setRegularNumbers($regular_numbers)
    {
        $this->regular_numbers = $regular_numbers;
    }

    /**
     * @return mixed
     */
    public function getLuckyNumbers()
    {
        return $this->lucky_numbers;
    }

    /**
     * @param mixed $lucky_numbers
     */
    public function setLuckyNumbers($lucky_numbers)
    {
        $this->lucky_numbers = $lucky_numbers;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function exChangeObject()
    {
        $this->id = $this->draw->getId();
        $this->draw_date  = $this->draw->getDrawDate()->format('j M Y');
        $this->draw_date_formatted = $this->draw->getDrawDate()->format('Y-m-d');
        $this->jackpot = $this->draw->getJackpot()->getAmount() /100;
        $this->regular_numbers = $this->draw->getResult()->getRegularNumbersArray();
        $this->lucky_numbers = $this->draw->getResult()->getLuckyNumbersArray();
        $this->setBreakDown();
    }

    public function toArray()
    {
        return json_decode(json_encode($this),TRUE);
    }

    /**
     * @return EuroMillionsDrawBreakDown
     */
    public function getBreakDown()
    {
        return $this->break_down;
    }

    private function setBreakDown()
    {
        if ($this->draw->getLottery()->isMegaMillions()) {
            $this->break_down = new MegaMillionsDrawBreakDownDTO($this->draw->getBreakDown());
        }

        if ($this->draw->getLottery()->isMegaSena()) {
            $this->break_down = new MegaSenaDrawBreakDownDTO($this->draw->getBreakDown());
        }

        if ($this->draw->getLottery()->isEuroJackpot()) {
            $this->break_down = new EuroJackpotDrawBreakDownDTO($this->draw->getBreakDown());
        }
        /*
        if ($this->draw->getLottery()->isSuperEnalotto()) {
            $this->break_down = new SuperEnalottoDrawBreakDownDTO($this->draw->getBreakDown());
        }
        */
        if ($this->draw->getLottery()->isPowerBall()) {
            $this->break_down = new PowerBallDrawBreakDownDTO($this->draw->getBreakDown());
        }

        if ($this->draw->getLottery()->isEuroMillions()) {
            $this->break_down = new EuroMillionsDrawBreakDownDTO($this->draw->getBreakDown());
        }
    }

    public function sanetizeWinnersBreakDown()
    {
        $this->break_down->category_one->winners = str_replace('.','',$this->break_down->category_one->winners);
        $this->break_down->category_two->winners = str_replace('.','',$this->break_down->category_two->winners);
        $this->break_down->category_three->winners = str_replace('.','',$this->break_down->category_three->winners);
        $this->break_down->category_four->winners = str_replace('.','',$this->break_down->category_four->winners);
        $this->break_down->category_five->winners = str_replace('.','',$this->break_down->category_five->winners);
        $this->break_down->category_six->winners = str_replace('.','',$this->break_down->category_six->winners);
        $this->break_down->category_seven->winners = str_replace('.','',$this->break_down->category_seven->winners);
        $this->break_down->category_eight->winners = str_replace('.','',$this->break_down->category_eight->winners);
        $this->break_down->category_nine->winners = str_replace('.','',$this->break_down->category_nine->winners);
        $this->break_down->category_ten->winners = str_replace('.','',$this->break_down->category_ten->winners);
        $this->break_down->category_eleven->winners = str_replace('.','',$this->break_down->category_eleven->winners);
        $this->break_down->category_twelve->winners = str_replace('.','',$this->break_down->category_twelve->winners);
        $this->break_down->category_thirteen->winners = str_replace('.','',$this->break_down->category_thirteen->winners);
    }

    public function checkResultAndCleanValuesIfAreEmpty()
    {
        foreach(explode(',',$this->regular_numbers) as $regular_number) {
            if( $regular_number == null ) {
                $this->regular_numbers = [];
                break;
            }
        }
        foreach(explode(',',$this->lucky_numbers) as $lucky_number) {
            if( $lucky_number == null ) {
                $this->lucky_numbers = [];
                break;
            }
        }
    }

    public function getResultsArray()
    {
        return [
            'id' => $this->id,
            'lottery' => $this->draw->getLottery()->getName(),
            'date' => $this->draw_date_formatted,
            'jackpot' => $this->jackpot,
            'results' => $this->draw->getResult()->toArray(),
            'break_down' => $this->break_down->toArray()
        ];
    }

    public function getPrizesArray()
    {
        return [
            'id' => $this->id,
            'lottery' => $this->draw->getLottery()->getName(),
            'date' => $this->draw_date_formatted,
            'prizes' => $this->break_down->toArray()
        ];
    }

    public function getWinnersArray()
    {
        return [
            'id' => $this->id,
            'lottery' => $this->draw->getLottery()->getName(),
            'date' => $this->draw_date_formatted,
            'winners' => $this->break_down->toArray()
        ];
    }
}