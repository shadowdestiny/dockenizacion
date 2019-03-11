<?php


namespace EuroMillions\web\vo\dto;

use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;
use EuroMillions\web\components\DateTimeUtil;


class FeatureFlagDTO extends DTOBase implements IDto
{
    private $name = null;
    private $description = null;
    private $status = false;
    private $created_at = null;
    private $updated_at = null;

    public function __construct(array $data = null)
    {
        if(isset($data['Item'])) {
            $data = $data['Item'];
        }

        if($data !== null) {
            if(isset($data['name']))
                $this->name = $data['name'];

            if(isset($data['description']))
                $this->description = $data['description'];

            if(isset($data['status']))
                $this->status = $data['status'];

            if(isset($data['created_at']))
                $this->created_at = DateTimeUtil::convertISODateToTimestamp($data['created_at']);

            if(isset($data['updated_at']))
                $this->updated_at = DateTimeUtil::convertISODateToTimestamp($data['updated_at']);
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