<?php


namespace EuroMillions\services\play_strategies;

use EuroMillions\interfaces\IPlayStorageStrategy;
use EuroMillions\interfaces\ISession;
use EuroMillions\vo\PlayFormToStorage;
use EuroMillions\vo\ServiceActionResult;

class SessionPlayStorageStrategy implements IPlayStorageStrategy
{

    const CURRENT_EMLINE_VAR = 'EM_lines_user';

    private $session;

    public function __construct(ISession $session)
    {
        $this->session = $session;
    }

    public function saveAll(PlayFormToStorage $data)
    {
        $this->session->set(self::CURRENT_EMLINE_VAR, $data->toJson());
    }

    /**
     * @param $key
     * @return ServiceActionResult
     */
    public function findByKey($key)
    {
        if(empty($key)) return new ServiceActionResult(false, 'Key is invalid in session');

        $result = $this->session->get($key);
        if(!empty($result)){
            return new ServiceActionResult(true,$result);
        }else{
            return new ServiceActionResult(false,'No EuroMillions lines in session');
        }
    }

    public function delete($key = '')
    {
        $this->session->destroy();
    }
}