<?php


namespace EuroMillions\eurojackpot\services;

use Phalcon\Di;
use Money\Money;
use Money\Currency;
use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\User;
use EuroMillions\shared\vo\Winning;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\eurojackpot\vo\EuroJackpotPrize;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\services\PrizeCheckoutService as PrizeService;
use EuroMillions\eurojackpot\emailTemplates\WinEmailEuroJackpotTemplate;
use EuroMillions\eurojackpot\emailTemplates\WinEmailEuroJackpotAboveTemplate;
use EuroMillions\shared\components\transactionBuilders\WinningTransactionDataBuilder;
use EuroMillions\web\services\email_templates_strategies\WinEmailAboveDataEmailTemplateStrategy;

class PrizeCheckoutService extends PrizeService
{

    public function calculatePrizeAndInsertMessagesInQueue($date, $lottery)
    {
        try
        {
            /** @var DomainServiceFactory $domainServiceFactory */
            $domainServiceFactory = Di::getDefault()->get('domainServiceFactory');
            $prizeConfigQueue = $this->di->get('config')['aws']['queue_prizes_endpoint'];
            /** @var Lottery $lottery */
            $lottery = $lottery instanceof Lottery ? $lottery : $this->lotteryRepository->findOneBy(['name' => $lottery]);
            $resultAwarded = $this->betRepository->getMatchesPlayConfigAndUserFromEuroJackpotByDrawDate($date, $lottery->getId());
            /** @var EuroMillionsDraw $draw */
            $draw = $this->lotteryDrawRepository->findOneBy(['draw_date' => new \DateTime($date), 'lottery' => $lottery->getId()]);
            if(count($resultAwarded) > 0 && !empty($draw)) {
                foreach($resultAwarded as $k => $result)
                {
                    $prize = new EuroJackpotPrize($draw->getBreakDown(), [$result['cnt'],$result['cnt_lucky']]);
                    $domainServiceFactory->getServiceFactory()->getCloudService($prizeConfigQueue)->cloud()->queue()->messageProducer([
                        'userId' => $result['userId'],
                        'prize' => $prize->getPrize()->getAmount(),
                        'drawId' => $draw->getId(),
                        'betId' => $result['bet'],
                        'cnt' => $result['cnt'],
                        'cnt_lucky' => $result['cnt_lucky'],
                        'power_play' => 0
                    ]);
                }
            }
        }catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }

    }

    /**
     * @param $bet
     * @param Money $amount
     * @param array $scalarValues
     * @return ActionResult
     * @throws \Money\UnknownCurrencyException
     */
    public function award($bet, Money $amount, array $scalarValues)
    {
        /** @var Bet $bet */
        $bet = $bet instanceof Bet ? $bet : $this->betRepository->findOneBy(['id' => $bet]);
        $lotteryId = $bet->getPlayConfig()->getLottery()->getId();
        $config = $this->di->get('config');
        $threshold_price = new Money((int)$config->threshold_above['value'] * 100, new Currency('EUR'));

        /** @var User $user */
        $user = $this->userRepository->find($scalarValues['userId']);
        try {
            $current_amount = $amount->getAmount() / 100;
            $price = new Money((int)$current_amount, new Currency('EUR'));
            $winning = new Winning($price, $threshold_price, $lotteryId);
            $userWalletBefore= $user->getWallet();
            $user->awardPrize($winning);
            $transactionBuilder = new WinningTransactionDataBuilder($winning, $bet, $user, $amount, $userWalletBefore);
            $this->storeAwardTransaction($transactionBuilder->getData(), $transactionBuilder->getType());
            if($transactionBuilder->greaterThanOrEqualThreshold()){
                $this->sendBigWinLotteryEmail($bet, $user, $price, $scalarValues);
            }
            else{
                $this->sendSmallWinLotteryEmail($bet, $user, $price, $scalarValues);
            }

            $this->userRepository->add($user);
            $this->entityManager->flush($user);

            return new ActionResult(true, $user);
        } catch (\Exception $e) {
            return new ActionResult(false);
        }
    }

    /**
     * @param User $user
     * @param Money $amount
     * @param Bet $bet
     * @param array $scalarValues
     * @internal param array $countBalls
     */
    protected function sendBigWinLotteryEmail(Bet $bet, User $user, Money $amount, array $scalarValues)
    {
        $emailBaseTemplate = new EmailTemplate();
        $emailTemplate = new WinEmailEuroJackpotAboveTemplate($emailBaseTemplate, new WinEmailAboveDataEmailTemplateStrategy($amount, $user->getUserCurrency(), $this->currencyConversionService));
        $luckyNumbers = explode(',', $bet->getEuroMillionsDraw()->getResult()->getLuckyNumbers());
        $numLine = $bet->getEuroMillionsDraw()->getResult()->getRegularNumbers() . '( ' . $luckyNumbers[0] . ', ' .$luckyNumbers[1] . ' )';
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
    protected function sendSmallWinLotteryEmail(Bet $bet, User $user, Money $amount, array $scalarValues)
    {
        $emailBaseTemplate = new EmailTemplate();
        $emailTemplate = new WinEmailEuroJackpotTemplate($emailBaseTemplate, new WinEmailAboveDataEmailTemplateStrategy($amount, $user->getUserCurrency(), $this->currencyConversionService));
        $luckyNumbers = explode(',', $bet->getEuroMillionsDraw()->getResult()->getLuckyNumbers());
        $numLine = $bet->getEuroMillionsDraw()->getResult()->getRegularNumbers() . '( ' . $luckyNumbers[0] . ', ' .$luckyNumbers[1] . ' )';
        $emailTemplate->setWinningLine($numLine);
        $emailTemplate->setNummBalls($scalarValues['matches']['cnt']);
        $emailTemplate->setStarBalls($scalarValues['matches']['cnt_lucky']);
        $emailTemplate->setUser($user);
        $emailTemplate->setResultAmount($amount);
        $this->emailService->sendTransactionalEmail($user, $emailTemplate);
    }
}