<?php


namespace EuroMillions\web\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\megamillions\emailTemplates\WinEmailMegaMillionsAboveTemplate;
use EuroMillions\megamillions\emailTemplates\WinEmailMegaMillionsTemplate;
use EuroMillions\shared\components\transactionBuilders\WinningTransactionDataBuilder;
use EuroMillions\shared\vo\LotteryPrize;
use EuroMillions\shared\vo\Winning;
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
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\vo\enum\TransactionType;
use EuroMillions\web\vo\Raffle;
use Money\Currency;
use Money\Money;
use Phalcon\Di;

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

    //TODO: At this moment for powerball and should be extract to powerball module
    public function calculatePrizeAndInsertMessagesInQueue($date, $lottery)
    {
        try
        {
            /** @var DomainServiceFactory $domainServiceFactory */
            $domainServiceFactory = Di::getDefault()->get('domainServiceFactory');
            $prizeConfigQueue = $this->di->get('config')['aws']['queue_prizes_endpoint'];
            /** @var Lottery $lottery */
            $lottery = $this->lotteryRepository->findOneBy(['name' => $lottery]);

            $resultAwarded = $this->betRepository->getMatchesPlayConfigAndUserFromLotteryByDrawDate($date, $lottery->getId());
            /** @var EuroMillionsDraw $draw */
            $draw = $this->lotteryDrawRepository->findOneBy(['draw_date' => new \DateTime($date), 'lottery' => $lottery->getId()]);
            if(count($resultAwarded) > 0) {
                foreach($resultAwarded as $k => $result)
                {
                    $prize = new LotteryPrize($draw->getBreakDown(), [$result['cnt'],$result['cnt_lucky'],$result['power_play']]);
                    $domainServiceFactory->getServiceFactory()->getCloudService($prizeConfigQueue)->cloud()->queue()->messageProducer([
                        'userId' => $result['userId'],
                        'prize' => $prize->getPrize()->getAmount(),
                        'drawId' => $draw->getId(),
                        'betId' => $result['bet'],
                        'cnt' => $result['cnt'],
                        'cnt_lucky' => $result['cnt_lucky'],
                        'power_play' => $result['power_play']
                    ]);
                }
            }
        }catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }

    }

    //TODO it should new method to award prize
    /**
     * @param $betId
     * @param Money $amount
     * @param array $scalarValues
     * @return ActionResult
     * @throws \Money\UnknownCurrencyException
     */
    public function award($betId, Money $amount, array $scalarValues)
    {
        /** @var Bet $bet */
        $bet = $this->betRepository->findOneBy(['id' => $betId]);
        $lotteryId = $bet->getPlayConfig()->getLottery()->getId();
        $config = $this->di->get('config');
        $threshold_price = new Money((int)$config->threshold_above['value'] * 100, new Currency('EUR'));

        /** @var User $user */
        $user = $this->userRepository->find($scalarValues['userId']);
        try {
            $current_amount = $amount->getAmount() / 100;
            $price = new Money((int)$current_amount, new Currency('EUR'));

            $winning = new Winning($price, $threshold_price, $lotteryId);
            //TODO refactor to move
            $userWalletBefore= $user->getWallet();
            $user->awardPrize($winning);
            $transactionBuilder = new WinningTransactionDataBuilder($winning, $bet, $user, $amount, $userWalletBefore);
            $this->storeAwardTransaction($transactionBuilder->getData(), $transactionBuilder->getType());

            if($transactionBuilder->greaterThanOrEqualThreshold()){
                $this->sendBigWinLotteryEmail($bet, $user, $price, $scalarValues);
            }
            else{
                //TODO: send to new queue
                $this->sendSmallWinLotteryEmail($bet, $user, $price, $scalarValues);
            }

            $this->userRepository->add($user);
            $this->entityManager->flush($user);

            return new ActionResult(true, $user);
        } catch (\Exception $e) {
            return new ActionResult(false);
        }
    }


    public function awardUser(Bet $bet, Money $amount, array $scalarValues)
    {
        $config = $this->di->get('config');
        $threshold_price = new Money((int)$config->threshold_above['value'] * 100, new Currency('EUR'));
        /** @var User $user */
        $user = $this->userRepository->find($scalarValues['userId']);
        try {
            $current_amount = $amount->getAmount() / 100;
            $price = new Money((int)$current_amount, new Currency('EUR'));
            $lotteryId = $bet->getPlayConfig()->getLottery()->getId();

            $winning = new Winning($price, $threshold_price, $lotteryId);
            $userWalletBefore= $user->getWallet();
            $user->awardPrize($winning);
            $transactionBuilder = new WinningTransactionDataBuilder($winning, $bet, $user, $amount,$userWalletBefore);
            $this->storeAwardTransaction($transactionBuilder->getData(), $transactionBuilder->getType());

            if($transactionBuilder->greaterThanOrEqualThreshold()){
                $this->sendBigWinEmail($bet, $user, $price, $scalarValues);
            }
            else{
                $this->sendSmallWinEmail($bet, $user, $price, $scalarValues);
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
    private function sendSmallWinLotteryEmail(Bet $bet, User $user, Money $amount, array $scalarValues)
    {
        $emailBaseTemplate = new EmailTemplate();
        if($bet->getPlayConfig()->getLottery()->getName()=='MegaMillions')
        {
            $emailTemplate = new WinEmailMegaMillionsTemplate($emailBaseTemplate, new WinEmailAboveDataEmailTemplateStrategy($amount, $user->getUserCurrency(), $this->currencyConversionService));
        }else{
            $emailTemplate = new WinEmailPowerBallTemplate($emailBaseTemplate, new WinEmailAboveDataEmailTemplateStrategy($amount, $user->getUserCurrency(), $this->currencyConversionService));
        }

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
        if($bet->getPlayConfig()->getLottery()->getName()=='MegaMillions')
        {
            $emailTemplate = new WinEmailMegaMillionsAboveTemplate($emailBaseTemplate, new WinEmailAboveDataEmailTemplateStrategy($amount, $user->getUserCurrency(), $this->currencyConversionService));
        }else{
            $emailTemplate = new WinEmailAboveTemplate($emailBaseTemplate, new WinEmailAboveDataEmailTemplateStrategy($amount, $user->getUserCurrency(), $this->currencyConversionService));
        }

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
    private function sendBigWinLotteryEmail(Bet $bet, User $user, Money $amount, array $scalarValues)
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