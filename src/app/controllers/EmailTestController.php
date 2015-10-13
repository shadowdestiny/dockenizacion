<?php


namespace EuroMillions\controllers;


use EuroMillions\entities\User;
use EuroMillions\services\ServiceFactory;
use EuroMillions\vo\Email;

class EmailTestController extends PublicSiteControllerBase
{

    /** @var  ServiceFactory */
    private $serviceFactory;

    public function initialize()
    {
        parent::initialize();
        $this->serviceFactory = $this->domainServiceFactory->getServiceFactory();
    }


    public function jackpotAction($userEmail)
    {
        $user = $this->getNewUser($userEmail);
        $this->serviceFactory->getEmailService()->sendJackpotRolloverMail($user,'jackpot-rollover');
    }

    public function lastestResultAction($userEmail)
    {
        $user = $this->getNewUser($userEmail);
        $this->serviceFactory->getEmailService()->sendLatestResultMail($user,'jackpot-rollover');
    }

    public function longPlayAction($userEmail)
    {
        $user = $this->getNewUser($userEmail);
        $this->serviceFactory->getEmailService()->sendLongPlayMail($user,'long-play-is-ended');
    }

    public function lowBalanceAction($userEmail)
    {
        $user = $this->getNewUser($userEmail);
        $this->serviceFactory->getEmailService()->sendLowBalanceMail($user,'low-balance');
    }

    public function winAction($userEmail)
    {
        $user = $this->getNewUser($userEmail);
        $this->serviceFactory->getEmailService()->sendWinEmail($user,'win-email');
    }

    public function winEmailAboveAction($userEmail)
    {
        $user = $this->getNewUser($userEmail);
        $this->serviceFactory->getEmailService()->sendWinEmailAbove($user,'win-email-above-1500');
    }


    private function getNewUser($userEmail)
    {
        $user = new User();
        $user->initialize(
            [
                'name'     => 'test',
                'surname'  => 'test01',
                'email'    => new Email($userEmail),
                'validated' => false,
                'validation_token' => '33e4e6a08f82abb38566fc3bb8e8ef0d'
            ]
        );
        return $user;
    }

}