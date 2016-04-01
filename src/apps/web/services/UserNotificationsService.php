<?php


namespace EuroMillions\web\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\web\repositories\UserNotificationsRepository;

class UserNotificationsService
{

    private $entityManager;

    /** @var  UserService $userService */
    private $userService;

    /** @var UserNotificationsRepository  */
    private $userNotificationsRepository;


    public function __construct( EntityManager $entityManager, UserService $userService )
    {
        $this->entityManager = $entityManager;
        $this->userService = $userService;
        $this->userNotificationsRepository = $entityManager->getRepository('EuroMillions\web\entities\UserNotifications');
    }

}