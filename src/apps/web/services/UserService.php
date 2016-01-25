<?php
namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\Notification;
use EuroMillions\web\entities\User;
use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\repositories\NotificationRepository;
use EuroMillions\web\repositories\PlayConfigRepository;
use EuroMillions\web\repositories\UserNotificationsRepository;
use EuroMillions\web\repositories\UserRepository;
use EuroMillions\web\vo\ContactFormInfo;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\NotificationType;
use EuroMillions\web\vo\UserId;
use Exception;
use Money\Currency;
use Money\Money;

class UserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var CurrencyService
     */
    private $currencyService;
    /**
     * @var EmailService
     */
    private $emailService;

    /**
     * @var PaymentProviderService
     */
    private $paymentProviderService;

    /** @var EntityManager */
    private $entityManager;

    /** @var PlayConfigRepository  */
    private $playRepository;

    /** @var UserNotificationsRepository  */
    private $userNotificationsRepository;

    /** @var NotificationRepository */
    private $notificationRepository;

    public function __construct(CurrencyService $currencyService,
                                EmailService $emailService,
                                PaymentProviderService $paymentProviderService,
                                EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository('EuroMillions\web\entities\User');
        $this->currencyService = $currencyService;
        $this->emailService = $emailService;
        $this->paymentProviderService = $paymentProviderService;
        $this->playRepository = $entityManager->getRepository('EuroMillions\web\entities\PlayConfig');
        $this->userNotificationsRepository = $entityManager->getRepository('EuroMillions\web\entities\UserNotifications');
        $this->notificationRepository = $entityManager->getRepository('EuroMillions\web\entities\Notification');
    }

    public function getBalance(UserId $userId, $locale)
    {
        /** @var User $user */
        $user = $this->userRepository->find($userId->id());
        return $this->currencyService->toString($user->getBalance(), $locale);
    }

    public function getBalanceWithUserCurrencyConvert(UserId $userId, $locale)
    {
        /** @var User $user */
        $user = $this->userRepository->find($userId->id());
        $money_convert = $this->currencyService->convert($user->getBalance(), $locale);
        return $this->currencyService->toString($money_convert, $locale);
    }

    /**
     * @param UserId $userId
     * @return User
     */
    public function getUser(UserId $userId)
    {
        return $this->userRepository->find($userId->id());
    }

    public function updateUser(User $user)
    {
        try{
            $this->userRepository->add($user);
            $this->entityManager->flush();
            return new ActionResult(true);
        } catch(\Exception $e) {
            return new ActionResult(false);
        }
    }

    public function getBalanceFromCurrentUser()
    {
        //EMTD after user is registered and logged in
    }

    /**
     * @param ContactFormInfo $contactFormInfo
     * @return ActionResult
     */
    public function contactRequest(ContactFormInfo $contactFormInfo)
    {
        try{
            $this->emailService->sendContactRequest($contactFormInfo);
            return new ActionResult(true,'We have received your request!');
        }catch(Exception $e){
            return new ActionResult(false, 'Sorry, we have problems receiving data');
        }
    }

    /**
     * @param User $user
     * @param ICardPaymentProvider $paymentProvider
     * @param Money $amount
     * @return ActionResult
     */
    public function recharge(User $user, ICardPaymentProvider $paymentProvider, Money $amount)
    {
        if($amount->getAmount() > 0){
            $result = $this->paymentProviderService->charge($paymentProvider,$amount);
            if ($result) {
                try{
                    $user->reChargeWallet($amount);
                    $this->userRepository->add($user);
                    $this->entityManager->flush($user);
                    return new ActionResult(true, $user->getBalance()->getAmount());
                } catch(Exception $e){
                    $error_message = 'Error updating balance';
                }
            } else {
                $error_message = 'Provider denied the operation';
            }
        } else {
            $error_message = 'Amount should be greater than 0';
        }
        return new ActionResult(false, $error_message);
    }

    public function getMyActivePlays(UserId $userId)
    {
        if(!empty($userId)){
            /** @var array $result */
            $result = $this->playRepository->getActivePlayConfigsByUser($userId);
            if(empty($result)){
                return new ActionResult(false,'You don\'t have games');
            }
            return new ActionResult(true,$result);
        }else{
            return new ActionResult(false);
        }
    }

    public function getMyInactivePlays(UserId $userId)
    {
        if(!empty($userId)){
            /** @var array $result */
            $result = $this->playRepository->getInactivePlayConfigsByUser($userId);
            if(empty($result)){
                return new ActionResult(false,'You don\'t have games');
            }
            return new ActionResult(true,$result);
        }else{
            return new ActionResult(false);
        }
    }

    public function updateUserData(array $user_data)
    {
        $user = $this->userRepository->getByEmail($user_data['email']);

        $user->setName($user_data['name']);
        $user->setSurname($user_data['surname']);
        $user->setEmail($user_data['email']);
        $user->setCountry($user_data['country']);
        $user->setStreet($user_data['street']);
        $user->setZip($user_data['zip']);
        $user->setCity($user_data['city']);
        $user->setPhoneNumber($user_data['phone_number']);

        try{
            $this->userRepository->add($user);
            $this->entityManager->flush($user);
            return new ActionResult(true,'Your data was update');
        }catch(Exception $e){
            return new ActionResult(false,'Sorry, try it later');
        }
    }

    public function getAllUsersWithJackpotReminder()
    {
        $result = $this->userRepository->getUsersWithJackpotReminder();
        if(!empty($result)) {
            return new ActionResult(true,$result);
        } else {
            return new ActionResult(false);
        }
    }

    public function getActiveNotificationsByUser(User $user)
    {
        /** @var UserNotifications $collection */
        $collection = $this->userNotificationsRepository->findBy(['user' => $user]);
        if(!empty($collection)) {
            return new ActionResult(true,$collection);
        }else{
            return new ActionResult(false);
        }
    }

   public function updateEmailNotification(NotificationType $notificationType, User $user,$active)
   {

       /** @var Notification $notification */
       $notification = $this->notificationRepository->findBy(['notification_type' => $notificationType->getType()]);


       /** @var UserNotifications $user_notification */
       $user_notification = $this->userNotificationsRepository->findOneBy(['user' => $user,
                                                                           'notification' => $notification
                                                                          ]
       );
       if(!empty($user_notification)) {
           try {
               $user_notification->setConfigValue($notificationType);
               $user_notification->setActive($active);
               $this->userNotificationsRepository->add($user_notification);
               $this->entityManager->flush($user_notification);
               return new ActionResult(true);
            }catch(Exception $e) {
                throw new \Exception();
            }
        }else{
           throw new \Exception();
       }
   }

    public function getActiveNotificationsTypeJackpot()
    {
        $user_notifications = $this->userNotificationsRepository->findBy(['active' => true,
                                                                          'notification' => 1,
                                                                         ]);
        if(!empty($user_notifications)) {
            return new ActionResult(true,$user_notifications);
        }
    }

    public function getActiveNotificationsByUserAndType(User $user, $notificationType)
    {
        $user_notifications = $this->userNotificationsRepository->findBy(['active' => true,
                                                                          'notification' => $notificationType,
                                                                          'user' => $user
        ]);
        if(!empty($user_notifications)) {
            return new ActionResult(true,$user_notifications);
        }
    }

    public function getActiveNotificationsByType($notificationType)
    {
        $user_notifications = $this->userNotificationsRepository->findBy(['active' => true,
                                                                          'notification' => $notificationType,
        ]);
        if(!empty($user_notifications)) {
            return new ActionResult(true,$user_notifications);
        }else {
            return new ActionResult(false);
        }
    }

    public function initUserNotifications(UserId $userId)
    {
        /** @var User $user */
        $user = $this->userRepository->find($userId->id());
        if(!empty($user)) {
            try{
                /** @var Notification[] $notifications */
                $notifications = $this->notificationRepository->findAll();
                foreach($notifications as $notification) {
                    /** @var UserNotifications $user_notifications */
                    $user_notifications = new UserNotifications();
                    $user_notifications->setUser($user);
                    ($notification->getNotificationType() == NotificationType::NOTIFICATION_THRESHOLD) ? $user_notifications->setActive(false) : $user_notifications->setActive(true);
                    if($notification->getNotificationType() == NotificationType::NOTIFICATION_RESULT_DRAW) {
                        $user_notifications->setConfigValue(false);
                    }
                    $user_notifications->setNotification($notification);
                    $this->userNotificationsRepository->add($user_notifications);
                    $this->entityManager->flush($user_notifications);
                }
                return new ActionResult(true);
            } catch(\Exception $ex) {
                return new ActionResult(false);
            }
        }
    }

    public function updateCurrency(User $user, Currency $new_currency)
    {
        if(!empty($user)) {
            try{
                $user->setUserCurrency($new_currency);
                $this->userRepository->add($user);
                $this->entityManager->flush($user);
                return new ActionResult(true,$new_currency);
            } catch( \Exception $e) {
                return new ActionResult(false);
            }
        } else {
            return new ActionResult(false);
        }
    }

}