<?php
namespace EuroMillions\entities;

class EuroMillionsResult extends LotteryResult
{
    protected $categories;

    protected $regular_numbers;
    protected $lucky_numbers;

    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    public function setRegularNumbers($regular_numbers)
    {
        $this->regular_numbers = $regular_numbers;
    }

    public function setLuckyNumbers($lucky_numbers)
    {
        $this->lucky_numbers = $lucky_numbers;
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function getRegularNumbers()
    {
        return $this->regular_numbers;
    }

    public function getLuckyNumbers()
    {
        return $this->lucky_numbers;
    }

}