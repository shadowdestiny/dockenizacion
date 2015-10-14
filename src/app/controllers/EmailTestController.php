<?php


namespace EuroMillions\controllers;


use EuroMillions\entities\User;
use EuroMillions\services\ServiceFactory;
use EuroMillions\vo\Email;

class EmailTestController extends PublicSiteControllerBase
{

    /** @var  ServiceFactory */
    private $serviceFactory;

    private static $config = [
        'mandrill_api_key' => 'YNzChiS2tFA5s9-SiZ0ydw',
        'from_name' => 'Euromillions.com',
        'from_address' => 'noreply@euromillions.com'
    ];

    public function initialize()
    {
        parent::initialize();
        $this->serviceFactory = $this->domainServiceFactory->getServiceFactory();
    }


    public function indexAction()
    {

    }

    public function sendAction()
    {
        $vars =  [
            'tags' => 'test'
        ];
        $userEmail = $this->request->getPost('user-email');
        $template = $this->request->getPost('template');
        $user = $this->getNewUser($userEmail);
        $vars['subject'] = $this->getSubject($template,$user);
        $this->serviceFactory->getEmailService(null,self::$config)->sendTransactionalEmail($user,$template,$vars);
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
    private function getSubject($template,User $user)
    {
        $subject = '';
        $draw_date = $this->domainServiceFactory->getLotteriesDataService()->getLastDrawDate('EuroMillions')->format('Y-m-d');
        $user_name = $user->getName();
        switch($template){
            case 'jackpot-rollover':
                $subject = 'The Jackpot has surpassed *&euro; 120 millions*';
                break;
            case 'latest-results':
                $subject = 'Latest draw results: ' .$draw_date;
                break;
            case 'low-balance':
                $subject = 'Your balance is too low, we are unable to process your play';
                break;
            case 'long-play-is-ended':
                $subject = 'You just finished a long term play';
                break;
            case 'win-email':
                $subject = 'Congratulations ' .$user_name . ', you won the lottery';
                break;
            case 'win-email-above-1500':
                $subject = 'Congratulations ' . $user_name . ', you won the lottery';
                break;
        }

        return $subject;
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