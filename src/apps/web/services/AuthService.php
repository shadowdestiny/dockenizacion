<?php
namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\shared\vo\Wallet;
use EuroMillions\web\components\Md5EmailValidationToken;
use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\web\components\PhpassWrapper;
use EuroMillions\web\components\RandomPasswordGenerator;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\ResetPasswordEmailTemplate;
use EuroMillions\web\emailTemplates\WelcomeEmailTemplate;
use EuroMillions\web\entities\GuestUser;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\IAuthStorageStrategy;
use EuroMillions\web\interfaces\IEmailValidationToken;
use EuroMillions\web\interfaces\IPasswordHasher;
use EuroMillions\shared\config\interfaces\IUrlManager;
use EuroMillions\web\interfaces\IUser;
use EuroMillions\web\repositories\UserRepository;
use EuroMillions\web\services\email_templates_strategies\NullEmailTemplateDataStrategy;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\Password;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\Url;
use EuroMillions\web\vo\UserId;
use EuroMillions\web\vo\ValidationToken;
use Money\Currency;
use Symfony\Component\Config\Definition\Exception\Exception;

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
    /** @var  UserService */
    private $userService;

    public function __construct(EntityManager $entityManager, IPasswordHasher $hasher, IAuthStorageStrategy $storageStrategy, IUrlManager $urlManager, LogService $logService, EmailService $emailService, UserService $userService)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository('EuroMillions\web\entities\User');
        $this->passwordHasher = $hasher;
        $this->storageStrategy = $storageStrategy;
        $this->urlManager = $urlManager;
        $this->logService = $logService;
        $this->emailService = $emailService;
        $this->userService = $userService;
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
        return get_class($user) === 'EuroMillions\web\entities\User';
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
            return new ActionResult(false, 'Email already registered. Try to use a different email. Or have you <a href="user-access/forgotPassword">forgot your password?</a>');
        }
        $user = new User();
        $email = new Email($credentials['email']);
        $user->initialize([
            'name'             => $credentials['name'],
            'surname'          => $credentials['surname'],
            'email'            => $email,
            'password'         => new Password($credentials['password'], $this->passwordHasher),
            'country'          => $credentials['country'],
            'wallet'           => new Wallet(),
            'validated'        => 0,
            'validation_token' => $this->getEmailValidationToken($email),
            'user_currency'    => new Currency('EUR')
        ]);

        $this->userRepository->add($user);

        try {
            $this->entityManager->flush();
            if (null !== $user->getId()) {
                $this->storageStrategy->setCurrentUserId($user->getId());
                $this->logService->logRegistration($user);
                $url = $this->getValidationUrl($user);
                $emailTemplate = new EmailTemplate();
                $emailTemplate = new WelcomeEmailTemplate($emailTemplate, new NullEmailTemplateDataStrategy());
                $emailTemplate->setUser($user);
                $this->emailService->sendTransactionalEmail($user, $emailTemplate);
                //user notifications default
                $this->userService->initUserNotifications($user->getId());
                return new ActionResult(true, $user);
            } else {
                return new ActionResult(false, 'Error getting an user');
            }
        } catch (Exception $e) {
            return new ActionResult(false, 'An error ocurred saving an user');
        }
    }

    public function registerFromCheckout(array $credentials, UserId $userId)
    {
        if (!$this->getCurrentUser() instanceof GuestUser) {
            return new ActionResult(false, 'Error getting user');
        }
        if ($this->userRepository->getByEmail($credentials['email'])) {
            return new ActionResult(false, 'Email already registered. Try to use a different email.');
        }
        try{
            $user = new User();
            $email = new Email($credentials['email']);
            $user->initialize([
                'name'     => $credentials['name'],
                'surname'  => $credentials['surname'],
                'email'    => $email,
                'password' => new Password($credentials['password'], $this->passwordHasher),
                'country'  => $credentials['country'],
                'wallet'  => new Wallet(),
                'validated' => 0,
                'validation_token' => $this->getEmailValidationToken($email),
                'user_currency' => new Currency('EUR')
            ]);
            $user->setId($userId);
            $this->userRepository->addWithId($user);
            $this->entityManager->flush();
            if (null !== $user->getId()) {
                $this->storageStrategy->setCurrentUserId($userId);
                $this->logService->logRegistration($user);
                $this->userService->initUserNotifications($user->getId());
                $emailTemplate = new EmailTemplate();
                $emailTemplate = new WelcomeEmailTemplate($emailTemplate, new NullEmailTemplateDataStrategy());
                $emailTemplate->setUser($user);
                $this->emailService->sendTransactionalEmail($user, $emailTemplate);
                return new ActionResult(true, $user);
            } else {
                return new ActionResult(false, 'Error getting an user');
            }
        } catch(\Exception $e) {
            return new ActionResult(false,$e->getMessage());
        }
    }

    private function getEmailValidationToken(Email $email, IEmailValidationToken $emailValidationTokenGenerator = null)
    {
        $emailValidationTokenGenerator = $this->getEmailValidationTokenGenerator($emailValidationTokenGenerator);
        return $validationToken = new ValidationToken($email, $emailValidationTokenGenerator);
    }

    public function validateEmailToken($token, IEmailValidationToken $emailValidationTokenGenerator = null)
    {
        $emailValidationTokenGenerator = $this->getEmailValidationTokenGenerator($emailValidationTokenGenerator);
        $user = $this->userRepository->getByToken($token);
        if (!empty($user)) {
            if ($emailValidationTokenGenerator->validate($user->getEmail()->toNative(), $token)) {
                $user->setValidated(true);
                $this->entityManager->flush($user);
                return new ActionResult(true, $user);
            } else {
                return new ActionResult(false, "The token is invalid");
            }
        } else {
            return new ActionResult(false, "The token is invalid");
        }
    }

    /**
     * @param User $user
     * @return Url
     */
    private function getValidationUrl(User $user)
    {
        return new Url($this->urlManager->get('/validate/' . $this->getEmailValidationToken(new Email($user->getEmail()->toNative()))));
    }

    /**
     * @param User $user
     * @return Url
     */
    private function getPasswordResetUrl(User $user)
    {
        return new Url($this->urlManager->get('/userAccess/passwordReset/' . $this->getEmailValidationToken(new Email($user->getEmail()->toNative()))));
    }

    public function tryLoginWithRemember()
    {
        if ($this->hasRememberMe()) {
            $this->loginWithRememberMe();
        }
    }

    /**
     * @param $token
     * @return ActionResult
     */
    public function resetPassword($token)
    {
        $user = $this->userRepository->getByToken($token);
        if (!empty($user)) {
            try {
//                $passwordGenerator = new RandomPasswordGenerator(new NullPasswordHasher());
//                $password = $passwordGenerator->getPassword();
//                $this->emailService->sendNewPasswordMail($user, $password);
//                $user->setPassword(new Password($password->toNative(), new PhpassWrapper()));
//                $this->entityManager->flush($user);
                return new ActionResult(true, 'Email sent');
            } catch (Exception $e) {
                return new ActionResult(false, '');
            }

        } else {
            return new ActionResult(false, '');
        }
    }

    public function samePassword(User $user, $password)
    {
        $password_match = $this->passwordHasher->checkPassword($password, $user->getPassword()->toNative());
        if ($password_match) {
            return new ActionResult(true);
        } else {
            return new ActionResult(false, 'The old password doesn\'t exist');
        }
    }

    public function updatePassword(User $user, $new_password)
    {
        try {
            $password = new Password($new_password, $this->passwordHasher);
            $user->setPassword($password);
            $this->userRepository->add($user);
            $this->entityManager->flush($user);
            $this->emailService->sendTransactionalEmail($user, new ResetPasswordEmailTemplate(new EmailTemplate(), new NullEmailTemplateDataStrategy()));
            return new ActionResult(true, 'Your password was changed correctly');
        } catch (\Exception $e) {
            return new ActionResult(false);
        }
    }

    /**
     * @param Email $email
     * @return ActionResult
     */
    public function forgotPassword(Email $email)
    {
        $user = $this->userRepository->getByEmail($email->toNative());
        if (!empty($user)) {
            $this->emailService->sendPasswordResetMail($user, $this->getPasswordResetUrl($user));
            return new ActionResult(true, 'An email has been sent to your email, please check it to create a new password.');
        } else {
            return new ActionResult(false, 'The email doesn\'t exist, please verify that you have inserted the correct email address.');
        }
    }


    /**
     * @param IEmailValidationToken $emailValidationTokenGenerator
     * @return IEmailValidationToken
     */
    private function getEmailValidationTokenGenerator(IEmailValidationToken $emailValidationTokenGenerator = null)
    {
        if (!$emailValidationTokenGenerator) {
            $emailValidationTokenGenerator = new Md5EmailValidationToken();
        }
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