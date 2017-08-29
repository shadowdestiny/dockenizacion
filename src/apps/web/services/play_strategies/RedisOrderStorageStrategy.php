<?php


namespace EuroMillions\web\services\play_strategies;


use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\exceptions\UnsupportedOperationException;
use EuroMillions\web\interfaces\IPlayStorageStrategy;
use EuroMillions\web\vo\PlayFormToStorage;
use RedisException;
use Redis;


class RedisOrderStorageStrategy implements IPlayStorageStrategy
{


    /** @var  Redis $storage */
    protected $storage;

    protected static $key = 'PlayStore_EMORDER:';

    protected static $christmasKey = 'PlayStore_CRORDER:';

    public function __construct(Redis $storage)
    {
        $this->storage = $storage;
    }


    public function save($json, $userId)
    {
        try{
            $this->storage->set($this->getNameKey($userId), $json);
            return new ActionResult(true);
        }catch(RedisException $e){
            return new ActionResult(false,'Unable to save data in storage');
        }
    }

    public function saveChristmas($json, $userId)
    {
        try{
            $this->storage->set($this->getChristmasKey($userId), $json);
            return new ActionResult(true);
        }catch(RedisException $e){
            return new ActionResult(false,'Unable to save data in storage');
        }
    }

    public function saveAll(PlayFormToStorage $data, $userId)
    {
        throw new UnsupportedOperationException();
    }

    public function findByKey($key)
    {
        try{
            $result = $this->storage->get(self::$key.$key);
            if(empty($result)){
                return new ActionResult(false,'Key not found');
            }else{
                return new ActionResult(true, $result);
            }
        }catch(RedisException $e){
            return new ActionResult(false,'An error ocurred while find key');
        }
    }

    public function findByChristmasKey($key)
    {
        try{
            $result = $this->storage->get(self::$christmasKey.$key);
            if(empty($result)){
                return new ActionResult(false,'Key not found');
            }else{
                return new ActionResult(true, $result);
            }
        }catch(RedisException $e){
            return new ActionResult(false,'An error ocurred while find key');
        }
    }

    public function delete($key = '')
    {
        if(empty($key)){
            return new ActionResult(false,'Invalid key');
        }else{
            try{
                $this->storage->delete(self::$key.$key);
                return new ActionResult(true);
            }catch(RedisException $e){
                return new ActionResult(false,'An exception ocurred while delete key');
            }
        }

    }

    private function getNameKey($userId)
    {
        return self::$key . $userId;
    }

    private function getChristmasKey($userId)
    {
        return self::$christmasKey . $userId;
    }
}