<?php


namespace EuroMillions\web\tasks;


use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\LatestResultsEmailTemplate;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\services\BetService;
use EuroMillions\web\services\email_templates_strategies\LatestResultsDataEmailTemplateStrategy;
use EuroMillions\web\services\LotteryService;
use EuroMillions\web\services\UserService;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;

class LatestresultTask extends TaskBase
{

    /** @var  LotteryService */
    private $lotteryService;
    /** @var  UserService */
    private $userService;
    /** @var  BetService */
    private $betService;


    public function initialize(LotteryService $lotteryService = null,
                               UserService $userService = null,
                               BetService $betService = null
                              )
    {
        parent::initialize();
        $this->userService = $userService ? $userService : $this->domainServiceFactory->getUserService();
        $this->lotteryService = $lotteryService ? $lotteryService : $this->domainServiceFactory->getLotteryService();
        $this->betService = $betService ? $betService : $this->domainServiceFactory->getBetService();
    }


    public function resultsReminderWhenPlayedAction( $args = null, IEmailTemplateDataStrategy $IEmailTemplateDataStrategy = null)
    {
        if(null != $args) {
            $drawDate = new \DateTime($args[0]);
        } else {
            /** @var EuroMillionsDraw $draw */
            $drawDate = $this->lotteryService->getLastDrawDate('EuroMillions');
        }
        if($IEmailTemplateDataStrategy == null ) {
            $IEmailTemplateDataStrategy = new LatestResultsDataEmailTemplateStrategy();
        }
        $draw = $this->lotteryService->getLastDrawWithBreakDownByDate('EuroMillions',$drawDate);
        $break_down_list = new EuroMillionsDrawBreakDownDTO($draw->getValues()->getBreakDown());
        $emailTemplate = new EmailTemplate();
        $emailTemplate = new LatestResultsEmailTemplate($emailTemplate, $IEmailTemplateDataStrategy);
        $emailTemplate->setBreakDownList($break_down_list);
        $result = $this->betService->getBetsPlayedLastDraw($drawDate);
        if (null != $result) {
            $this->lotteryService->sendResultLotteryToUsersWithBets($result,$emailTemplate);
        }
        $regularNumbers = $draw->getValues()->getResult()->getRegularNumbersArray();
        $luckyNumbers = $draw->getValues()->getResult()->getLuckyNumbersArray();
        if((count(array_filter($regularNumbers))!=0) && (count(array_filter($luckyNumbers))!=0)) {
            $users = $this->userService->getAllUsers();
            $this->lotteryService->sendResultLotteryToUsers($users,$emailTemplate);
        }
    }
}