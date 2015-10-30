<?php


namespace EuroMillions\admin\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\admin\vo\ActionResult;
use EuroMillions\web\entities\User;
use EuroMillions\web\vo\UserId;
use Money\Money;


class MaintenanceUserService
{

    private $entityManager;

    private $userRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository('EuroMillions\web\entities\User');
    }

    public function listAllUsers()
    {
        /** @var User[]  $result */
        $result = $this->userRepository->findAll();
        if(!empty($result)) {
            return new ActionResult(true,$result);
        } else {
            return new ActionResult(false);
        }
    }

    public function updateBalance(UserId $userId, Money $amount)
    {
        /** @var User $user */
        $user = $this->userRepository->find($userId);
        if(!empty($user)){
            $user->setBalance($user->getBalance()->add($amount));
            $this->entityManager->flush();
            return new ActionResult(true,'Balance was updated correctly');
        }else{
            return new ActionResult(false);
        }
    }
}