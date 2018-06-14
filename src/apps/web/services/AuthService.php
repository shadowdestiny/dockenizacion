<?php
namespace EuroMillions\web\services;

use DateTime;
use Doctrine\ORM\EntityManager;
use EuroMillions\shared\interfaces\IUrlManager;
use EuroMillions\web\components\Md5EmailValidationToken;
use EuroMillions\web\components\PDFGenerator;
use EuroMillions\web\components\UserId;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\ResetPasswordEmailTemplate;
use EuroMillions\web\entities\GuestUser;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\IAuthStorageStrategy;
use EuroMillions\web\interfaces\IEmailValidationToken;
use EuroMillions\web\interfaces\IPasswordHasher;
use EuroMillions\web\interfaces\IPDFWrapper;
use EuroMillions\web\interfaces\IUser;
use EuroMillions\web\pdf\PdfTemplate;
use EuroMillions\web\repositories\UserRepository;
use EuroMillions\web\services\email_templates_strategies\NullEmailTemplateDataStrategy;
use EuroMillions\web\vo\dto\NotificationDTO;
use EuroMillions\web\vo\dto\PastDrawsCollectionDTO;
use EuroMillions\web\vo\dto\UpcomingDrawsDTO;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\IPAddress;
use EuroMillions\web\vo\Password;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\Url;
use EuroMillions\web\vo\ValidationToken;
use EuroMillions\web\pdf\GDPRPdfTemplate;

class AuthService
{

