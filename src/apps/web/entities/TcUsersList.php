<?php

namespace EuroMillions\web\entities;

use EuroMillions\web\interfaces\IEntity;

class TcUsersList extends EntityBase implements IEntity
{
    protected $id;
    protected $user;
    protected $trackingCode;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTrackingCode()
    {
        return $this->trackingCode;
    }

    /**
     * @param mixed $trackingCode
     */
    public function setTrackingCode($trackingCode)
    {
        $this->trackingCode = $trackingCode;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}