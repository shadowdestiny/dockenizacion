<?php


namespace EuroMillions\web\controllers;


use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\EmailTemplateDecorator;
use EuroMillions\web\emailTemplates\JackpotRolloverEmailTemplate;
use EuroMillions\web\emailTemplates\LatestResultsEmailTemplate;
use EuroMillions\web\emailTemplates\LongPlayEndedEmailTemplate;
use EuroMillions\web\emailTemplates\LowBalanceEmailTemplate;
use EuroMillions\web\emailTemplates\WelcomeEmailTemplate;
use EuroMillions\web\emailTemplates\WinEmailAboveTemplate;
use EuroMillions\web\emailTemplates\WinEmailTemplate;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;
use EuroMillions\web\services\email_templates_strategies\LatestResultsDataEmailTemplateStrategy;
use EuroMillions\web\services\email_templates_strategies\LongPlayEndedDataEmailTemplateStrategy;
use EuroMillions\web\services\email_templates_strategies\LowBalanceDataEmailTemplateStrategy;
use EuroMillions\web\services\email_templates_strategies\NullEmailTemplateDataStrategy;
use EuroMillions\web\services\email_templates_strategies\WinEmailAboveDataEmailTemplateStrategy;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\Url;
use Money\Currency;
use Money\Money;

class EmailController extends PublicSiteControllerBase
{

    /** @var  User  */
    protected $user;

    private static $config = [
        'mandrill_api_key' => 'YNzChiS2tFA5s9-SiZ0ydw',
        'from_name' => 'Euromillions.com',
        'from_address' => 'noreply@euromillions.com'
    ];

    private static $emailTemplates = [
        'jackpot-rollover',
        'latest-results',
        'low-balance',
        'long-play-is-ended',
        'win-email',
        'win-email-above-1500',
        'register',
        'send-password-request',
        'send-new-password',
        'welcome',
    ];


    public function indexAction()
    {
        $this->view->pick('email-test/index');
    }

    public function sendAction()
    {
        $userEmail = $this->request->getPost('user-email');
        $template = $this->request->getPost('template');
        if(!empty($userEmail))
        $this->user = $this->getNewUser($userEmail);
        $url = new Url('http://localhost:8080');
        if($template == 'all') {
            foreach(self::$emailTemplates as $nameTemplate) {
                $emailTemplate = $this->getInstanceDecorator($nameTemplate);
                if($emailTemplate instanceof WinEmailTemplate) {
                    $emailTemplate->setUser($this->user);
                    $emailTemplate->setWinningLine('1,2,3,4,5 (1,11)');
                    $emailTemplate->setStarBalls(2);
                    $emailTemplate->setNummBalls(3);
                    $emailTemplate->setResultAmount(new Money(10000000, new Currency('EUR')));
                }
                $this->sendEmail($nameTemplate, $url, $emailTemplate);
            }
        } else {
            $emailTemplate = $this->getInstanceDecorator($template);
            if($emailTemplate instanceof WinEmailTemplate) {
                $emailTemplate->setUser($this->getNewUser('raul.mesa@panamedia.net'));
                $emailTemplate->getUser()->setUserCurrency(new Currency('USD'));
                $emailTemplate->setWinningLine('1,2,3,4,5 (1,11)');
                $emailTemplate->setStarBalls(2);
                $emailTemplate->setNummBalls(3);
                $emailTemplate->setResultAmount(new Money(5000, new Currency('EUR')));
            }
            if($emailTemplate instanceof LatestResultsEmailTemplate) {
                $draw = $this->lotteryService->getLastDrawWithBreakDownByDate('EuroMillions',new \DateTime());
                $break_down_list = null;
                if($draw->success()){
                    $break_down_list = new EuroMillionsDrawBreakDownDTO($draw->getValues()->getBreakDown());
                }
                $emailTemplate->setBreakDownList($break_down_list);
            }
            $this->sendEmail($template, $url, $emailTemplate);
        }

        $this->view->pick('email-test/index');
    }

