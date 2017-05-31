<?php

namespace EuroMillions\web\controllers;

use Doctrine\Common\Cache\RedisCache;

class DevopsController extends ControllerBase
{
    public function clearApcAction()
    {
        $this->noRender();
        /** @var \Redis $redis */
        $redis = $this->domainServiceFactory->getServiceFactory()->getDI()->get('redisCache');
        $redis_cache = new RedisCache();
        $redis_cache->setRedis($redis);
        $results = $redis->keys('result_*');
        foreach ($results as $result) {
            $redis->delete($result);
        }
        apc_clear_cache();
        apc_clear_cache('user');
        echo 'Cache cleared';
    }


    /**
     * @param \Phalcon\Mvc\Dispatcher $dispatcher
     * @return bool
     */
    public function beforeExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
        $config = $dispatcher->getDI()->get('config')['ips'];
        $ipClient = $this->request->getClientAddress();
        if (!in_array($ipClient, explode(',', $config['ips']))) {
            $this->response->redirect('/');
        }
    }

}