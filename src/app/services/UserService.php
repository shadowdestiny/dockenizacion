<?php
namespace EuroMillions\services;
use Alcohol\ISO4217;
use antonienko\MoneyFormatter\MoneyFormatter;
use Doctrine\ORM\EntityManager;
use EuroMillions\entities\PaymentMethod;
use EuroMillions\entities\User;
use EuroMillions\interfaces\IUsersPreferencesStorageStrategy;
use EuroMillions\repositories\PaymentMethodRepository;
use EuroMillions\repositories\PlayConfigRepository;
use EuroMillions\repositories\UserRepository;
use EuroMillions\vo\ContactFormInfo;
use EuroMillions\vo\Email;
use EuroMillions\vo\ServiceActionResult;
use EuroMillions\vo\UserId;
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
     * @var IUsersPreferencesStorageStrategy
     */
    private $storageStrategy;
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


    public function __construct(CurrencyService $currencyService,
                                IUsersPreferencesStorageStrategy $strategy,
                                EmailService $emailService,
                                PaymentProviderService $paymentProviderService,
                                EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository('EuroMillions\entities\User');
        $this->paymentMethodRepository = $entityManager->getRepository('EuroMillions\entities\PaymentMethod');
        $this->currencyService = $currencyService;
        $this->storageStrategy = $strategy;
        $this->emailService = $emailService;
        $this->paymentProviderService = $paymentProviderService;
        $this->playRepository = $entityManager->getRepository('EuroMillions\entities\PlayConfig');
    }

    public function getBalance(UserId $userId)
    {
        /** @var User $user */
        $user = $this->userRepository->find($userId->id());
        return $this->currencyService->toString($user->getBalance());
    }

    public function setCurrency(Currency $currency)
    {
        $this->storageStrategy->setCurrency($currency);
    }

    public function getUser(UserId $userId)
    {
        return $this->userRepository->find($userId->id());
    }

    public function getCurrency()
    {
        $currency = $this->storageStrategy->getCurrency();
        if (!$currency) {
            $currency = new Currency('EUR');
        }
        return $currency;
    }

    /**
     * @param Money $jackpot
     * @return Money
     */
    public function getJackpotInMyCurrency(Money $jackpot)
    {
        return $this->currencyService->convert($jackpot, $this->storageStrategy->getCurrency());
    }

    public function getBalanceFromCurrentUser()
    {
        //EMTD after user is registered and logged in
    }

    public function getMyCurrencyNameAndSymbol()
    {
        $currency = $this->getCurrency();
        $iso4217 = new ISO4217();
        $currency_data = $iso4217->getByAlpha3($currency->getName());
        $mf = new MoneyFormatter();
        $symbol = $mf->getSymbolFromCurrency('en_US', $currency);
        if (!$symbol)
        {
            $symbol = $currency->getName();
        }
        return ['symbol' => $symbol, 'name' => $currency_data['name']];
    }

    /**
     * @param ContactFormInfo $contactFormInfo
     * @return ServiceActionResult
     */
    public function contactRequest(ContactFormInfo $contactFormInfo)
    {
        try{
            $this->emailService->sendContactRequest($contactFormInfo);
            return new ServiceActionResult(true,'We have received your request!');
        }catch(Exception $e){
            return new ServiceActionResult(false, 'Sorry, we have problems receiving data');
        }
    }

    /**
     * @param User $user
     * @param PaymentMethod $paymentMethod
     * @param Money $amount
     * @return ServiceActionResult
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
                    return new ServiceActionResult(true, $user->getBalance()->getAmount());
                } catch(Exception $e){
                    $error_message = 'Error updating balance';
                }
            } else {
                $error_message = 'Provider denied the operation';
            }
        } else {
            $error_message = 'Amount should be greater than 0';
        }
        return new ServiceActionResult(false, $error_message);
    }

    /**
     * @param PaymentMethod $paymentMethod
     * @return ServiceActionResult
     */
    public function addNewPaymentMethod(PaymentMethod $paymentMethod)
    {
        try{
            $this->paymentMethodRepository->add($paymentMethod);
            $this->entityManager->flush($paymentMethod);
            return new ServiceActionResult(true, 'Your payment method was added');
        }catch(Exception $e){
            return new ServiceActionResult(false,'An exception ocurred while payment method was saved');
        }
    }

    /**
     * @param UserId $userId
     * @return ServiceActionResult
     */
    public function getPaymentMethods(UserId $userId)
    {
        $user = $this->userRepository->find($userId->id());
        if(!empty($user)){
            $paymentMethodCollection = $this->paymentMethodRepository->getPaymentMethodsByUser($user);
            if(!empty($paymentMethodCollection)){
                return new ServiceActionResult(true,$paymentMethodCollection);
            }else{
                return new ServiceActionResult(false,'You don\'t have any payment method registered');
            }
        }
    }

    public function getMyPlaysActives(UserId $userId)
    {
        if(!empty($userId)){
            /** @var array $result */
            $result = $this->playRepository->getPlayConfigsActivesByUser($userId);
            if(empty($result)){
                return new ServiceActionResult(false,'You don\'t have games');
            }
            return new ServiceActionResult(true,$result);
        }else{
            return new ServiceActionResult(false);
        }
    }

    public function getMyPlaysInActives(UserId $userId)
    {
        if(!empty($userId)){
            /** @var array $result */
            $result = $this->playRepository->getPlayConfigsInActivesByUser($userId);
            if(empty($result)){
                return new ServiceActionResult(false,'You don\'t have games');
            }
            return new ServiceActionResult(true,$result);
        }else{
            return new ServiceActionResult(false);
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
            return new ServiceActionResult(true,'Your data was update');
        }catch(Exception $e){
            return new ServiceActionResult(false,'Sorry, try it later');
        }
    }

}