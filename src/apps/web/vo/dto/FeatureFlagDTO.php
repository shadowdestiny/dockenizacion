<?php


namespace EuroMillions\web\vo\dto;

use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;


class FeatureFlagDTO extends DTOBase implements IDto
{
    private $name;
    private $description;
    private $status;
    private $created_at;
    private $updated_at;

    public function __construct(array $data = null)
    {
        if($data !== null) {
            $this->name = $data['name'];
            $this->description = $data['description'];
            $this->status = $data['status'];
            $this->created_at = $this->convertDatesToTimestamps($data['created_at']);
            $this->updated_at = $this->convertDatesToTimestamps($data['updated_at']);
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getStatus($boolean = true)
    {
        if($boolean){
            return $this->status;
        }
        else{
            return $this->convertStatus($this->status);
        }
    }

    public function setStatus($status)
    {
        if($status === "1" || $status === 1 || $status === true){
            $status = true;
        }
        else{
            $status = false;
        }

        $this->status =  $status;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($timestamp)
    {
        $this->created_at = $timestamp;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setUpdatedAt($timestamp)
    {
        $this->updated_at = $timestamp;
    }

    public function exChangeObject()
    {
        throw new \Exception('Method not implemented');

    }

    public function toArray()
    {
        throw new \Exception('Method not implemented');
    }

    private function convertDatesToTimestamps($fromISOStringDate)
    {
        $datetime = new \DateTime($fromISOStringDate);
        return $datetime->getTimestamp();
    }

    private function convertStatus($status)
    {
        if($status === true){
            return 1;
        }
        else{
            return 0;
        }
    }

}