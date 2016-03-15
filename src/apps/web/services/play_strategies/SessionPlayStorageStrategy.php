<?php


namespace EuroMillions\web\services\play_strategies;

use EuroMillions\web\exceptions\UnsupportedOperationException;
use EuroMillions\web\interfaces\IPlayStorageStrategy;
use EuroMillions\web\vo\PlayFormToStorage;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\UserId;
use Phalcon\Session\AdapterInterface;

class SessionPlayStorageStrategy implements IPlayStorageStrategy
{

    const CURRENT_EMLINE_VAR = 'EM_lines_user';

    private $session;

    public function __construct(AdapterInterface $session)
    {
        $this->session = $session;
    }

    public function saveAll(PlayFormToStorage $data, UserId $userId)
    {
        $this->session->set(self::CURRENT_EMLINE_VAR, $data->toJson());
    }

    /**
     * @param $key
     * @return ActionResult
     */
    public function findByKey($key)
    {
        if (null === $key) {
            return new ActionResult(false, 'Key is invalid in session');
        }

        $result = $this->session->get($key);
        if (!empty($result)) {
            return new ActionResult(true, $result);
        } else {
            return new ActionResult(false, 'No EuroMillions lines in session');
        }
    }

    public function delete($key = '')
    {
        $this->session->destroy();
    }

    public function save($json, UserId $userId)
    {
        throw new UnsupportedOperationException();
    }
}