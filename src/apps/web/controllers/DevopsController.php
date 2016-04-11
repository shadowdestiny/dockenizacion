<?php
namespace EuroMillions\web\controllers;

class DevopsController extends ControllerBase
{
    public function clearApcAction()
    {
        $this->noRender();
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
        if(!in_array($ipClient,explode(',',$config['ips']))){
            $this->response->redirect('/');
        }
    }

}