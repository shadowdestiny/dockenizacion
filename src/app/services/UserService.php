<?php
namespace EuroMillions\services;
use EuroMillions\entities\User;
use EuroMillions\interfaces\IStorageStrategy;
use EuroMillions\repositories\UserRepository;
use EuroMillions\vo\UserId;
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
     * @var IStorageStrategy
     */
    private $storageStrategy;

    public function __construct(UserRepository $userRepository, CurrencyService $currencyService, IStorageStrategy $strategy)
    {
        $this->userRepository = $userRepository;
        $this->currencyService = $currencyService;
        $this->storageStrategy = $strategy;
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

    public function getCurrentUser()
    {
        return $this->storageStrategy->getCurrentUser();
    }

    public function isLogged()
    {
        $user = $this->getCurrentUser();
        return get_class($user) == 'EuroMillions\entities\User';
    }
}