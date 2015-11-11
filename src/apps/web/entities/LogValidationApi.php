<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\IEntity;

class LogValidationApi extends EntityBase implements IEntity
{

    protected $id;

    protected $id_provider;

    protected $id_ticket;

    protected $status;

    protected $response;

    protected $received;


    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getIdProvider()
    {
        return $this->id_provider;
    }

    /**
     * @param mixed $id_provider
     */
    public function setIdProvider($id_provider)
    {
        $this->id_provider = $id_provider;
    }

    /**
     * @return mixed
     */
    public function getIdTicket()
    {
        return $this->id_ticket;
    }

    /**
     * @param $id_ticket
     */
    public function setIdTicket($id_ticket)
    {
        $this->id_ticket = $id_ticket;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getReceived()
    {
        return $this->received;
    }

    /**
     * @param mixed $received
     */
    public function setReceived($received)
    {
        $this->received = $received;
    }

}