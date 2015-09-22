<?php


namespace EuroMillions\services\play_strategies;


use EuroMillions\interfaces\IPlayStorageStrategy;
use EuroMillions\interfaces\IRedis;
use EuroMillions\vo\PlayFormToStorage;
use EuroMillions\vo\ServiceActionResult;
use EuroMillions\vo\UserId;
use Phalcon\Cache\Backend\Redis;
use RedisException;

class RedisPlayStorageStrategy implements IPlayStorageStrategy
{

    /** @var  Redis */
    protected $storage;

    protected $userId;

    protected static $key = 'PlayStore_EMLINES:';
    /**
     * @param IRedis $storage
     * @param UserId $userId
     */
    public function __construct(IRedis $storage, UserId $userId)
    {
        $this->storage = $storage;
        $this->userId = $userId;
    }

    public function saveAll(PlayFormToStorage $data)
    {
        try{
            $this->storage->save($this->getNameKey(), $data->toJson());
            return new ServiceActionResult(true);
        }catch(RedisException $e){
            return new ServiceActionResult(false,'Unable to save data in storage');
        }

    }

    public function findByKey($key)
    {
        try{
            $result = $this->storage->get($key);
            if(empty($result)){
                return new ServiceActionResult(false,'Key not found');
            }else{
                return new ServiceActionResult(true, $result);
            }
        }catch(RedisException $e){
            return new ServiceActionResult(false,'An error ocurred while find key');
        }
    }

    public function delete($key = '')
    {
        if(empty($key)){
            return new ServiceActionResult(false,'Invalid key');
        }else{
            try{
                $this->storage->delete($key);
                return new ServiceActionResult(true);
            }catch(RedisException $e){
                return new ServiceActionResult(false,'An exception ocurred while delete key');
            }
        }
    }

    private function getNameKey()
    {
        return "PlayStore_EMLINES:" . $this->userId->id();
    }
}