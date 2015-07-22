<?php
namespace EuroMillions\services;
use EuroMillions\entities\User;
use EuroMillions\interfaces\ICurrencyStrategy;
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
     * @var ICurrencyStrategy
     */
    private $currencyStrategy;

    public function __construct(UserRepository $userRepository, CurrencyService $currencyService, ICurrencyStrategy $strategy)
    {
        $this->userRepository = $userRepository;
        $this->currencyService = $currencyService;
        $this->currencyStrategy = $strategy;
    }

    public function getBalance(UserId $userId)
    {
        /** @var User $user */
        $user = $this->userRepository->find($userId->id());
        return $this->currencyService->toString($user->getBalance());
    }

    public function setCurrency(Currency $currency)
    {
        $this->currencyStrategy->set($currency);
    }

    /**
     * @param Money $jackpot
     * @return Money
     */
    public function getJackpotInMyCurrency(Money $jackpot)
    {
        return $this->currencyService->convert($jackpot, $this->currencyStrategy->get());
    }

    public function getBalanceFromCurrentUser()
    {
        //EMTD after user is registered and logged in
    }

}