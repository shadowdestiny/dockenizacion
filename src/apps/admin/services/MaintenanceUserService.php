<?php


namespace EuroMillions\admin\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\entities\User;
use EuroMillions\web\repositories\UserRepository;
use Money\Money;


class MaintenanceUserService
{

    private $entityManager;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository('EuroMillions\web\entities\User');
    }

    public function listAllUsers()
    {
        /** @var User[] $result */
        $result = $this->userRepository->findAll();
        if (count($result)) {
            return new ActionResult(true, $result);
        } else {
            return new ActionResult(false);
        }
    }

    public function updateBalance($userId, Money $amount)
    {
        /** @var User $user */
        $user = $this->userRepository->find($userId);
        if (null !== $user) {
            $user->reChargeWallet($amount);
            $this->entityManager->flush();
            return new ActionResult(true, 'Balance was updated correctly');
        } else {
            return new ActionResult(false);
        }
    }

    public function getUser($userId)
    {
        $user = $this->userRepository->findBy(['id' => $userId]);
        if (null !== $user) {
            return new ActionResult(true, $user[0]);
        } else {
            return new ActionResult(false);
        }
    }

    public function updateUserData(\Phalcon\Http\Request $request)
    {
        try {
            /** @var User $user */
            $user = $this->userRepository->getByEmail($request->getPost('email'));
            $user->setName($request->getPost('name'));
            $user->setSurname($request->getPost('surname'));
            $user->setEmail($request->getPost('email'));
            $user->setCountry($request->getPost('country'));
            $user->setStreet($request->getPost('street'));
            $user->setZip($request->getPost('zip'));
            $user->setCity($request->getPost('city'));
            $user->setPhoneNumber($request->getPost('phone_number'));
            //EMTD refactor this to use the wallet $user->setBalance(new Money($user_data['balance'], new Currency('EUR')));
           $this->userRepository->add($user);
            $this->entityManager->flush($user);
            return new ActionResult(true, $user);
        } catch (\Exception $e) {
            return new ActionResult(false, 'Sorry, try it later');
        }
    }
}