<?php

namespace EuroMillions\admin\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\UserAdmin;
use EuroMillions\web\repositories\UserAdminRepository;
use Phalcon\Exception;

class UserAdminService
{
    private $entityManager;
    /** @var UserAdminRepository $userAdminRepository */
    private $userAdminRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userAdminRepository = $this->entityManager->getRepository('EuroMillions\web\entities\UserAdmin');
    }

    /**
     * @param array $userAdminData
     *
     * @throws Exception
     */
    public function createUserAdmin(array $userAdminData)
    {
        try {
            $this->entityManager->persist(new UserAdmin($userAdminData));
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new Exception("Was not possible to create User");
        }
    }

    /**
     * @param array $userAdminData
     *
     * @throws Exception
     */
    public function editUserAdminFromAdmin(array $userAdminData)
    {
        try {
            /** @var UserAdmin $user */
            $user = $this->getUserAdminById($userAdminData['id']);
            $user->setName($userAdminData['name']);
            $user->setSurname($userAdminData['surname']);
            $user->setEmail($userAdminData['email']);
            $user->setUseraccess($userAdminData['useraccess']);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new Exception("Was not possible to edit User");
        }
    }

    /**
     * @param $email
     *
     * @return bool
     */
    public function existUserAdmin($email)
    {
        if (empty($this->userAdminRepository->findBy(['email' => $email]))) {
            return false;
        }
        return true;
    }

    /**
     * @return array
     */
    public function getAllAdminUsers()
    {
        return $this->userAdminRepository->findAll();
    }

    public function deleteUserById($id)
    {
        try {
            $this->entityManager->remove($this->getUserAdminById($id));
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new Exception("Was not possible to remove User");
        }
    }

    /**
     * @param $userId
     *
     * @return null|object
     */
    private function getUserAdminById($userId)
    {
        return $this->userAdminRepository->find($userId);
    }
}