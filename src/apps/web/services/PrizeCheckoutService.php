<?php


namespace EuroMillions\web\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\shared\vo\Wallet;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\WinEmailAboveTemplate;
use EuroMillions\web\emailTemplates\WinEmailTemplate;
use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\User;
use EuroMillions\web\repositories\BetRepository;
use EuroMillions\web\repositories\PlayConfigRepository;
use EuroMillions\web\repositories\UserRepository;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\services\email_templates_strategies\WinEmailAboveDataEmailTemplateStrategy;
use EuroMillions\web\vo\enum\TransactionType;
use Money\Currency;
use Money\Money;

class PrizeCheckoutService
{

    private $entityManager;

    /**
     * @var PlayConfigRepository
     */
    private $playConfigRepository;

    /** @var  BetRepository */
    private $betRepository;

    /** @var UserRepository  */
    private $userRepository;

    /** @var  UserService */
    private $userService;

    /** @var CurrencyConversionService  */
    private $currencyConversionService;

    /** @var  EmailService */
    private $emailService;

    /** @var  TransactionService */
    private $transactionService;

    private $di;


    public function __construct(EntityManager $entityManager, CurrencyConversionService $currencyConversionService, UserService $userService, EmailService $emailService, TransactionService $transactionService)
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

    public function playConfigsWithBetsAwarded(\DateTime $date)
    {
        if(!$date) {
            $date = new \DateTime();
        }
        $result_awarded = $this->betRepository->getCheckResult($date->format('Y-m-d'));
        if(count($result_awarded)){
            return new ActionResult(true,$result_awarded);
        }else{
            return new ActionResult(false);
        }
    }

    public function awardUser(Bet $bet, $userId, Money $amount, array $countBalls)
    {
        $config = $this->di->get('config');
        $threshold_price = new Money((int) $config->threshold_above['value'] * 100, new Currency('EUR'));
        /** @var User $user */
        $user = $this->userRepository->find($userId);
        try{
            //EMTD WinningVO to avoid this logic
            $data= $this->prepareDataToTransaction($bet,$user,$amount);
            $current_amount = $amount->getAmount() / 100;
            $amount = new Money((int) $current_amount, new Currency('EUR'));
            if($amount->greaterThanOrEqual($threshold_price)) {
                $user->setWinningAbove($amount);
                $user->setShowModalWinning(1);
                $this->storeAwardTransaction($data, TransactionType::BIG_WINNING);
                $this->sendBigWinEmail($user, $amount, $bet, $countBalls);
            } else {
                $user->awardPrize($amount);
                $data['walletAfter'] = $user->getWallet();
                $data['state'] = '';
                $this->storeAwardTransaction($data, TransactionType::WINNINGS_RECEIVED);
                $this->sendSmallWinEmail($user, $amount, $bet, $countBalls);
            }
            $this->userRepository->add($user);
            $this->entityManager->flush($user);
            return new ActionResult(true,$user);
        }catch(\Exception $e){
            return new ActionResult(false);
        }
    }

    private function storeAwardTransaction(array $data, $transactionType)
    {
        $this->transactionService->storeTransaction($transactionType,$data);
    }

    private function prepareDataToTransaction(Bet $bet, User $user, Money $amount)
    {
        return [
            'draw_id' => $bet->getEuroMillionsDraw()->getId(),
            'bet_id'  => $bet->getId(),
            'amount'  => $amount->getAmount(),
            'user'    => $user,
            'walletBefore' => $user->getWallet(),
            'walletAfter' => $user->getWallet(),
            'state'       => 'pending',
            'now'         => new \DateTime()
        ];
    }

    /**
     * @param User $user
     * @param Money $amount
     * @param Bet $bet
     * @param array $countBalls
     */
    private function sendSmallWinEmail(User $user, Money $amount, Bet $bet, array $countBalls)
    {
        $emailBaseTemplate = new EmailTemplate();
        $emailTemplate = new WinEmailTemplate($emailBaseTemplate, new WinEmailAboveDataEmailTemplateStrategy($amount, $user->getUserCurrency(), $this->currencyConversionService));
        $numLine= $bet->getPlayConfig()->getLine()->getRegularNumbers() . '( ' . $bet->getPlayConfig()->getLine()->getLuckyNumbers() . ' )';
        $emailTemplate->setWinningLine($numLine);
        $emailTemplate->setNummBalls($countBalls['cnt']);
        $emailTemplate->setStarBalls($countBalls['cnt_lucky']);
        $emailTemplate->setUser($user);
        $emailTemplate->setResultAmount($amount);
        $this->emailService->sendTransactionalEmail($user, $emailTemplate);
    }

    /**
     * @param User $user
     * @param Money $amount
     * @param Bet $bet
     * @param array $countBalls
     */
    private function sendBigWinEmail(User $user, Money $amount, Bet $bet, array $countBalls)
    {
        $emailBaseTemplate = new EmailTemplate();
        $emailTemplate = new WinEmailAboveTemplate($emailBaseTemplate, new WinEmailAboveDataEmailTemplateStrategy($amount, $user->getUserCurrency(), $this->currencyConversionService));
        $numLine= $bet->getPlayConfig()->getLine()->getRegularNumbers() . '( ' . $bet->getPlayConfig()->getLine()->getLuckyNumbers() . ' )';
        $emailTemplate->setWinningLine($numLine);
        $emailTemplate->setNummBalls($countBalls['cnt']);
        $emailTemplate->setStarBalls($countBalls['cnt_lucky']);
        $emailTemplate->setUser($user);
        $emailTemplate->setResultAmount($amount);
        $this->emailService->sendTransactionalEmail($user, $emailTemplate);
    }


}