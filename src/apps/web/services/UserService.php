<?php
namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\LongPlayEndedEmailTemplate;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\Notification;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\repositories\NotificationRepository;
use EuroMillions\web\repositories\PlayConfigRepository;
use EuroMillions\web\repositories\UserNotificationsRepository;
use EuroMillions\web\repositories\UserRepository;
use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;
use EuroMillions\web\vo\ContactFormInfo;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\NotificationValue;
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
     * @var CurrencyConversionService
     */
    private $currencyConversionService;
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

    /** @var  WalletService */
    private $walletService;

    public function __construct(CurrencyConversionService $currencyConversionService,
                                EmailService $emailService,
                                PaymentProviderService $paymentProviderService,
                                WalletService $walletService,
                                EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository('EuroMillions\web\entities\User');
        $this->currencyConversionService = $currencyConversionService;
        $this->emailService = $emailService;
        $this->paymentProviderService = $paymentProviderService;
        $this->playRepository = $entityManager->getRepository('EuroMillions\web\entities\PlayConfig');
        $this->userNotificationsRepository = $entityManager->getRepository('EuroMillions\web\entities\UserNotifications');
        $this->notificationRepository = $entityManager->getRepository('EuroMillions\web\entities\Notification');
        $this->walletService = $walletService;
    }

    public function getBalanceWithUserCurrencyConvert($userId, Currency $userCurrency)
    {
        /** @var User $user */
        $user = $this->userRepository->find($userId);
        $money_convert = $this->currencyConversionService->convert($user->getBalance(), $userCurrency);
        return $this->currencyConversionService->toString($money_convert, $user->getLocale());
    }

    /**
     * @param string $userId
     * @return User
     */
    public function getUser($userId)
    {
        //EMTD Si no hay user, deberíamos lanzar LogicException. Siempre debería haber user cuando llamamos a esta función.
        return $this->userRepository->find($userId);
    }

    public function getUserByToken($token)
    {
        $user = $this->userRepository->getByToken($token);
        if(null != $user) {
            return new ActionResult(true, $user);
        } else {
            return new ActionResult(false);
        }
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



    public function getMyActivePlays($userId)
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

    public function getMyInactivePlays($userId)
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

   public function updateEmailNotification(NotificationValue $notificationType, User $user, $active)
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
        } else {
            return new ActionResult(false);
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
        }else {
            return new ActionResult(false,$user_notifications);
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

    public function initUserNotifications($userId)
    {
        /** @var User $user */
        $user = $this->userRepository->find($userId);
        if(!empty($user)) {
            try{
                /** @var Notification[] $notifications */
                $notifications = $this->notificationRepository->findAll();
                foreach($notifications as $notification) {
                    /** @var UserNotifications $user_notifications */
                    $user_notifications = new UserNotifications();
                    $user_notifications->setUser($user);
                    ($notification->getNotificationType() == NotificationValue::NOTIFICATION_THRESHOLD) ? $user_notifications->setActive(false) : $user_notifications->setActive(true);
                    if($notification->getNotificationType() == NotificationValue::NOTIFICATION_RESULT_DRAW) {
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
        return new ActionResult(false);
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

    public function chargeFeeFromWallet(Money $amount, Money $feeLimit, Money $fee )
    {
        if( $amount->getAmount() < $feeLimit->getAmount() ) {
            return new ActionResult(true,$amount->add($fee));
        } else {
            return new ActionResult(false,$amount);
        }
    }

    public function userWonAbove( User $user, Money $money )
    {
        if(null == $user) {
            return new ActionResult(false,new Money(0,$user->getUserCurrency()));
        }
        if( $money->getAmount() < 250000 ) {
            return new ActionResult(false,new Money(0,$user->getUserCurrency()));
        }

        try {
            $user->setWinningAbove($money);
            $this->userRepository->add($user);
            $this->entityManager->flush($user);
            return new ActionResult(true, $money);
        } catch( \Exception $e ) {
            return new ActionResult(false,new Money(0,$user->getUserCurrency()));
        }
    }

    public function resetWonAbove( User $user ) {

        if(null == $user) {
            return new ActionResult(false,new Money(0,$user->getUserCurrency()));
        }
        try {
            $user->setWinningAbove(new Money(0, $user->getUserCurrency()));
            $this->userRepository->add($user);
            $this->entityManager->flush($user);
        } catch ( \Exception $e ) {
            return new ActionResult(false);
        }
        return new ActionResult(true);
    }

    public function checkLongTermAndSendNotification( array $playConfigList, \DateTime $today)
    {
        /** @var PlayConfig[] $playConfigList */
        foreach($playConfigList as $play_config) {
            $day_last_draw = $play_config->getLastDrawDate()->getTimestamp();
            if($today->getTimestamp() > $day_last_draw ) {
                /** @var User $user */
                $user = $this->userRepository->find($play_config->getUser()->getId());
                $emailTemplate = new EmailTemplate();
                $emailTemplate = new LongPlayEndedEmailTemplate($emailTemplate, new JackpotDataEmailTemplateStrategy());
                $this->emailService->sendTransactionalEmail($user,$emailTemplate);
            }
        }
    }

    public function checkEnoughAmountForNextDraw( $userId, Lottery $lottery, \DateTime $date)
    {
        /** @var User $user */
        $user = $this->userRepository->find($userId);
        if( $user ) {
            $totalPlayForNextDraw = $this->playRepository->getTotalByUserAndPlayForNextDraw($userId, $date);
            if( $totalPlayForNextDraw > 0 ) {
                $total = $user->getBalance()->getAmount() >= $totalPlayForNextDraw * $lottery->getSingleBetPrice()->getAmount();
                return $total ? new ActionResult(true) : new ActionResult(false);
            }
            return new ActionResult(false);
        }
        return new ActionResult(false);
    }


    public function getUsersWithPlayConfigsForNextDraw()
    {
        return $this->userRepository->getUsersWithPlayConfigsOrderByLottery();
    }

    /**
     * @param Lottery $lottery
     * @param array $playconfigs
     * @return Money
     */
    public function getPriceForNextDraw(Lottery $lottery, array $playconfigs)
    {
        $price = new Money(0,new Currency('EUR'));
        /** @var PlayConfig $playconfig */
        foreach($playconfigs as $playconfig) {
            $amount = new Money((int) ($playconfig->numBets() * $lottery->getSingleBetPrice()->getAmount()),new Currency('EUR'));
            $price = $price->add($amount);
        }
        return $price;
    }

    public function createWithDraw( User $user, array $data )
    {
        try {
            $user->setBankAccount($data['bank-account']);
            $user->setBankName($data['bank-name']);
            $user->setBankSwift($data['bank-swift']);
            $this->entityManager->persist($user);
            $this->entityManager->flush($user);
            $amount = new Money((int) $data['amount'] * 100, new Currency('EUR'));
            $this->walletService->withDraw( $user, $amount);
        } catch ( \Exception $e ) {
            return new ActionResult(false);
        }
        return new ActionResult(true);
    }
}