    /**
     * @param \Phalcon\Mvc\Dispatcher $dispatcher
     * @return bool
     */
    public function beforeExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
        $config = $dispatcher->getDI()->get('config')['ips'];
        $ipClient = $this->request->getClientAddress(true);
        if(!in_array($ipClient,explode(',',$config['ips']))){
            $this->response->redirect('/');
        }
    }

    /**
     * @param $template
     * @param $user
     * @return EmailTemplateDecorator
     */
    private function getInstanceDecorator($template)
    {

        $instance = null;
        $emailTemplate = new EmailTemplate();

        switch($template){
            case 'jackpot-rollover':
                $instance = new JackpotRolloverEmailTemplate($emailTemplate, new JackpotDataEmailTemplateStrategy());
                $instance->setUser($this->getNewUser('raul.mesa@panamedia.net'));
                $instance->getUser()->setUserCurrency(new Currency('USD'));
                break;
            case 'latest-results':
                $instance = new LatestResultsEmailTemplate($emailTemplate, new LatestResultsDataEmailTemplateStrategy());
                break;
            case 'low-balance':
                $instance = new LowBalanceEmailTemplate($emailTemplate, new LowBalanceDataEmailTemplateStrategy());
                break;
            case 'long-play-is-ended':
                $instance = new LongPlayEndedEmailTemplate($emailTemplate, new LongPlayEndedDataEmailTemplateStrategy());
                break;
            case 'win-email':
                $winEmailAboveDataEmailTemplateStrategy = new WinEmailAboveDataEmailTemplateStrategy(new Money(5000, new Currency('EUR')), new Currency('USD'));
                $instance = new WinEmailTemplate($emailTemplate, $winEmailAboveDataEmailTemplateStrategy);
                $instance->setWinningLine('1,2,3,4,5 (1,11)');
                $instance->setStarBalls('2');
                $instance->setNummBalls('3');
                break;
            case 'win-email-above-1500':
                $winEmailAboveDataEmailTemplateStrategy = new WinEmailAboveDataEmailTemplateStrategy(new Money(100000, new Currency('EUR')), new Currency('USD'));
                $instance = new WinEmailAboveTemplate($emailTemplate, $winEmailAboveDataEmailTemplateStrategy);
                $instance->setUser($this->getNewUser('raul.mesa@panamedia.net'));
                $instance->getUser()->setUserCurrency(new Currency('USD'));
                $instance->setWinningLine('1,2,3,4,5 (1,11)');
                $instance->setStarBalls('2');
                $instance->setNummBalls('3');
                $instance->setResultAmount(new Money(100000, new Currency('EUR')));
                break;
            case 'welcome':
                $instance = new WelcomeEmailTemplate($emailTemplate, new JackpotDataEmailTemplateStrategy());
                $instance->setUser($this->getNewUser('raul.mesa@panamedia.net'));
                break;

        }
        return $instance;
    }

    private function getNewUser($userEmail = null)
    {
        $user = new User();
        if(empty($userEmail)) {
            $user = $this->userService->getUser('832063cb-a559-11e5-b358-0242ac110002');
        } else {
            $user->initialize(
                [
                    'name'     => 'test',
                    'surname'  => 'test01',
                    'email'    => new Email($userEmail),
                    'validated' => false,
                    'validation_token' => '33e4e6a08f82abb38566fc3bb8e8ef0d'
                ]
            );
        }
        return $user;
    }

    /**
     * @param $nameTemplate
     * @param $url
     * @param $emailTemplate
     */
    private function sendEmail($nameTemplate, $url, $emailTemplate)
    {
        if ($nameTemplate == 'register') {
            $url = new Url('http://localhost:8080/user-access/validate/3c44633d83a5780f5bac7dcc6eccb0ab');
            $this->domainServiceFactory->getServiceFactory()->getEmailService(null, self::$config)->sendRegistrationMail($this->user, $url);
        } else if ($nameTemplate == 'send-password-request') {
            $url = new Url('http://localhost:8080/user-access/passwordReset/3c44633d83a5780f5bac7dcc6eccb0ab');
            $this->domainServiceFactory->getServiceFactory()->getEmailService(null, self::$config)->sendPasswordResetMail($this->user, $url);
        } else if ($nameTemplate == 'send-new-password') {
            //
        } else {
            $this->domainServiceFactory->getServiceFactory()->getEmailService(null, self::$config)->sendTransactionalEmail($this->user, $emailTemplate);
        }
    }
}