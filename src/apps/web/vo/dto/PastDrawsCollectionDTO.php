<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;
use Money\Currency;
use Money\Money;
use EuroMillions\web\entities\PlayConfig;

class PastDrawsCollectionDTO extends DTOBase implements IDto
{

    public $result;

    public function __construct(array $playConfigs, $tickets=0)
    {
        $this->playConfigs = $playConfigs;
        $this->exChangeObject($tickets);
    }


    public function exChangeObject($tickets=0)
    {
        if($tickets)
        {
            $this->result = $this->createCollectionByLottery();
        }else{
            $this->result = $this->createCollectionByDate();
        }

    }
    public function toArray()
    {
        throw new \Exception('Method not implemented');
    }

    private function createCollectionByDate()
    {
        $collection = [];
        if($this->result) {
            foreach($this->result as $result) {
                $startDrawDate = $result['startDrawDate']->format('Y-m-d');
                $collection['dates'][$startDrawDate][] = new PastDrawDTO($result);
            }
        }
        return $collection;
    }

    private function createCollectionByLottery()
    {
        $result = [];
        if($this->playConfigs) {
            /** @var PlayConfig $playConfig */
            foreach($this->playConfigs as $playConfig) {
                $result[$playConfig[0]->getPlayConfig()->getLottery()->getName()][$playConfig[0]->getPlayConfig()->getStartDrawDate()->format('Y-m-d')][] = new PlayConfigCollectionDTO([$playConfig[0]->getPlayConfig()], new Money((int) 250, new Currency('EUR')));
            }
        }
        return $result;
    }

}