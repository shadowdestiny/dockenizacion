<?php
namespace EuroMillions\web\services;

use EuroMillions\web\interfaces\ILogger;
use EuroMillions\web\interfaces\IUser;

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

    public function error($msg, $action)
    {
        $logger = $this->getLogger('userAuth');
        $logger->error(json_encode(['action' => $action, 'message' => $msg]));
    }

    public function logUpdateAccountDetails(IUser $user)
    {
        $this->logUserAccountAction($user, 'update');
    }

    public function logChangeCurrency(IUser $user, $currency)
    {
        $this->logUserAccountAction($user, 'updateCurrency_withCurrency'.$currency);
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
        $logger->info(json_encode(['action' => $action, 'user' => $user->getId()]));
    }


    /**
     * @param IUser $user
     * @param $action
     */
    private function logUserAccountAction(IUser $user, $action)
    {
        $logger = $this->getLogger('userAccount');
        $logger->info(json_encode( ['action' => $action, 'user' => $user->getId() ]));
    }

}