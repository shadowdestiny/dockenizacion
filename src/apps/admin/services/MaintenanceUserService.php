<?php


namespace EuroMillions\admin\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\admin\vo\ActionResult;
use EuroMillions\web\entities\User;
use EuroMillions\web\vo\UserId;
use Money\Currency;
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
            $user->reChargeWallet($amount);
            $this->entityManager->flush();
            return new ActionResult(true,'Balance was updated correctly');
        }else{
            return new ActionResult(false);
        }
    }

    public function getUser(UserId $userId)
    {
        $user = $this->userRepository->findBy(['id' => $userId]);
        if(!empty($user)) {
            return new ActionResult(true,$user[0]);
        }else{
             return new ActionResult(false);
        }
    }

    public function updateUserData(array $user_data)
    {

        try{
            /** @var User $user */
            $user = $this->userRepository->getByEmail($user_data['email']);

            $user->setName($user_data['name']);
            $user->setSurname($user_data['surname']);
            $user->setEmail($user_data['email']);
            $user->setCountry($user_data['country']);
            $user->setStreet($user_data['street']);
            $user->setZip($user_data['zip']);
            $user->setCity($user_data['city']);
            $user->setPhoneNumber($user_data['phone_number']);
            $user->setBalance(new Money($user_data['balance'],new Currency('EUR')));

            $this->userRepository->add($user);
            $this->entityManager->flush($user);
            return new ActionResult(true,$user);
        }catch(\Exception $e){
            return new ActionResult(false,'Sorry, try it later');
        }
    }
}