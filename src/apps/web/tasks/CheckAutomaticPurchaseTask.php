<?php

namespace EuroMillions\web\tasks;

use EuroMillions\web\emailTemplates\CheckAutomaticPurchaseEmailTemplate;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;
use EuroMillions\web\services\EmailService;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\services\factories\ServiceFactory;
use EuroMillions\web\services\PlayService;
use EuroMillions\web\vo\Email;

class CheckAutomaticPurchaseTask extends TaskBase
{

    /** @var  PlayService $playService */
    protected $playService;
    /** @var  EmailService $emailService */
    protected $emailService;

    public function initialize()
    {
        $domainFactory = new DomainServiceFactory($this->getDI(), new ServiceFactory($this->getDI()));
        $serviceFactory = new ServiceFactory($this->getDI());
        $this->emailService =  $serviceFactory->getEmailService();
        $this->playService = $domainFactory->getPlayService();
        parent::initialize();

    }

    public function mainAction()
    {

    }

    public function verifyAction()
    {
        $subscriptionsActives = $this->playService->getAllSubscriptionsActivesByLotteryId(1);
        $subscriptionsPlayed = $this->playService->getAllSubscriptionsPlayedByLotteryId(1);
        if (count($subscriptionsActives) != count($subscriptionsPlayed)) {
            $usersDiff = array_diff_assoc($subscriptionsActives, $subscriptionsPlayed);
            if (count($usersDiff) > 0) {
                $this->sendEmailPurchase($usersDiff, count($subscriptionsActives), count($subscriptionsPlayed));
            }
        }
    }

    private function sendEmailPurchase($usersList, $subscriptionActives, $subscriptionsPlayed)
    {
        $emailBaseTemplate = new EmailTemplate();
        $emailTemplate = new CheckAutomaticPurchaseEmailTemplate($emailBaseTemplate, new JackpotDataEmailTemplateStrategy());
        $emailTemplate->setSubscriptionsPlayed($subscriptionsPlayed);
        $emailTemplate->setSubscriptionsActives($subscriptionActives);
        $emailTemplate->setUsers($usersList);

        $user = new User();
        $user->setEmail(new Email('alerts@panamedia.net'));

        $this->emailService->sendTransactionalEmail($user, $emailTemplate);
    }
}