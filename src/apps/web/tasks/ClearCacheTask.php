<?php


namespace EuroMillions\web\tasks;


use Doctrine\Common\Cache\RedisCache;

class ClearCacheTask extends TaskBase
{


    public function initialize()
    {
        parent::initialize();
    }

    public function clearAction()
    {
        /** @var \Redis $redis */
        $redis = $this->domainServiceFactory->getServiceFactory()->getDI()->get('redisCache');
        $redis_cache = new RedisCache();
        $redis_cache->setRedis($redis);
        $results = $redis->keys('result_*');
        foreach($results as $result){
            $redis->delete($result);
        }
    }
}