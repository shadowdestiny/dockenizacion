<?php


namespace EuroMillions\web\services\play_strategies;


use EuroMillions\web\exceptions\UnsupportedOperationException;
use EuroMillions\web\interfaces\IPlayStorageStrategy;
use EuroMillions\web\interfaces\IRedis;
use EuroMillions\web\vo\PlayFormToStorage;
use EuroMillions\web\vo\UserId;


class RedisOrderStorageStrategy implements IPlayStorageStrategy
{


    /** @var  IRedis $storage */
    protected $storage;

    protected static $key = 'PlayStore_EMORDER:';

    /**
     * @param IRedis $storage
     */
    public function __construct(IRedis $storage)
    {
        $this->storage = $storage;
    }


    public function save($json, UserId $userId)
    {

    }

    public function saveAll(PlayFormToStorage $data, UserId $userId)
    {
        throw new UnsupportedOperationException();
    }

    public function findByKey($key)
    {
        // TODO: Implement findByKey() method.
    }

    public function delete($key = '')
    {
        // TODO: Implement delete() method.
    }
}