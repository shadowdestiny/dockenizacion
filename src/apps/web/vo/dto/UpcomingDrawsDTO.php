<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;
use Money\Currency;
use Money\Money;

class UpcomingDrawsDTO extends DTOBase implements IDto
{
    /**
     * @var array
     */
    private $playConfigs;

    public $result;

    public function __construct(array $playConfigs)
    {
        $this->playConfigs = $playConfigs;
        $this->exChangeObject();
    }


    public function exChangeObject()
    {
        $this->result = $this->createCollectionByDate();
    }

    public function toArray()
    {
        throw new \Exception('Operation not implemented');
    }

    private function createCollectionByDate()
    {
        $result = [];
        if($this->playConfigs) {
            /** @var PlayConfig $playConfig */
            foreach($this->playConfigs as $playConfig) {
                $startDrawDate = $playConfig->getStartDrawDate()->format('Y-m-d');
                $result[$startDrawDate][] = new PlayConfigCollectionDTO([$playConfig], new Money((int) 250, new Currency('EUR')));
            }
        }
        return $result;
    }

}