<?php


namespace EuroMillions\vo;


use Doctrine\Instantiator\Exception\InvalidArgumentException;

class LastDrawDate
{

    protected $startDrawDate;

    protected $frequency;

    public function __construct($startDrawDate,$frequency)
    {
        $this->startDrawDate = $startDrawDate;
        $this->frequency = $frequency;
    }

    /**
     * @return \DateTime
     */
    public function getLastDrawDate()
    {
        $dateTimeLast = new \DateTime();
        try{
            if($this->frequency == 1){
                $tmpLast = strtotime($this->startDrawDate);
            }else{
                $tmpLast = strtotime('+'.$this->frequency.'week',strtotime($this->startDrawDate));
            }
            $dateTimeLast->setTimestamp($tmpLast);
            return $dateTimeLast->format('Y-m-d H:i:s');
        }catch(\Exception $e){
            throw new InvalidArgumentException('Invalid date to calculate last date draw');
        }


    }

}