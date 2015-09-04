<?php
namespace EuroMillions\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\components\Md5EmailValidationToken;
use EuroMillions\components\NullPasswordHasher;
use EuroMillions\components\RandomPasswordGenerator;
use EuroMillions\entities\GuestUser;
use EuroMillions\entities\User;
use EuroMillions\interfaces\IAuthStorageStrategy;
use EuroMillions\interfaces\IEmailValidationToken;
use EuroMillions\interfaces\IPasswordHasher;
use EuroMillions\interfaces\IUrlManager;
use EuroMillions\interfaces\IUser;
use EuroMillions\repositories\UserRepository;
use EuroMillions\vo\Email;
use EuroMillions\vo\Password;
use EuroMillions\vo\ServiceActionResult;
use EuroMillions\vo\Url;
use EuroMillions\vo\ValidationToken;
use Money\Currency;
use Money\Money;

class AuthService
{

    /** @var EntityManager */
    private $entityManager;
    /** @var UserRepository */
    private $userRepository;
    private $passwordHasher;
    /** @var IAuthStorageStrategy */
    private $storageStrategy;
    /** @var IUrlManager */
    private $urlManager;
    /** @var LogService */
    private $logService;
    /** @var EmailService */
    private $emailService;

    public function __construct(EntityManager $entityManager, IPasswordHasher $hasher, IAuthStorageStrategy $storageStrategy, IUrlManager $urlManager, LogService $logService, EmailService $emailService)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository('EuroMillions\entities\User');
        $this->passwordHasher = $hasher;
        $this->storageStrategy = $storageStrategy;
        $this->urlManager = $urlManager;
        $this->logService = $logService;
        $this->emailService = $emailService;
    }

    /**
     * @return IUser
     */
    public function getCurrentUser()
    {
        $user_id = $this->storageStrategy->getCurrentUserId();
        $user = $this->userRepository->find($user_id);
        if (!$user) {
            $user = new GuestUser();
            $user->setId($user_id);
        }
        return $user;
    }

    public function setCurrentUser(User $user)
    {
        $this->storageStrategy->setCurrentUserId($user->getId());
    }

    public function logout()
    {
        $this->logService->logOut($this->getCurrentUser());
        $this->storageStrategy->removeCurrentUser();
    }

    public function isLogged()
    {
        $user = $this->getCurrentUser();
        return get_class($user) == 'EuroMillions\entities\User';
    }

    public function check($credentials, $agentIdentificationString)
    {
        $user = $this->userRepository->getByEmail($credentials['email']);
        if (!$user) {
            return false;
        }
        $password_match = $this->passwordHasher->checkPassword($credentials['password'], $user->getPassword()->toNative());
        if ($password_match) {
            $this->setCurrentUser($user);
            if ($credentials['remember']) {
                $user->setRememberToken($agentIdentificationString);
                $this->storageStrategy->storeRemember($user);
                $this->entityManager->flush();
            }
            $this->logService->logIn($user);
        }
        return $password_match;
    }

    public function loginWithRememberMe()
    {
        $user_id = $this->storageStrategy->getRememberUserId();
        /** @var User $user */
        $user = $this->userRepository->find($user_id);
        $token = $this->storageStrategy->getRememberToken();
        if ($user && $token == $user->getRememberToken()->toNative()) {
            $this->storageStrategy->setCurrentUserId($user->getId());
            $this->logService->logRemember($user);
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

    public function register(array $credentials)
    {
        if ($this->userRepository->getByEmail($credentials['email'])) {
            return new ServiceActionResult(false, 'Email already registered');
        }
        $user = new User();
        $email = new Email($credentials['email']);
        $user->initialize([
            'name'     => $credentials['name'],
            'surname'  => $credentials['surname'],
            'email'    => $email,
            'password' => new Password($credentials['password'], $this->passwordHasher),
            'country'  => $credentials['country'],
            'balance'  => new Money(0, new Currency('EUR')),
            'validated' => 0,
            'validation_token' => $this->getEmailValidationToken($email)
        ]);
        $this->userRepository->add($user);

        try{
            $this->entityManager->flush();
            if(!empty($user->getId())) {
                $this->storageStrategy->setCurrentUserId($user->getId());
                $this->logService->logRegistration($user);
                $url = $this->getValidationUrl($user);
                $this->emailService->sendRegistrationMail($user, $url);
                return new ServiceActionResult(true, $user);
            }else{
                return new ServiceActionResult(false, 'Error getting an user');
            }
        } catch(Exception $e) {
            return new ServiceActionResult(false,'An error ocurred saving an user');
        }
    }

    private function getEmailValidationToken(Email $email, IEmailValidationToken $emailValidationTokenGenerator = null)
    {
        $emailValidationTokenGenerator = $this->getEmailValidationTokenGenerator($emailValidationTokenGenerator);
        return $validationToken = new ValidationToken($email, $emailValidationTokenGenerator);
    }

    public function validateEmailToken(User $user, $token, IEmailValidationToken $emailValidationTokenGenerator = null)
    {
        $emailValidationTokenGenerator = $this->getEmailValidationTokenGenerator($emailValidationTokenGenerator);
        if ($emailValidationTokenGenerator->validate($user->getEmail()->toNative(), $token)) {
            $user->setValidated(true);
            $this->entityManager->flush($user);
            return new ServiceActionResult(true, $user);
        } else {
            return new ServiceActionResult(false, "The token is invalid");
        }
    }

    /**
     * @param User $user
     * @return Url
     */
    private function getValidationUrl(User $user)
    {
        return new Url($this->urlManager->get('userAccess/validate/' . $this->getEmailValidationToken(new Email($user->getEmail()->toNative()))));
    }

    /**
     * @param User $user
     * @return Url
     */
    private function getPasswordResetUrl(User $user)
    {
        return new Url($this->urlManager->get('userAccess/passwordReset/'. $this->getEmailValidationToken(new Email($user->getEmail()->toNative()))));
    }

    public function tryLoginWithRemember()
    {
        if ($this->hasRememberMe()) {
            $this->loginWithRememberMe();
        }
    }

    /**
     * @param $token
     * @return ServiceActionResult
     */
    public function resetPassword($token)
    {
        $user = $this->userRepository->getByToken($token);
        if(!empty($user)){
            $passwordGenerator = new RandomPasswordGenerator(new NullPasswordHasher());
            $password = $passwordGenerator->getPassword();
            $this->emailService->sendNewPasswordMail($user, $password);
            return new ServiceActionResult(true, 'Email sent');
        } else {
            return new ServiceActionResult(false, '');
        }
    }

    /**
     * @param Email $email
     * @return ServiceActionResult
     */
    public function forgotPassword(Email $email)
    {
        $user = $this->userRepository->getByEmail($email->toNative());
        if (!empty($user)) {
            $this->emailService->sendPasswordResetMail($user, $this->getPasswordResetUrl($user));
            return new ServiceActionResult(true, 'Email sent');
        } else {
            return new ServiceActionResult(false, 'Email doesn\'t exist');
        }
    }

    /**
     * @param IEmailValidationToken $emailValidationTokenGenerator
     * @return IEmailValidationToken
     */
    private function getEmailValidationTokenGenerator(IEmailValidationToken $emailValidationTokenGenerator = null)
    {
        if (!$emailValidationTokenGenerator) $emailValidationTokenGenerator = new Md5EmailValidationToken();
        return $emailValidationTokenGenerator;
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