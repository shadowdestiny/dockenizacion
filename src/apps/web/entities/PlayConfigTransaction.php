<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 26/06/18
 * Time: 10:21
 */

namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\IEntity;

class PlayConfigTransaction extends EntityBase implements IEntity, \JsonSerializable
{

    protected $id;

    protected $transaction;

    protected $playConfig;

    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }


    /**
     * @return mixed
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param mixed $transaction
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @return mixed
     */
    public function getPlayConfig()
    {
        return $this->playConfig;
    }

    /**
     * @param mixed $playConfig
     */
    public function setPlayConfig($playConfig)
    {
        $this->playConfig = $playConfig;
    }

    public function getId()
    {
        // TODO: Implement getId() method.
    }

}