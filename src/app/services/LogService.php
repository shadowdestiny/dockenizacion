<?php
namespace EuroMillions\services;

use EuroMillions\interfaces\ILogger;
use EuroMillions\interfaces\IUser;

class LogService
{
    /** @var ILogger[] */
    private $logs;

    private $loggerFactory;

    public function __construct(LoggerFactory $loggerFactory)
    {
        $this->loggerFactory = $loggerFactory;
    }

    public function logIn(IUser $user)
    {
        $this->logUserAuthAction($user, 'login');
    }

    public function logOut(IUser $user)
    {
        $this->logUserAuthAction($user, 'logout');
    }

    public function logRemember(IUser $user)
    {
        $this->logUserAuthAction($user, 'login with remember me');
    }

    public function logRegistration(IUser $user)
    {
        $this->logUserAuthAction($user, 'registration');
    }


    private function getLogger($logName)
    {
        if(empty($this->logs[$logName])) {
            $logger = $this->loggerFactory->get($logName);
            $this->logs[$logName] = $logger;
        }
        return $this->logs[$logName];
    }

    /**
     * @param IUser $user
     * @param $action
     */
    private function logUserAuthAction(IUser $user, $action)
    {
        $logger = $this->getLogger('userAuth');
        $logger->info(json_encode(['action' => $action, 'user' => $user->getId()->id()]));
    }
}