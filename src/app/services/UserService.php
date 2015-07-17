<?php
namespace EuroMillions\services;
use EuroMillions\entities\User;
use EuroMillions\interfaces\ICurrencyStrategy;
use EuroMillions\repositories\UserRepository;
use EuroMillions\vo\UserId;
use Money\Currency;

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

    public function __construct(UserRepository $userRepository, CurrencyService $currencyService)
    {
        $this->userRepository = $userRepository;
        $this->currencyService = $currencyService;
    }

    public function getBalance(UserId $userId)
    {
        /** @var User $user */
        $user = $this->userRepository->find($userId->id());
        return $this->currencyService->toString($user->getBalance());
    }

    public function setCurrency(ICurrencyStrategy $strategy, Currency $currency)
    {
        $strategy->set($currency);
    }

    public function getBalanceFromCurrentUser(ICurrencyStrategy $strategy)
    {
        //EMTD after user is registered and logged in
    }

}