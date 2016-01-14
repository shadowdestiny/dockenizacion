<?php


namespace EuroMillions\web\services\play_strategies;


use EuroMillions\shared\components\PhalconRedisWrapper;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\IPlayStorageStrategy;
use EuroMillions\web\interfaces\IRedis;
use EuroMillions\web\vo\PlayFormToStorage;
use EuroMillions\web\vo\ActionResult;
use EuroMillions\web\vo\UserId;
use RedisException;

class RedisPlayStorageStrategy implements IPlayStorageStrategy
{

    /** @var  IRedis $storage */
    protected $storage;

    protected static $key = 'PlayStore_EMLINES:';

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
        try{
            $this->storage->save($this->getNameKey($userId), $data->toJson());
            return new ActionResult(true);
        }catch(RedisException $e){
            return new ActionResult(false,'Unable to save data in storage');
        }
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

    private function getNameKey(UserId $userId)
    {
        return "PlayStore_EMLINES:" . $userId->id();
    }
}