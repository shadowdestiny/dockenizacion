<?php


namespace EuroMillions\web\vo;


use InvalidArgumentException;

class LastDrawDateEuroJackpot extends LastDrawDate
{


    /**
     * @return string
     * @throws \Exception
     */
    public function getLastDrawDate()
    {
        $dateTimeLast = new \DateTime();
        try{
            if($this->frequency == 1){
                $tmpLast = strtotime($this->startDrawDate);
            }else{
                $weeks = $this->frequency / 1;
                $tmp = strtotime('+'.$weeks.'week',strtotime($this->startDrawDate));
                $dayName = 'Friday';
                $tmpLast = strtotime('last '.$dayName,$tmp);
            }
            $dateTimeLast->setTimestamp($tmpLast);
            return $dateTimeLast->format('Y-m-d H:i:s');
        }catch(\Exception $e){
            throw new InvalidArgumentException('Invalid date to calculate last date draw');
        }
    }


}