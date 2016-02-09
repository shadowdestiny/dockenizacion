<?php


namespace EuroMillions\web\services\play_strategies;


use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\exceptions\UnsupportedOperationException;
use EuroMillions\web\interfaces\IPlayStorageStrategy;
use EuroMillions\web\interfaces\IRedis;
use EuroMillions\web\vo\PlayFormToStorage;
use EuroMillions\web\vo\UserId;
use RedisException;


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
        try{
            $this->storage->save($this->getNameKey($userId), $json);
            return new ActionResult(true);
        }catch(RedisException $e){
            return new ActionResult(false,'Unable to save data in storage');
        }
    }

    public function saveAll(PlayFormToStorage $data, UserId $userId)
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

    public function delete($key = '')
    {
        // TODO: Implement delete() method.
    }

    private function getNameKey(UserId $userId)
    {
        return self::$key . $userId->id();
    }
}