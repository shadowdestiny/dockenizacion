<?php
namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\CreditCardPaymentMethod;
use EuroMillions\web\entities\PaymentMethod;
use EuroMillions\web\entities\User;
use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\repositories\PaymentMethodRepository;
use EuroMillions\web\repositories\PlayConfigRepository;
use EuroMillions\web\repositories\UserNotificationsRepository;
use EuroMillions\web\repositories\UserRepository;
use EuroMillions\web\vo\CardHolderName;
use EuroMillions\web\vo\CardNumber;
use EuroMillions\web\vo\ContactFormInfo;
use EuroMillions\web\vo\CVV;
use EuroMillions\web\vo\ExpiryDate;
use EuroMillions\web\vo\ActionResult;
use EuroMillions\web\vo\NotificationType;
use EuroMillions\web\vo\UserId;
use Exception;
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

    /** @var PaymentMethodRepository */
    private $paymentMethodRepository;

    /** @var PlayConfigRepository  */
    private $playRepository;

    /** @var UserNotificationsRepository  */
    private $userNotificationsRepository;

    public function __construct(CurrencyService $currencyService,
                                EmailService $emailService,
                                PaymentProviderService $paymentProviderService,
                                EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository('EuroMillions\web\entities\User');
        $this->paymentMethodRepository = $entityManager->getRepository('EuroMillions\web\entities\PaymentMethod');
        $this->currencyService = $currencyService;
        $this->emailService = $emailService;
        $this->paymentProviderService = $paymentProviderService;
        $this->playRepository = $entityManager->getRepository('EuroMillions\web\entities\PlayConfig');
        $this->userNotificationsRepository = $entityManager->getRepository('EuroMillions\web\entities\UserNotifications');

    }

    public function getBalance(UserId $userId, $locale)
    {
        /** @var User $user */
        $user = $this->userRepository->find($userId->id());
        return $this->currencyService->toString($user->getBalance(), $locale);
    }

    public function getUser(UserId $userId)
    {
        return $this->userRepository->find($userId->id());
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
     * @param PaymentMethod $paymentMethod
     * @param Money $amount
     * @return ActionResult
     */
    public function recharge(User $user, PaymentMethod $paymentMethod,Money $amount)
    {
        if($amount->getAmount() > 0){
            $result = $this->paymentProviderService->charge($paymentMethod,$amount);
            if ($result) {
                try{
                    $user->setBalance($user->getBalance()->add($amount));
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

    /**
     * @param PaymentMethod $paymentMethod
     * @return ActionResult
     */
    public function addNewPaymentMethod(PaymentMethod $paymentMethod)
    {
        try{
            $this->paymentMethodRepository->add($paymentMethod);
            $this->entityManager->flush($paymentMethod);
            return new ActionResult(true, 'Your payment method was added');
        }catch(Exception $e){
            return new ActionResult(false,'An exception ocurred while payment method was saved');
        }
    }

    /**
     * @param UserId $userId
     * @return ActionResult
     */
    public function getPaymentMethods(UserId $userId)
    {
        /** @var User $user */
        $user = $this->userRepository->find($userId->id());
        if(!empty($user)){
            $paymentMethodCollection = $this->paymentMethodRepository->getPaymentMethodsByUser($user);
            if(!empty($paymentMethodCollection)){
                return new ActionResult(true,$paymentMethodCollection);
            }else{
                return new ActionResult(false,'You don\'t have any payment method registered');
            }
        }else{
            throw new \InvalidArgumentException('User doesn\'t exist');
        }
    }


    public function editMyPaymentMethod($id,array $data)
    {
        try{
            /** @var CreditCardPaymentMethod $payment_method */
            $payment_method = $this->paymentMethodRepository->findOneBy(['id' => $id]);
            $payment_method->setCardHolderName(new CardHolderName($data['cardHolderName']));
            $payment_method->setCardNumber(new CardNumber($data['cardNumber']));
            $exp_date = new ExpiryDate($data['month'].'/'.$data['year']);
            $payment_method->setExpiryDate(ExpiryDate::assertExpiryDate($exp_date));
            $payment_method->setCVV(new CVV($data['cvv']));
            $payment_method->setCompany($payment_method->getCardNumber()->type());
            $this->paymentMethodRepository->add($payment_method);
            $this->entityManager->flush($payment_method);
            return new ActionResult(true,'Your credit card data was updating');
        }catch(\Exception $e) {
            return new ActionResult(false, $e->getMessage());
        }
    }

    public function getPaymentMethod($id)
    {
        try{
            return $this->paymentMethodRepository->findOneBy(['id' => $id]);
        }catch(\Exception $e) {
            return new ActionResult(false);
        }

    }

    public function getMyPlaysActives(UserId $userId)
    {
        if(!empty($userId)){
            /** @var array $result */
            $result = $this->playRepository->getPlayConfigsActivesByUser($userId);
            if(empty($result)){
                return new ActionResult(false,'You don\'t have games');
            }
            return new ActionResult(true,$result);
        }else{
            return new ActionResult(false);
        }
    }

    public function getMyPlaysInActives(UserId $userId)
    {
        if(!empty($userId)){
            /** @var array $result */
            $result = $this->playRepository->getPlayConfigsInActivesByUser($userId);
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

   public function updateEmailNotification(NotificationType $notificationType, $id_user_notification,$active)
   {
       /** @var UserNotifications $user_notification */
        $user_notification = $this->userNotificationsRepository->findOneBy(['id' => $id_user_notification]);

        if(!empty($user_notification)) {
           $user_notification->setConfigValue($notificationType);
           $user_notification->setActive($active);
           $this->userNotificationsRepository->add($user_notification);
           $this->entityManager->flush($user_notification);
           return new ActionResult(true);
        }else {
            return new ActionResult(false);
        }
   }

    public function getActiveNotificationsTypeJackpot()
    {
        $user_notifications = $this->userNotificationsRepository->findBy(['active' => true,
                                                                          'notification' => 1
                                                                         ]);
        if(!empty($user_notifications)) {
            return new ActionResult(true,$user_notifications);
        }
    }

    public function initUserNotifications()
    {

    }

}