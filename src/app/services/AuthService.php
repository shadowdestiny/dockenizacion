<?php
namespace EuroMillions\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\entities\User;
use EuroMillions\interfaces\IAuthStorageStrategy;
use EuroMillions\interfaces\IPasswordHasher;
use EuroMillions\repositories\UserRepository;
use EuroMillions\vo\UserId;

class AuthService
{

    /** @var EntityManager */
    private $entityManager;
    /** @var UserRepository */
    private $userRepository;
    private $passwordHasher;
    /** @var IAuthStorageStrategy */
    private $storageStrategy;

    public function __construct(EntityManager $entityManager, IPasswordHasher $hasher, IAuthStorageStrategy $storageStrategy)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository('EuroMillions\entities\User');
        $this->passwordHasher = $hasher;
        $this->storageStrategy = $storageStrategy;
    }

    public function getCurrentUser()
    {
        return $this->storageStrategy->getCurrentUser();
    }

    public function isLogged()
    {
        $user = $this->getCurrentUser();
        return get_class($user) == 'EuroMillions\entities\User';
    }

    public function check($credentials, $agentIdentificationString)
    {
        $user = $this->userRepository->getByEmail($credentials['email']);
        if(!$user) {
            return false;
        }
        $password_match = $this->passwordHasher->checkPassword($credentials['password'], $user->getPassword()->password());
        if ($password_match && $credentials['remember']) {
            $user->setRememberToken($agentIdentificationString);
            $this->storageStrategy->storeRemember($user);
            $this->entityManager->flush();
        }
        return $password_match;
    }

    public function loginWithRememberMe()
    {
        $user_id = new UserId($this->storageStrategy->getRememberUserId());
        /** @var User $user */
        $user = $this->userRepository->find($user_id);
        $token = $this->storageStrategy->getRememberToken();
        if ($user && $token == $user->getRememberToken()->token()) {
            $this->storageStrategy->setCurrentUser($user);
            return true;
        } else {
            $this->storageStrategy->removeRemember();
            return false;
        }
    }

    public function hasRememberMe()
    {
        return $this->storageStrategy->hasRemember();
    }


//    /**
//     * Check if the session has a remember me cookie
//     *
//     * @return boolean
//     */
//    public function hasRememberMe()
//    {
//        return $this->cookies->has('RMU');
//    }

//    /**
//     * Returns the current identity
//     *
//     * @return array
//     */
//    public function getIdentity()
//    {
//        return $this->session->get(self::SESSION_VAR_NAME);
//    }
//    /**
//     * Returns the current identity
//     *
//     * @return string
//     */
//    public function getName()
//    {
//        $identity = $this->session->get(self::SESSION_VAR_NAME);
//        return $identity['name'];
//    }
//    /**
//     * Removes the user identity information from session
//     */
//    public function remove()
//    {
//        if ($this->cookies->has('RMU')) {
//            $this->cookies->get('RMU')->delete();
//        }
//        if ($this->cookies->has('RMT')) {
//            $this->cookies->get('RMT')->delete();
//        }
//        $this->session->remove(self::SESSION_VAR_NAME);
//    }
//    /**
//     * Auths the user by his/her id
//     *
//     * @param int $id
//     */
//    public function authUserById($id)
//    {
//        $user = $this->usersQueryDAO->getById($id);
//        $this->session->set(self::SESSION_VAR_NAME, array(
//            'id'   => $user->id,
//            'name' => $user->name,
//        ));
//    }
//    /**
//     * Get the entity related to user in the active identity
//     * @return User
//     */
//    public function getUser()
//    {
//        $identity = $this->session->get(self::SESSION_VAR_NAME);
//        if (isset($identity['id'])) {
//            $user = $this->usersQueryDAO->getById($identity['id']);
//            return $user;
//        }
//        return false;
//    }
//    /**
//     * @param User $user
//     * @param $user_agent
//     * @return string
//     */
//    private function getToken(User $user, $user_agent)
//    {
//        $token = md5($user->username . $user->password . $user_agent);
//        return $token;
//    }
//}
}