    /** @var EntityManager */
    private $entityManager;
    /** @var UserRepository */
    protected $userRepository;
    private $passwordHasher;
    /** @var IAuthStorageStrategy */
    private $storageStrategy;
    /** @var IUrlManager */
    private $urlManager;
    /** @var LogService */
    protected $logService;
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
        $user = null;
        $user_id = $this->storageStrategy->getCurrentUserId();
        if (UserId::isValid($user_id)) {
            $user = $this->userRepository->find($user_id);
        } else {
            $user_id = UserId::create();
        }
        if (!$user) {
            $user = new GuestUser();
            $user->setId($user_id);
        }
        return $user;
    }

    /**
     * @return User
     */
    public function getLoggedUser()
    {
        if ($this->isLogged()) {
            return $this->getCurrentUser();
        } else {
            throw new \EuroMillions\shared\exceptions\AccessNotAllowedForGuests('Restricted area');
        }
    }

    public function logout()
    {
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
            $this->log('User not found ['.$credentials['email'].']', 'check');
            return ['bool' => false, 'error' => 'userNotFound'];
        }
        $password_match = $this->passwordHasher->checkPassword($credentials['password'], $user->getPassword()->toNative());
        if ($password_match) {
            if ($user->getDisabledDate() !== null) {
                return ['bool' => false, 'error' => 'disabledUser'];
            }else {
                $this->storageStrategy->setCurrentUserId($user->getId());
                if ($credentials['remember']) {
                    $user->setRememberToken($agentIdentificationString);
                    $this->storageStrategy->storeRemember($user);
                }
                $user->setIpAddress(new IPAddress($credentials['ipaddress']));
                $this->entityManager->flush();
            }
        }
        return ['bool' => $password_match, 'error' => ''];
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
            $this->log('Email already registered. Try to use a different email. ['.$credentials['email'].']', 'register');
            return new ActionResult(false, 'Email already registered. Try to use a different email. Or have you <a href="/user-access/forgotPassword">forgot your password?</a>');
        }
        try {
            $user = $this->userRepository->register($credentials, $this->passwordHasher, new Md5EmailValidationToken());
        } catch (\Exception $e) {
            $this->log($e->getMessage(),'register');
            return new ActionResult(false, $e);
        }
        $this->emailService->sendWelcomeEmail($user, $this->urlManager);
        $this->userService->initUserNotifications($user->getId());
        return new ActionResult(true, $user);
    }

    public function registerFromCheckout(array $credentials, $userId)
    {
        $current_user = $this->getCurrentUser();
        if (!is_a($current_user, 'EuroMillions\web\entities\GuestUser', false)) {
            return new ActionResult(false, 'Error getting user');
        }
        if ($this->userRepository->getByEmail($credentials['email'])) {
            return new ActionResult(false, 'Email already registered. Try to use a different email. Or have you <a href="/user-access/forgotPassword">forgot your password?</a>');
        }
        try {
            $user = $this->userRepository->registerFromCheckout($credentials, $userId, $this->passwordHasher, new Md5EmailValidationToken());
            if (null !== $user->getId()) {
                $this->storageStrategy->setCurrentUserId($userId);
            } else {
                return new ActionResult(false, 'Error getting an user');
            }
        } catch (\Exception $e) {
            return new ActionResult(false, $e->getMessage());
        }
        $this->emailService->sendWelcomeEmail($user, $this->urlManager);
        $this->userService->initUserNotifications($user->getId());
        return new ActionResult(true, $user);
    }

    private function getEmailValidationToken(Email $email, IEmailValidationToken $emailValidationTokenGenerator = null)
    {
        $emailValidationTokenGenerator = $this->getEmailValidationTokenGenerator($emailValidationTokenGenerator);
        return new ValidationToken($email, $emailValidationTokenGenerator);
    }

    public function disableUser($userId)
    {
        try {
            $user = $this->userService->getUser($userId);
            $user->setDisabledDate(new \DateTime());
            $this->userRepository->add($user);
            $this->entityManager->flush();
            $this->logout();
        } catch (\Exception $e) {
            $this->log( 'An exception occurred disabling user' . ' ' . $e->getMessage(), 'disableUser');
        }

    }

    public function resendToken()
    {
        /** @var User $currentUser */
        $currentUser = $this->getCurrentUser();

        if($currentUser instanceof User) {
            try {
                $this->emailService->sendWelcomeEmail($currentUser, $this->urlManager);
            } catch (\Exception $e) {
                throw new \Exception('Error resend welcome email');
            }

        }

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
    private function getPasswordResetUrl(User $user)
    {
        return new Url($this->urlManager->get('/passwordReset/' . $this->getEmailValidationToken(new Email($user->getEmail()->toNative()))));
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
            } catch (\Exception $e) {
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

    public function updatePassword(User $user, $newPassword)
    {
        try {
            $password = new Password($newPassword, $this->passwordHasher);
            $user->setPassword($password);
            $this->userRepository->add($user);
            $this->entityManager->flush($user);
            $this->emailService->sendTransactionalEmail($user, new ResetPasswordEmailTemplate(new EmailTemplate(), new NullEmailTemplateDataStrategy()));
            return new ActionResult(true, 'Your password was changed correctly');
        } catch (\Exception $e) {
            return new ActionResult(false, 'It was a problem updating your password. Please try again later.');
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

    public function confirmUser($token)
    {
        $user = $this->userRepository->getByToken($token);
        if($user) {
            $this->storageStrategy->setCurrentUserId($user->getId());
            return new ActionResult(true);
        }
        return new ActionResult(false);
    }


    public function getPDFFromUser(IPDFWrapper $pdfWrapper, array $notificationsDTO, $transactions, $nextDrawDate )
    {

        $userData = [];
        try {

            /** @var User $currentUser */
            $currentUser = $this->getCurrentUser();
            $userData['user'] = $currentUser;
            $userData['notifications'] = $notificationsDTO;
            $myGames = $this->userService->getMyActivePlays($currentUser->getId())->getValues();
            $upComingDraws = $myGames != null ? new UpcomingDrawsDTO($myGames) : [];
            $userData['upComingDraws'] = $upComingDraws;
            $myGamesInactives = $this->userService->getMyInactivePlays($currentUser->getId())->getValues();
            $userData['lastTickets'] = $myGamesInactives;
            $mySubscriptionsActives = $this->userService->getMyActiveSubscriptions($currentUser->getId(), $nextDrawDate);
            $userData['transactions'] = $transactions;
            $userData['activeSubscriptions'] = $mySubscriptionsActives;

            $pdfTemplate = new PdfTemplate();
            $gdprPdfTemplate = new GDPRPdfTemplate($pdfTemplate);
            $gdprPdfTemplate->setData(PDFGenerator::build($userData));
            $pdfWrapper->build($gdprPdfTemplate->loadHeader(),$gdprPdfTemplate->loadBody(),$gdprPdfTemplate->loadFooter());
            $pdfWrapper->getPDF();

        } catch (\Exception $e) {

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


    protected function log($message, $action) {
        if(method_exists($this,'logError')) {
            $this->logError($message, $action);
        }
    }
}