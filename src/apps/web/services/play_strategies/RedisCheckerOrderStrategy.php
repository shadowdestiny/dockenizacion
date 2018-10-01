<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 1/10/18
 * Time: 10:07
 */

namespace EuroMillions\web\services\play_strategies;


use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\interfaces\IPlayStorageStrategy;
use EuroMillions\web\vo\PlayFormToStorage;
use Redis;
use RedisException;

class RedisCheckerOrderStrategy implements IPlayStorageStrategy
{

    /** @var  Redis $storage */
    protected $storage;

    protected static $key = 'Order_inprogress:';

    public function __construct(Redis $storage)
    {
        $this->storage = $storage;
    }


    /** @return ActionResult */
    public function save($json, $userId)
    {
        try{
            $this->storage->set($this->getNameKey($userId), $json);
            return new ActionResult(true);
        }catch(RedisException $e){
            return new ActionResult(false,'Unable to save data in storage');
        }
        // TODO: Implement save() method.
    }

    /**
     * @param $christmasTickets
     * @param $userId
     * @return ActionResult
     */
    public function saveChristmas($christmasTickets, $userId)
    {
        throw new \BadMethodCallException();
    }

    /**
     * @param PlayFormToStorage $data
     * @param string $userId
     * @return ActionResult
     */
    public function saveAll(PlayFormToStorage $data, $userId)
    {
        throw new \BadMethodCallException();
    }

    /**
     * @param string $key
     * @return ActionResult
     */
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

    /**
     * @param $key
     * @return ActionResult
     */
    public function findByChristmasKey($key)
    {
        // TODO: Implement findByChristmasKey() method.
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
        return "Order_inprogress:" . $userId;
    }
}