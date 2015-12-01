<?php


namespace EuroMillions\web\controllers;


use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\IEmailTemplate;
use EuroMillions\web\emailTemplates\JackpotRolloverEmailTemplate;
use EuroMillions\web\emailTemplates\LatestResultsEmailTemplate;
use EuroMillions\web\emailTemplates\LongPlayEndedEmailTemplate;
use EuroMillions\web\emailTemplates\LowBalanceEmailTemplate;
use EuroMillions\web\emailTemplates\WinEmailTemplate;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\ServiceFactory;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDataDTO;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\EuroMillionsLine;
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
        'win-email-above-1500'
    ];


    public function indexAction()
    {

    }

    public function sendAction()
    {
        $userEmail = $this->request->getPost('user-email');
        $template = $this->request->getPost('template');
        $emailTemplate = new EmailTemplate();
        $this->user = $this->getNewUser($userEmail);
        if($template == 'all') {
            foreach(self::$emailTemplates as $nameTemplate) {
                $emailTemplate = $this->getInstanceDecorator($nameTemplate,$emailTemplate);
                if($emailTemplate instanceof WinEmailTemplate) {
                    $emailTemplate->setUser($this->user);
                    $emailTemplate->setResultAmount(new Money(10000000, new Currency('EUR')));
                }
                $this->domainServiceFactory->getServiceFactory()->getEmailService(null,self::$config)->sendTransactionalEmail($this->user,$emailTemplate);
            }
        } else {
            $emailTemplate = $this->getInstanceDecorator($template,$emailTemplate);
            if($emailTemplate instanceof WinEmailTemplate) {
                $emailTemplate->setUser($this->user);
                $emailTemplate->setResultAmount(new Money(10000000, new Currency('EUR')));
            }
            $this->domainServiceFactory->getServiceFactory()->getEmailService(null,self::$config)->sendTransactionalEmail($this->user,$emailTemplate);
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
    private function getInstanceDecorator($template,IEmailTemplate $emailTemplate)
    {

        $instance = null;
        switch($template){
            case 'jackpot-rollover':
                $instance = new JackpotRolloverEmailTemplate($emailTemplate);
                break;
            case 'latest-results':
                $instance = new LatestResultsEmailTemplate($emailTemplate);
                break;
            case 'low-balance':
                $instance = new LowBalanceEmailTemplate($emailTemplate);
                break;
            case 'long-play-is-ended':
                $instance = new LongPlayEndedEmailTemplate($emailTemplate);
                break;
            case 'win-email':
                $instance = new WinEmailTemplate($emailTemplate);
                break;
            case 'win-email-above-1500':
                $instance = new WinEmailTemplate($emailTemplate);
                break;
        }
        return $instance;
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
            $break_down_list = new EuroMillionsDrawBreakDownDTO($draw->getValues());
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
}