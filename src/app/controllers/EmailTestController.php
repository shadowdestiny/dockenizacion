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

    private static $tags = [
        'tags' => 'test-template'
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
        $userEmail = $this->request->getPost('user-email');
        $template = $this->request->getPost('template');
        $user = $this->getNewUser($userEmail);
        $this->serviceFactory->getEmailService(null,self::$config)->sendTransactionalEmail($user,$template,self::$tags);
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