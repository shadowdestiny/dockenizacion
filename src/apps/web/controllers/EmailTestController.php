<?php


namespace EuroMillions\web\controllers;


use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\web\components\RandomPasswordGenerator;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\JackpotRolloverEmailTemplate;
use EuroMillions\web\emailTemplates\LatestResultsEmailTemplate;
use EuroMillions\web\emailTemplates\LongPlayEndedEmailTemplate;
use EuroMillions\web\emailTemplates\LowBalanceEmailTemplate;
use EuroMillions\web\emailTemplates\WinEmailTemplate;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;
use EuroMillions\web\services\email_templates_strategies\LatestResultsDataEmailTemplateStrategy;
use EuroMillions\web\services\email_templates_strategies\LongPlayEndedDataEmailTemplateStrategy;
use EuroMillions\web\services\email_templates_strategies\LowBalanceDataEmailTemplateStrategy;
use EuroMillions\web\services\email_templates_strategies\NullEmailTemplateDataStrategy;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDataDTO;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\Url;
use EuroMillions\web\vo\UserId;
use Money\Currency;
use Money\Money;

class EmailTestController extends PublicSiteControllerBase
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
        'send-new-password'
    ];


    public function indexAction()
    {

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
                    $emailTemplate->setResultAmount(new Money(10000000, new Currency('EUR')));
                }
                $this->sendEmail($nameTemplate, $url, $emailTemplate);
            }
        } else {
            $emailTemplate = $this->getInstanceDecorator($template);
            if($emailTemplate instanceof WinEmailTemplate) {
                $emailTemplate->setUser($this->user);
                $emailTemplate->setResultAmount(new Money(10000000, new Currency('EUR')));
            }
            if($emailTemplate instanceof LatestResultsEmailTemplate) {
                $draw = $this->lotteriesDataService->getBreakDownDrawByDate('EuroMillions',new \DateTime());
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
        $config = $dispatcher->getDI()->get('globalConfig')['ips'];
        $ipClient = $this->request->getClientAddress();
        if(!in_array($ipClient,explode(',',$config['ips']))){
            $this->response->redirect('/');
        }
    }

    /**
     * @param $template
     * @param $user
     * @return string
     */
    private function getInstanceDecorator($template)
    {

        $instance = null;
        $emailTemplate = new EmailTemplate();

        switch($template){
            case 'jackpot-rollover':
                $instance = new JackpotRolloverEmailTemplate($emailTemplate, new JackpotDataEmailTemplateStrategy());
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
                $instance = new WinEmailTemplate($emailTemplate, new NullEmailTemplateDataStrategy());
                break;
            case 'win-email-above-1500':
                $instance = new WinEmailTemplate($emailTemplate, new NullEmailTemplateDataStrategy());
                break;
        }
        return $instance;
    }

    private function getNewUser($userEmail = null)
    {
        $user = new User();
        if(empty($userEmail)) {
            $user = $this->userService->getUser(new UserId('832063cb-a559-11e5-b358-0242ac110002'));
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

    private function getVarsFromTemplate($template)
    {

        $next_draw_day = $this->lotteriesDataService->getNextDateDrawByLottery('EuroMillions');
        $time_config = $this->getDI()->get('globalConfig')['retry_validation_time'];
        $draw_day_format_one = $next_draw_day->format('l');
        $draw_day_format_two = $next_draw_day->format('j F Y');
        $jackpot_amount = $this->lotteriesDataService->getNextJackpot('EuroMillions');
        /** @var EuroMillionsDrawBreakDown $emBreakDownData */
        $draw = $this->lotteriesDataService->getBreakDownDrawByDate('EuroMillions',new \DateTime());
        $breakdown_list = null;
        if($draw->success()){
            $break_down_list = new EuroMillionsDrawBreakDownDTO($draw->getValues()->getBreakDown());
        }

        $break_down_json = [];
        /** @var EuroMillionsDrawBreakDownDataDTO[] $break_down_dto_list */
        foreach($break_down_list as $break_down) {
            $break_down_json[] = $break_down;
        }
        /** @var EuroMillionsLine $draw_result */
        $draw_result = $this->lotteriesDataService->getLastResult('EuroMillions');
        $last_draw_date = $this->lotteriesDataService->getLastDrawDate('EuroMillions')->format('j F Y');
        $config = $this->getDI()->get('config');
        $date = new \DateTime();

        $vars = [
            'jackpot-rollover' => [
                'subject' => 'Jackpot',
                'vars' =>
                    [
                        [
                            'name'    => 'jackpot',
                            'content' => $jackpot_amount->getAmount()/100
                        ],
                        [
                            'name'    => 'draw_day_format_one',
                            'content' => $draw_day_format_one
                        ],
                        [
                            'name'    => 'draw_day_format_two',
                            'content' => $draw_day_format_two,
                        ],
                        [
                            'name'    => 'time_closed',
                            'content' => $time_config['time'] . ' CET'
                        ],
                        [
                            'name'    => 'url_play',
                            'content' => $config->domain['url'] . 'play'
                        ],
                        [
                            'name' => 'date_header',
                            'content' => $date->format('j M Y')
                        ]
                    ]
            ],
            'latest-results' => [
                'subject' => 'Latest results',
                'vars' =>
                    [
                        [
                            'name'    => 'breakdown',
                            'content' => $break_down_json
                        ],
                        [
                            'name'    => 'jackpot',
                            'content' => $jackpot_amount->getAmount()/100
                        ],
                        [
                            'name'    => 'draw_date',
                            'content' => $last_draw_date
                        ],
                        [
                            'name'    => 'regular_numbers',
                            'content' => $draw_result['regular_numbers']
                        ],
                        [
                            'name'    => 'lucky_numbers',
                            'content' => $draw_result['lucky_numbers']
                        ],
                        [
                            'name' => 'date_header',
                            'content' => $date->format('j M Y')
                        ]
                    ]
            ],
            'low-balance' => [
                    'subject' => 'Low balance',
                    'vars' =>
                    [
                            [
                                'name'    => 'jackpot',
                                'content' => $jackpot_amount->getAmount() /100
                            ],
                            [
                                'name'    => 'draw_day_format_one',
                                'content' => $draw_day_format_one
                            ],
                            [
                                'name'    => 'draw_day_format_two',
                                'content' => $draw_day_format_two,
                            ],
                            [
                                'name' => 'url_add_funds',
                                'content' => $config->domain['url'] . 'account/wallet'
                            ],
                        [
                            'name' => 'date_header',
                            'content' => $date->format('j M Y')
                        ]
                    ]
            ],
            'long-play-is-ended' => [
                'subject' => 'Your long play is ended',
                'vars' =>
                    [
                        [
                            'name'    => 'jackpot',
                            'content' => $jackpot_amount->getAmount() /100
                        ],
                        [
                            'name'    => 'url_play',
                            'content' => $config->domain['url'] . 'play'
                        ],
                        [
                            'name' => 'date_header',
                            'content' => $date->format('j M Y')
                        ]
                    ]
            ],
            'win-email' => [
                'subject' => 'Congratulations',
                'vars' =>
                    [
                        [
                            'name'    => 'user_name',
                            'content' => $this->user->getName()
                        ],
                        [
                            'name'    => 'winning',
                            'content' => '165.000.000'
                        ],
                        [
                            'name'    => 'url_play',
                            'content' => $config->domain['url'] . 'play'
                        ],
                        [
                            'name'    => 'url_account',
                            'content' => $config->domain['url'] . 'account/wallet'
                        ],
                        [
                            'name' => 'date_header',
                            'content' => $date->format('j M Y')
                        ]
                    ]
            ],
            'win-email-above-1500' => [
                'subject' => 'Congratulations',
                'vars' =>
                    [
                        [
                            'name'    => 'user_name',
                            'content' => $this->user->getName()
                        ],
                        [
                            'name'    => 'winning',
                            'content' => '165.000.000'
                        ],
                        [
                            'name'    => 'url_play',
                            'content' => $config->domain['url'] . 'play'
                        ],
                        [
                            'name'    => 'url_account',
                            'content' => $config->domain['url'] . 'account/wallet'
                        ],
                        [
                            'name' => 'date_header',
                            'content' => $date->format('j M Y')
                        ]
                    ]
            ]
       ];

       return $vars[$template];
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