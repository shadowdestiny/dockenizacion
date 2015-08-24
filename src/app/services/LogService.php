<?php
namespace EuroMillions\services;

use EuroMillions\entities\User;
use EuroMillions\interfaces\ILogger;

class LogService
{
    /** @var ILogger[] */
    private $logs;

    private $loggerFactory;

    public function __construct(LoggerFactory $loggerFactory)
    {
        $this->loggerFactory = $loggerFactory;
    }

    public function logIn(User $user)
    {
        $logger = $this->getLogger('userAuth');
        $logger->info(json_encode(['action' => 'login', 'user'=> $user->getId()->id()]));
    }

    public function logOut(User $user)
    {
        $logger = $this->getLogger('userAuth');
        $logger->info(json_encode(['action' => 'logout', 'user'=> $user->getId()->id()]));
    }

    private function getLogger($logName)
    {
        if(empty($this->logs[$logName])) {
            $logger = $this->loggerFactory->get($logName);
            $this->logs[$logName] = $logger;
        }
        return $this->logs[$logName];
    }
}