<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;

class PastDrawsCollectionDTO extends DTOBase implements IDto
{

    public $result;

    public function __construct(array $result)
    {
        $this->result = $result;
        $this->exChangeObject();
    }

    public function exChangeObject()
    {
        $this->result = $this->createCollectionByDate();
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

}