<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 6/07/18
 * Time: 13:01
 */

namespace EuroMillions\powerball\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\web\repositories\BetRepository;
use EuroMillions\web\repositories\PlayConfigRepository;
use EuroMillions\web\repositories\UserRepository;
use EuroMillions\web\services\CurrencyConversionService;
use EuroMillions\web\services\EmailService;
use EuroMillions\web\services\TransactionService;
use EuroMillions\web\services\UserService;

class PrizesServices
{

    private $entityManager;

    /**
     * @var PlayConfigRepository
     */
    private $playConfigRepository;

    /** @var  BetRepository */
    private $betRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var  UserService */
    private $userService;

    /** @var CurrencyConversionService */
    private $currencyConversionService;

    /** @var  EmailService */
    private $emailService;

    /** @var  TransactionService */
    private $transactionService;

    private $di;


    public function __construct(EntityManager $entityManager,
                                CurrencyConversionService $currencyConversionService,
                                UserService $userService,
                                EmailService $emailService,
                                TransactionService $transactionService)
    {
        $this->entityManager = $entityManager;
        $this->playConfigRepository = $entityManager->getRepository('EuroMillions\web\entities\PlayConfig');
        $this->betRepository = $entityManager->getRepository('EuroMillions\web\entities\Bet');
        $this->userRepository = $entityManager->getRepository('EuroMillions\web\entities\User');
        $this->di = \Phalcon\Di\FactoryDefault::getDefault();
        $this->currencyConversionService = $currencyConversionService;
        $this->userService = $userService;
        $this->emailService = $emailService;
        $this->transactionService = $transactionService;
    }


    public function storeMessagePrize()
    {

    }

}