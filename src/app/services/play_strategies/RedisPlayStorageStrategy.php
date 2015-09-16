<?php


namespace EuroMillions\services\play_strategies;


use EuroMillions\interfaces\IPlayStorageStrategy;
use EuroMillions\interfaces\IRedis;
use EuroMillions\vo\ServiceActionResult;
use Exception;
use Phalcon\Cache\Backend\Redis;
use RedisException;

class RedisPlayStorageStrategy implements IPlayStorageStrategy
{

    const EMLINE_FETCH_KEY = 'PlayStore_EMLINES';

    /** @var  Redis */
    protected $storage;

    /**
     * @param IRedis $storage
     */
    public function __construct(IRedis $storage)
    {
        $this->storage = $storage;
    }

    public function saveAll(array $euroMillionsLine)
    {
        try{
            $this->storage->save(self::EMLINE_FETCH_KEY, $euroMillionsLine);
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
}