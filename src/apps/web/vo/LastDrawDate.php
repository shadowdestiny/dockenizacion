<?php


namespace EuroMillions\web\vo;


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
                $weeks = $this->frequency / 2;
                $tmp = strtotime('+'.$weeks.'week',strtotime($this->startDrawDate));
                $dayName = date('w',$tmp) == 2 ? 'Friday' : 'Tuesday';
                $tmpLast = strtotime('last '.$dayName,$tmp);
            }
            $dateTimeLast->setTimestamp($tmpLast);
            return $dateTimeLast->format('Y-m-d H:i:s');
        }catch(\Exception $e){
            throw new InvalidArgumentException('Invalid date to calculate last date draw');
        }
    }

}