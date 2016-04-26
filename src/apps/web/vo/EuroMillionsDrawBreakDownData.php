<?php


namespace EuroMillions\web\vo;


class EuroMillionsDrawBreakDownData
{

    protected $name;

    protected $lottery_prize;

    protected $winners;

    protected $category_name;

    /**
     * @return mixed
     */
    public function getCategoryName()
    {
        return $this->category_name;
    }

    /**
     * @param mixed $category_name
     */
    public function setCategoryName($category_name)
    {
        $this->category_name = $category_name;
    }



    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;

    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getLotteryPrize()
    {
        return $this->lottery_prize;
    }

    /**
     * @param mixed $lottery_prize
     */
    public function setLotteryPrize($lottery_prize)
    {
        $this->lottery_prize = $lottery_prize;
    }

    /**
     * @return mixed
     */
    public function getWinners()
    {
        return $this->winners;
    }

    /**
     * @param mixed $winners
     */
    public function setWinners($winners)
    {
        $this->winners = $winners;
    }

    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'winners' => $this->getWinners(),
            'lottery_prize_amount' => $this->getLotteryPrize()->getAmount(),
            'lottery_prize_currency_name' => $this->getLotteryPrize()->getCurrency()->getName()
        ];
    }

}