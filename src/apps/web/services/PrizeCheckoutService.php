<?php


namespace EuroMillions\web\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\shared\vo\PowerBallPrize;
use EuroMillions\shared\vo\Wallet;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\WinEmailAboveTemplate;
use EuroMillions\web\emailTemplates\WinEmailPowerBallAboveTemplate;
use EuroMillions\web\emailTemplates\WinEmailPowerBallTemplate;
use EuroMillions\web\emailTemplates\WinEmailTemplate;
use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\repositories\BetRepository;
use EuroMillions\web\repositories\LotteryDrawRepository;
use EuroMillions\web\repositories\PlayConfigRepository;
use EuroMillions\web\repositories\UserRepository;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\services\email_templates_strategies\WinEmailAboveDataEmailTemplateStrategy;
use EuroMillions\web\vo\enum\TransactionType;
use EuroMillions\web\vo\Raffle;
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

    /** @var UserRepository */
    private $userRepository;

    /** @var  UserService */
    private $userService;

    /** @var CurrencyConversionService */
    private $currencyConversionService;

    /** @var  EmailService */
    private $emailService;

    /** @var  LotteryDrawRepository */
    private $lotteryDrawRepository;

    /** @var  TransactionService */
    private $transactionService;

    private $di;


    public function __construct(EntityManager $entityManager, CurrencyConversionService $currencyConversionService, UserService $userService, EmailService $emailService, TransactionService $transactionService)
    {
        $this->entityManager = $entityManager;
        $this->playConfigRepository = $entityManager->getRepository('EuroMillions\web\entities\PlayConfig');
        $this->betRepository = $entityManager->getRepository('EuroMillions\web\entities\Bet');
        $this->userRepository = $entityManager->getRepository('EuroMillions\web\entities\User');
        $this->lotteryDrawRepository = $this->entityManager->getRepository('EuroMillions\web\entities\EuroMillionsDraw');
        $this->lotteryRepository = $this->entityManager->getRepository('EuroMillions\web\entities\Lottery');
        $this->di = \Phalcon\Di\FactoryDefault::getDefault();
        $this->currencyConversionService = $currencyConversionService;
        $this->userService = $userService;
        $this->emailService = $emailService;
        $this->transactionService = $transactionService;
    }

    public function playConfigsWithBetsAwarded(\DateTime $date)
    {
        if (!$date) {
            $date = new \DateTime();
        }
        $result_awarded = $this->betRepository->getCheckResult($date->format('Y-m-d'));
        if (count($result_awarded)) {
            return new ActionResult(true, $result_awarded);
        } else {
            return new ActionResult(false);
        }
    }

    //TODO: At this moment for powerball
    public function calculatePrizeAndInsertMessagesInQueue($date, $lottery)
    {
        try
        {
            $resultAwarded = $this->betRepository->getMatchesPlayConfigAndUserFromPowerBallByDrawDate($date);
            /** @var Lottery $lottery */
            $lottery = $this->lotteryRepository->findOneBy(['name' => $lottery]);
            /** @var EuroMillionsDraw $draw */
            $draw = $this->lotteryDrawRepository->getLastDraw($lottery,new \DateTime($date));
            if(count($resultAwarded) > 0) {
                foreach($resultAwarded as $result)
                {
                    $prize = new PowerBallPrize($draw->getBreakDown(), [$result['cnt'],$result['cnt_lucky'],$result['power_play']]);

                }
            }
        }catch(\Exception $e)
        {

        }

    }


    public function awardUser(Bet $bet, Money $amount, array $scalarValues)
    {
        $config = $this->di->get('config');
        $threshold_price = new Money((int)$config->threshold_above['value'] * 100, new Currency('EUR'));
        /** @var User $user */
        $user = $this->userRepository->find($scalarValues['userId']);
        try {
            //EMTD WinningVO to avoid this logic
            $data = $this->prepareDataToTransaction($bet, $user, $amount);
            $current_amount = $amount->getAmount() / 100;
            $amount = new Money((int)$current_amount, new Currency('EUR'));
            if ($amount->greaterThanOrEqual($threshold_price)) {
                $user->setWinningAbove($amount);
                $user->setShowModalWinning(1);
                $this->storeAwardTransaction($data, TransactionType::BIG_WINNING);
                $this->sendBigWinEmail($bet, $user, $amount, $scalarValues);
            } else {
                $user->awardPrize($amount);
                $data['walletAfter'] = $user->getWallet();
                $data['state'] = '';
                $this->storeAwardTransaction($data, TransactionType::WINNINGS_RECEIVED);
                $this->sendSmallWinEmail($bet, $user, $amount, $scalarValues);
            }
            $this->userRepository->add($user);
            $this->entityManager->flush($user);
            return new ActionResult(true, $user);
        } catch (\Exception $e) {
            return new ActionResult(false);
        }
    }

    public function getBetsRaffle(Bet $bet, $drawdate)
    {
        $data = $this->betRepository->getRafflePlayedLastDraw($drawdate);

        return $data;
    }

    public function matchNumbersUser(Bet $bet, array $scalarValues, \DateTime $drawDate, Money $amount)
    {
        try {
            $new_amount = new Money((int)$amount->getAmount() / 100, new Currency('EUR'));
            $match = $this->betRepository->getMatchNumbers($drawDate, $scalarValues['userId']);
            /** @var Bet $currentBet */
            $currentBet = $this->betRepository->findOneBy(['id' => $bet->getId()]);
            $currentBet->setMatchNumbers($match['numbers']);
            $currentBet->setMatchStars($match['stars']);
            $currentBet->setPrize($new_amount);
            $this->entityManager->detach($currentBet);
            $this->betRepository->add($currentBet);
            $this->entityManager->flush();
        } catch (\Exception $e) {

        }
    }

    private function storeAwardTransaction(array $data, $transactionType)
    {
        $this->transactionService->storeTransaction($transactionType, $data);
    }

    private function prepareDataToTransaction(Bet $bet, User $user, Money $amount)
    {
        return [
            'draw_id' => $bet->getEuroMillionsDraw()->getId(),
            'bet_id' => $bet->getId(),
            'amount' => $amount->getAmount(),
            'user' => $user,
            'walletBefore' => $user->getWallet(),
            'walletAfter' => $user->getWallet(),
            'state' => 'pending',
            'now' => new \DateTime()
        ];
    }

    /**
     * @param User $user
     * @param Money $amount
     * @param Bet $bet
     * @param array $scalarValues
     * @internal param array $countBalls
     */
    private function sendSmallWinEmail(Bet $bet, User $user, Money $amount, array $scalarValues)
    {
        $emailBaseTemplate = new EmailTemplate();
        $emailTemplate = new WinEmailTemplate($emailBaseTemplate, new WinEmailAboveDataEmailTemplateStrategy($amount, $user->getUserCurrency(), $this->currencyConversionService));
        $numLine = $bet->getEuroMillionsDraw()->getResult()->getRegularNumbers() . '( ' . $bet->getEuroMillionsDraw()->getResult()->getLuckyNumbers() . ' )';
        $emailTemplate->setWinningLine($numLine);
        $emailTemplate->setNummBalls($scalarValues['matches']['cnt']);
        $emailTemplate->setStarBalls($scalarValues['matches']['cnt_lucky']);
        $emailTemplate->setUser($user);
        $emailTemplate->setResultAmount($amount);
        $this->emailService->sendTransactionalEmail($user, $emailTemplate);
    }


    /**
     * @param User $user
     * @param Money $amount
     * @param Bet $bet
     * @param array $scalarValues
     * @internal param array $countBalls
     */
    private function sendSmallWinPowerBallEmail(Bet $bet, User $user, Money $amount, array $scalarValues)
    {
        $emailBaseTemplate = new EmailTemplate();
        $emailTemplate = new WinEmailPowerBallTemplate($emailBaseTemplate, new WinEmailAboveDataEmailTemplateStrategy($amount, $user->getUserCurrency(), $this->currencyConversionService));
        $powerBall = explode(',', $bet->getEuroMillionsDraw()->getResult()->getLuckyNumbers());
        $numLine = $bet->getEuroMillionsDraw()->getResult()->getRegularNumbers() . '( ' . $powerBall[1] . ' )';
        $emailTemplate->setWinningLine($numLine);
        $emailTemplate->setNummBalls($scalarValues['matches']['cnt']);
        $emailTemplate->setStarBalls($scalarValues['matches']['cnt_lucky']);
        $emailTemplate->setUser($user);
        $emailTemplate->setResultAmount($amount);
        $this->emailService->sendTransactionalEmail($user, $emailTemplate);
    }

    /**
     * @param User $user
     * @param Money $amount
     * @param Bet $bet
     * @param array $scalarValues
     * @internal param array $countBalls
     */
    private function sendBigWinEmail(Bet $bet, User $user, Money $amount, array $scalarValues)
    {
        $emailBaseTemplate = new EmailTemplate();
        $emailTemplate = new WinEmailAboveTemplate($emailBaseTemplate, new WinEmailAboveDataEmailTemplateStrategy($amount, $user->getUserCurrency(), $this->currencyConversionService));
        $numLine = $bet->getEuroMillionsDraw()->getResult()->getRegularNumbers() . '( ' . $bet->getEuroMillionsDraw()->getResult()->getLuckyNumbers() . ' )';
        $emailTemplate->setWinningLine($numLine);
        $emailTemplate->setNummBalls($scalarValues['matches']['cnt']);
        $emailTemplate->setStarBalls($scalarValues['matches']['cnt_lucky']);
        $emailTemplate->setUser($user);
        $emailTemplate->setResultAmount($amount);
        $this->emailService->sendTransactionalEmail($user, $emailTemplate);
    }

    /**
     * @param User $user
     * @param Money $amount
     * @param Bet $bet
     * @param array $scalarValues
     * @internal param array $countBalls
     */
    private function sendBigWinPowerBallEmail(Bet $bet, User $user, Money $amount, array $scalarValues)
    {
        $emailBaseTemplate = new EmailTemplate();
        $emailTemplate = new WinEmailPowerBallAboveTemplate($emailBaseTemplate, new WinEmailAboveDataEmailTemplateStrategy($amount, $user->getUserCurrency(), $this->currencyConversionService));
        $powerBall = explode(',', $bet->getEuroMillionsDraw()->getResult()->getLuckyNumbers());
        $numLine = $bet->getEuroMillionsDraw()->getResult()->getRegularNumbers() . '( ' . $powerBall[1] . ' )';
        $emailTemplate->setWinningLine($numLine);
        $emailTemplate->setNummBalls($scalarValues['matches']['cnt']);
        $emailTemplate->setStarBalls($scalarValues['matches']['cnt_lucky']);
        $emailTemplate->setUser($user);
        $emailTemplate->setResultAmount($amount);
        $this->emailService->sendTransactionalEmail($user, $emailTemplate);
    }

    public function sendEmailWinnerRaffle($betsRaffle, Raffle $lastRaffle)
    {
        foreach ($betsRaffle as $bet) {
            if ($lastRaffle->equals(new Raffle($bet['raffle']))) {
                /* @var PlayConfig $playconfig */
                $playconfig = $this->playConfigRepository->find($bet['playconfig']);
                /* @var User $user */
                $user = $playconfig->getUser();
                $name = 'A user has won the Raffle';
                $type = '';
                $message = '<b>User name: </b>' . $user->getName() . '\n';
                $message .= '<b>User Id : </b>' . $user->getId() . '\n';
                $message .= '<b>Email </b>' . $user->getEmail() . '\n';
                $time = $now = new \DateTime('NOW');
                $this->emailService->sendLog($name, $type, $message, $time);
            }
        }

    }

}