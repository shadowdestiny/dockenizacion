<?php
namespace EuroMillions\services;
use EuroMillions\entities\User;
use EuroMillions\repositories\UserRepository;
use EuroMillions\vo\UserId;

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
}