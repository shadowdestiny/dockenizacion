<?php
namespace EuroMillions\entities;

class EuroMillionsResult 
{
    protected $categories;

    protected $regularNumbers;
    protected $luckyNumbers;
    public function load(IEuroMillionsResultStrategy $strategy)
    {
        list($this->categories, $this->regularNumbers, $this->luckyNumbers) = $strategy->load();
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function getRegularNumbers()
    {
        return $this->regularNumbers;
    }

    public function getLuckyNumbers()
    {
        return $this->luckyNumbers;
    }

    //EMTD acabar la entidad estilo doctrine
}