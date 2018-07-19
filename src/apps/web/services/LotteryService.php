<?php

namespace EuroMillions\web\services;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\UnexpectedResultException;
use EuroMillions\shared\config\Namespaces;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\IEmailTemplate;
use EuroMillions\web\emailTemplates\PowerBallPurchaseConfirmationEmailTemplate;
use EuroMillions\web\emailTemplates\PowerBallPurchaseSubscriptionConfirmationEmailTemplate;
use EuroMillions\web\emailTemplates\PurchaseConfirmationEmailTemplate;
use EuroMillions\web\emailTemplates\PurchaseSubscriptionConfirmationEmailTemplate;
use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\exceptions\BadSiteConfiguration;
use EuroMillions\web\exceptions\DataMissingException;
use EuroMillions\web\interfaces\IJackpot;
use EuroMillions\web\repositories\LotteryDrawRepository;
use EuroMillions\web\repositories\LotteryRepository;
use EuroMillions\web\repositories\PlayConfigRepository;
use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;
use EuroMillions\web\services\external_apis\LottorisqApi;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use EuroMillions\web\services\user_notifications_strategies\UserNotificationAutoPlayNoFunds;
use EuroMillions\web\services\user_notifications_strategies\UserNotificationResultsStrategy;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use EuroMillions\web\vo\dto\EuroMillionsDrawDTO;
use EuroMillions\web\vo\dto\PowerBallDrawBreakDownDTO;
use EuroMillions\web\vo\dto\PowerBallDrawDTO;
use EuroMillions\web\vo\enum\TransactionType;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\EuroMillionsJackpot;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\Raffle;
use Phalcon\Http\Client\Provider\Curl;

class LotteryService
{
    /** @var EntityManager */
    private $entityManager;
    /** @var LotteryDrawRepository */
    private $lotteryDrawRepository;
    /** @var PlayConfigRepository */
    private $playConfigRepository;
    /** @var LotteryRepository */
    private $lotteryRepository;
    /** @var LotteriesDataService */
    private $lotteriesDataService;
    /** @var  UserService $userService */
    private $userService;
    /** @var  BetService $betService */
    private $betService;
    /** @var EmailService $emailService */
    private $emailService;
    /** @var  UserNotificationsService $userNotificationsService */
    private $userNotificationsService;
    /** @var WalletService $walletService */
    private $walletService;

    public function __construct(EntityManager $entityManager,
                                LotteriesDataService $lotteriesDataService,
                                UserService $userService,
                                BetService $betService,
                                EmailService $emailService,
                                UserNotificationsService $userNotificationsService,
                                WalletService $walletService
    )
    {
        $this->entityManager = $entityManager;
        $this->lotteryDrawRepository = $this->entityManager->getRepository(Namespaces::ENTITIES_NS . 'EuroMillionsDraw');
        $this->lotteryRepository = $this->entityManager->getRepository(Namespaces::ENTITIES_NS . 'Lottery');
        $this->playConfigRepository = $this->entityManager->getRepository(Namespaces::ENTITIES_NS . 'PlayConfig');
        $this->lotteriesDataService = $lotteriesDataService;
        $this->emailService = $emailService;
        $this->userService = $userService;
        $this->betService = $betService;
        $this->userNotificationsService = $userNotificationsService;
        $this->walletService = $walletService;
    }

    /**
     * @param $lotteryName
     * @param \DateTime|null $today
     * @return \DateTime
     */
    public function getLastDrawDate($lotteryName, \DateTime $today = null)
    {
        if (!$today) {
            $today = new \DateTime();
        }
        return $this->lotteryRepository->findOneBy(['name' => $lotteryName])->getLastDrawDate($today);
    }

    /**
     * @param $lotteryName
     * @return IJackpot
     */
    public function getNextJackpot($lotteryName)
    {
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->getLotteryByName($lotteryName);
        /** @var EuroMillionsJackpot $jackpot_object */
        $jackpot_object = 'EuroMillions\web\vo\\' . $lotteryName . 'Jackpot';
        try {
            $next_jackpot = $this->lotteryDrawRepository->getNextJackpot($lottery);
            return $jackpot_object::fromAmountIncludingDecimals($next_jackpot->getAmount());
        } catch (DataMissingException $e) {
            try {
                $next_jackpot = ($lotteryName == 'PowerBall') ?
                    $this->lotteriesDataService->updateNextDrawJackpotPowerball($lotteryName) :
                    $this->lotteriesDataService->updateNextDrawJackpot($lotteryName);
                if($next_jackpot == null ) return $jackpot_object::fromAmountIncludingDecimals(null);
                return $jackpot_object::fromAmountIncludingDecimals($next_jackpot->getAmount());
            } catch (DataMissingException $e) {
                return $jackpot_object::fromAmountIncludingDecimals(null);
            }
        }
    }

    /**
     * @param $lotteryName
     * @return IJackpot
     */
    public function getAllNextJackpots()
    {
        $allJackpots = null;
        try {
            $next_jackpots = $this->lotteryDrawRepository->getAllNextJackpots();
            foreach($next_jackpots as $key=>$val) {
                /** @var EuroMillionsJackpot $jackpot_object */
                $jackpot_object = 'EuroMillions\web\vo\\' . $key . 'Jackpot';
                $allJackpots[$key] = $jackpot_object::fromAmountIncludingDecimals($val->getAmount());
            }
            return $allJackpots;
        } catch (DataMissingException $e) {
//            try {
//                $next_jackpot = ($lotteryName == 'PowerBall') ?
//                    $this->lotteriesDataService->updateNextDrawJackpotPowerball($lotteryName) :
//                    $this->lotteriesDataService->updateNextDrawJackpot($lotteryName);
//                return $jackpot_object::fromAmountIncludingDecimals($next_jackpot->getAmount());
//            } catch (DataMissingException $e) {
//                return $jackpot_object::fromAmountIncludingDecimals(null);
//            }
        }
    }

    public function getLastResult($lotteryName)
    {
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->getLotteryByName($lotteryName);
        $result = [];
        try {
            /** @var EuroMillionsLine $lottery_result */
            $lottery_result = $this->lotteryDrawRepository->getLastResult($lottery);
        } catch (DataMissingException $e) {
//            $lottery_result = $this->lotteriesDataService->updateLastDrawResult($lotteryName);
        }
        $result['regular_numbers'] = explode(',', $lottery_result->getRegularNumbers());
        $result['lucky_numbers'] = explode(',', $lottery_result->getLuckyNumbers());
        return $result;
    }

    public function getLastFiveResults($lotteryName)
    {
        $lottery_results = $this->lotteryDrawRepository->getLastSixResults(
            $this->lotteryRepository->getLotteryByName($lotteryName)
        );

        $result = [];
        $cont = 0;
        /** @var EuroMillionsDraw $lottery_result */
        foreach ($lottery_results as $lottery_result) {
            if ($cont != 0) {
                $result[$cont-1]['regular_numbers'] = explode(',', $lottery_result->getResult()->getRegularNumbers());
                $result[$cont-1]['lucky_numbers'] = explode(',', $lottery_result->getResult()->getLuckyNumbers());
                $result[$cont-1]['draw_date'] =  $lottery_result->getDrawDate();
            }
            $cont++;
        }

        return $result;
    }

    public function getLastBreakdown($lotteryName)
    {
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->getLotteryByName($lotteryName);
        $result = [];
        try {
            /** @var EuroMillionsDrawBreakDown $lottery_result */
            $lottery_result = $this->lotteryDrawRepository->getLastBreakdown($lottery);
        } catch (DataMissingException $e) {
//            $lottery_result = $this->lotteriesDataService->updateLastDrawResult($lotteryName);
        }
        return $lottery_result;
    }

    public function getTimeToNextDraw($lotteryName, \DateTime $now = null)
    {
        list($now, $lottery) = $this->getLotteryAndNowDate($lotteryName, $now);
        $next_draw_date = $lottery->getNextDrawDate($now);
        return $now->diff($next_draw_date);
    }

    public function getNextDateDrawByLottery($lotteryName, \DateTime $now = null, $showLotteryLocalTime = true)
    {
        list($now, $lottery) = $this->getLotteryAndNowDate($lotteryName, $now);
        $date = $lottery->getNextDrawDate($now);
        if($showLotteryLocalTime) {
            if($lottery->getName() !== 'EuroMillions')
            {
                //TODO: Timezone should be store in Lottery entity
                $date = new DateTime($date->format('Y-m-d'). ' ' .$lottery->getDrawTime(), new \DateTimeZone('America/New_York'));
                $date->setTimezone(new \DateTimeZone('Europe/Madrid'))->modify('+30 minutes')->format('Y-m-d H:i:s');;
                return $date;
            }
        }
        return $date;
    }

    public function getNextDrawAndJackpotForAllLotteries(\DateTime $now = null) {
        $jackpots = $this->getAllNextJackpots();
        list($now, $lotteries) = $this->getAllLotteriesAndNowDate($now);
        $time = null;
        foreach($lotteries as $lottery) {
            if(trim($lottery->getName()) != 'Christmas') {
                $time[$lottery->getName()]['show_days'] = (new \DateTime())->diff($this->getNextDateDrawByLottery($lottery->getName())->modify('-1 hours'))->format('%a');
                $time[$lottery->getName()]['jackpot_value'] = $jackpots[$lottery->getName()]->getAmount();
                $time[$lottery->getName()]['link'] = $lottery->getName().'/play';
                $time[$lottery->getName()]['name'] = $lottery->getName().' Jackpot';
            }
        }

        return $time;
    }

    public function getRecurrentDrawDates($lotteryName, $iteration = 12, \DateTime $now = null)
    {

        list($now, $lottery) = $this->getLotteryAndNowDate($lotteryName, $now);
        return $lottery->getRecurringIntervalDrawDate($iteration, $now);
    }

    public function getNextDrawByLottery($lotteryName, \DateTime $now = null)
    {
        list($now, $lottery) = $this->getLotteryAndNowDate($lotteryName, $now);
        if ($lotteryName == 'Christmas') {
            /** @var EuroMillionsDraw[] $euroMillionsDraw */
            $euroMillionsDraw = $this->lotteryDrawRepository->getNextDraw($lottery, $lottery->getNextDrawDate($now));
            if ($euroMillionsDraw !== null) {
                return new ActionResult(true, $euroMillionsDraw);
            } else {
                return new ActionResult(false);
            }
        } else {

            if ($lottery !== null) {
                /** @var EuroMillionsDraw[] $euroMillionsDraw */
                $euroMillionsDraw = $this->lotteryDrawRepository->getNextDraw($lottery, $lottery->getNextDrawDate($now));

                if ($euroMillionsDraw !== null) {
                    return new ActionResult(true, $euroMillionsDraw);
                } else {
                    return new ActionResult(false);
                }
            }
        }

        return new ActionResult(false);
    }

    public function getPowerBallDrawsDTO($lotteryName, $limit = 13,WebLanguageStrategy $webLanguageStrategy)
    {

        $emTranslationAdapter = new EmTranslationAdapter($webLanguageStrategy->get(), $this->entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));
        /** @var Lottery $lottery */
        $lottery = $this->getLotteryByName($lotteryName);
        if (null !== $lottery) {
            try {
                $euroMillionsDraws = $this->lotteryDrawRepository->getDraws($lottery, $limit);
                $powerBallDrawsDTO = [];
                //TODO please, we need move results, jackpot from Draws to another service. Inject this depencencies for example
                /** @var EuroMillionsDraw[] $euroMillionsDraws */
                foreach ($euroMillionsDraws as $euroMillionsDraw) {
                    $powerBallDrawDTO = new PowerBallDrawDTO($euroMillionsDraw, $emTranslationAdapter);
                    $powerBallDrawsDTO[] = $powerBallDrawDTO;
                }
                return new ActionResult(true, $powerBallDrawsDTO);
            } catch (DataMissingException $e) {
                return new ActionResult(false);
            }
        }
        return new ActionResult(false);
    }

    public function getDrawsDTO($lotteryName, $limit = 13)
    {

        /** @var Lottery $lottery */
        $lottery = $this->getLotteryByName($lotteryName);
        if (null !== $lottery) {
            try {
                $euroMillionsDraws = $this->lotteryDrawRepository->getDraws($lottery, $limit);
                $euroMillionsDrawsDTO = [];
                /** @var EuroMillionsDraw[] $euroMillionsDraws */
                foreach ($euroMillionsDraws as $euroMillionsDraw) {
                    $euromillionsBreakDownDataDTO = new EuroMillionsDrawBreakDownDTO($euroMillionsDraw->getBreakDown());
                    $euroMillionsDrawDTO = new EuroMillionsDrawDTO($euromillionsBreakDownDataDTO, $euroMillionsDraw);
                    $euroMillionsDrawsDTO[] = $euroMillionsDrawDTO;
                }
                return new ActionResult(true, $euroMillionsDrawsDTO);
            } catch (DataMissingException $e) {
                return new ActionResult(false);
            }
        }
        return new ActionResult(false);

    }

    public function getLotteryConfigByName($lotteryName)
    {
        /** @var Lottery $lottery */
        $lottery = $this->getLotteryByName($lotteryName);
        if (null !== $lottery) {
            return $lottery;
        } else {
            return new ActionResult(false, 'Lottery unknown');
        }
    }

    public function lastBreakDown($lotteryName, \DateTime $now = null)
    {
        list($now, $lottery) = $this->getLotteryAndNowDate($lotteryName, $now);
        $last_draw_date = $lottery->getLastDrawDate($now);
        /** @var EuroMillionsDraw $draw */
        $draw = $this->lotteryDrawRepository->findOneBy(['lottery' => $lotteryName, 'draw_date' => $last_draw_date]);
        if (null !== $draw) {
            return new ActionResult(true, $draw);
        } else {
            return new ActionResult(false);
        }
    }

    public function getSingleBetPriceByLottery($lotteryName)
    {
        /** @var Lottery $lottery */
        $lottery = $this->getLotteryByName($lotteryName);
        if (null !== $lottery) {
            return $lottery->getSingleBetPrice();
        } else {
            throw new BadSiteConfiguration('The lottery ' . $lotteryName . ' is not properly configured');
        }
    }

    public function addRaffle(Lottery $lottery, Raffle $raffle)
    {
        try {
            /** @var EuroMillionsDraw $euromillionsDraw */
            $euromillionsDraw = $this->lotteryDrawRepository->getLastDraw($lottery);
            $euromillionsDraw->setRaffle($raffle);
            $this->entityManager->persist($euromillionsDraw);
            $this->entityManager->flush($euromillionsDraw);
        } catch (\Exception $e) {
            throw new UnexpectedResultException();
        }
    }

    public function getLastJackpot($lotteryName)
    {
        return $this->lotteryDrawRepository->getLastJackpot($lotteryName);
    }

    public function getLastRaffle($lotteryName, \DateTime $today)
    {
        return $this->lotteryDrawRepository->getLastRaffle($lotteryName, $today);
    }

    public function getLastDrawWithBreakDownByDate($lotteryName, \DateTime $today)
    {
        return $this->getBreakDown($lotteryName, $today, 'getLastBreakDownData');
    }

    public function getDrawWithBreakDownByDate($lotteryName, \DateTime $today)
    {
        return $this->getBreakDown($lotteryName, $today, 'getBreakDownData');

    }

    public function placeBetForNextDraw(Lottery $lottery, \DateTime $dateNextDraw = null)
    {
        $playConfigs = $this->playConfigRepository->getEuromillionsSubscriptionsActives();

        if (!is_null($playConfigs)) {
            $nextDrawDate = $lottery->getNextDrawDate($dateNextDraw);
            /** @var PlayConfig $playConfig */
            $cont = 0;
            foreach ($playConfigs as $playConfig) {
                echo $playConfig->getUser()->getId() . ' - ' . $playConfig->getId() . ' \n' ;
                $cont++;
                $euroMillionsDraw = $this->lotteryDrawRepository->getNextDraw($lottery, $lottery->getNextDrawDate($dateNextDraw));

                if (empty($this->betService->obtainBetsWithAPlayConfigAndAEuromillionsDraw($playConfig, $euroMillionsDraw))) {
                    $price = $this->lotteriesDataService->getPriceForNextDraw([$playConfig]);
                    if ($playConfig->getUser()->getWallet()->getSubscription()->getAmount() >= $price->getAmount()) {
                        /** @var EuroMillionsDraw $euroMillionsDraw */
                        $result = $this->betService->validation($playConfig, $euroMillionsDraw, $nextDrawDate);
                        if ($result->success()) {
                            $walletBefore = $playConfig->getUser()->getWallet();
                            $this->walletService->payWithSubscription($playConfig->getUser(), $playConfig);
                            $dataTransaction = [
                                'lottery_id' => $lottery->getId(),
                                'numBets' => 1,
                                'walletBefore' => $walletBefore,
                                'amountWithCreditCard' => 0,
                                'playConfigs' => $playConfig->getId(),
                                'discount' => $playConfig->getDiscount(),
                            ];
                            $this->walletService->purchaseTransactionGrouped($playConfig->getUser(), TransactionType::AUTOMATIC_PURCHASE, $dataTransaction);
                            try {
                                $this->sendEmailPurchase($playConfig->getUser(), $playConfig);
                            } catch (\Exception $e) {
                                echo $e->getMessage();
                            }
                        }
                    } else {
                        try {
                            $userNotificationAutoPlayNoFunds = new UserNotificationAutoPlayNoFunds($this->userService);
                            $hasNotification = $this->userNotificationsService->hasNotificationActive($userNotificationAutoPlayNoFunds, $playConfig->getUser());
                            if ($hasNotification) {
                                $this->emailService->sendLowBalanceEmail($playConfig->getUser());
                            }
                        } catch (\Exception $e) {
                            echo $e->getMessage();
                        }
                    }
                }
            }
            echo $cont;
        }
    }

    public function placePowerBallBetForNextDraw(Lottery $lottery, \DateTime $dateNextDraw = null)
    {
        $playConfigs = $this->playConfigRepository->getPowerBallSubscriptionsActives();

        if (!is_null($playConfigs)) {
            $nextDrawDate = $lottery->getNextDrawDate($dateNextDraw);
            /** @var PlayConfig $playConfig */
            $cont = 0;
            foreach ($playConfigs as $playConfig) {
                $cont++;
                $euroMillionsDraw = $this->lotteryDrawRepository->getNextDraw($lottery, $lottery->getNextDrawDate($dateNextDraw));
                if (empty($this->betService->obtainBetsWithAPlayConfigAndAEuromillionsDraw($playConfig, $euroMillionsDraw))) {
                    $price = $this->lotteriesDataService->getPriceForNextDraw([$playConfig]);

                    if ($playConfig->getUser()->getWallet()->getSubscription()->getAmount() >= $price->getAmount()) {

                        $APIPlayConfigs = json_encode([$playConfig]);
                        $result_validation = json_decode((new LottorisqApi())->book($APIPlayConfigs)->body);
                        $this->betService->validationLottoRisq($playConfig, $euroMillionsDraw, $lottery->getNextDrawDate(), null, $result_validation->uuid);
                        if ($result_validation->success) {
                            $walletBefore = $playConfig->getUser()->getWallet();
                            $this->walletService->payWithSubscription($playConfig->getUser(), $playConfig);
                            $dataTransaction = [
                                'lottery_id' => $lottery->getId(),
                                'numBets' => 1,
                                'walletBefore' => $walletBefore,
                                'amountWithCreditCard' => 0,
                                'playConfigs' => $playConfig->getId(),
                                'discount' => $playConfig->getDiscount(),
                            ];
                            $this->walletService->purchaseTransactionGrouped($playConfig->getUser(), TransactionType::AUTOMATIC_PURCHASE, $dataTransaction);
                            try {
                                $this->sendPowerBallEmailPurchase($playConfig->getUser(), $playConfig);
                            } catch (\Exception $e) {
                                echo $e->getMessage();
                            }
                        }
                    } else {
                        try {
                            $userNotificationAutoPlayNoFunds = new UserNotificationAutoPlayNoFunds($this->userService);
                            $hasNotification = $this->userNotificationsService->hasNotificationActive($userNotificationAutoPlayNoFunds, $playConfig->getUser());
                            if ($hasNotification) {
                                $this->emailService->sendLowBalanceEmail($playConfig->getUser());
                            }
                        } catch (\Exception $e) {
                            echo $e->getMessage();
                        }
                    }
                }
            }
            echo $cont;
        }
    }

    public function getAllResultFromPowerball(Curl $curl, $config)
    {
        try {
            $curl->setOption(CURLOPT_SSL_VERIFYHOST, false);
            $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
            $result = $curl->get($config->endpoint.'/results',
                [],
                array(
                    "x-api-key: " .$config->api_key,
                    "Content-Type: application/json; charset=utf-8",
                )
            );
            if(!$result) {
                throw new \Exception('No results data');
            }
            return $result;
        } catch (\Exception $e)
        {

        }
    }



    public function getLotteriesOrderedByNextDrawDate(\DateTime $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }
        $new_array = [];
        $lotteries = $this->lotteryRepository->findAll();
        /** @var Lottery $lottery */
        foreach ($lotteries as $lottery) {
            $next_draw = $lottery->getNextDrawDate($now);
            $new_array[$next_draw->format('Y-m-d H:i:s')] = $lottery;
        }
        ksort($new_array);
        return count($new_array) > 0 ? $new_array : [];
    }

    public function sendResultLotteryToUsersWithBets($result, IEmailTemplate $emailTemplate)
    {

        $notificationResultsStrategy = $this->obtainNotificationResultStrategy();
        /** @var Bet $bet */
        foreach ($result as $bet) {
            $user = $bet->getPlayConfig()->getUser();
            $hasNotification = $this->userNotificationsService->hasNotificationActive($notificationResultsStrategy, $user);
            if ($hasNotification === 0) {
                $this->emailService->sendTransactionalEmail($user, $emailTemplate);
            }
        }
    }

    public function sendResultLotteryToUsers(array $users, IEmailTemplate $emailTemplate)
    {
        $notificationResultsStrategy = $this->obtainNotificationResultStrategy();
        /** @var User $user */
        foreach ($users as $user) {
            $hasNotification = $this->userNotificationsService->hasNotificationActive($notificationResultsStrategy, $user);
            if ($hasNotification->getValue() == 1) {
                $this->emailService->sendTransactionalEmail($user, $emailTemplate);
            }
        }
    }

    public function obtainDataForDraw($lotteryName, \DateTime $datetime = null)
    {
        if ($datetime == null) {
            $datetime = new DateTime();
        }

        $draw = $this->getNextDateDrawByLottery($lotteryName, $datetime);
        $date_time_util = new DateTimeUtil();

        if ($date_time_util->checkTimeForClosePlay($draw, $datetime)) {
            $playDates = $this->getRecurrentDrawDates($lotteryName, 12, $datetime->modify('+1 day'));
            $draw = $this->getNextDateDrawByLottery($lotteryName, $datetime->modify('+1 day'));
        } else {
            $playDates = $this->getRecurrentDrawDates($lotteryName, 12, $datetime);
            $draw = $this->getNextDateDrawByLottery($lotteryName, $datetime);
        }

        $dayOfWeek = $date_time_util->getDayOfWeek($draw);

        return [
            'drawDate' => $draw->format('l j M G:i'),
            'playDates' => $playDates,
            'dayOfWeek' => $dayOfWeek,
        ];
    }

    /**
     * @param $lotteryName
     * @param \DateTime $today
     * @param $method
     * @return ActionResult
     */
    private function getBreakDown($lotteryName, \DateTime $today, $method)
    {
        /** @var Lottery $lottery */
        $lottery = $this->getLotteryByName($lotteryName);
        if (null !== $lottery) {
            $emBreakDownData = $this->lotteryDrawRepository->$method($lottery, $today);
            if (null !== $emBreakDownData) {
                return new ActionResult(true, $emBreakDownData);
            } else {
                return new ActionResult(false);
            }
        } else {
            return new ActionResult(false);
        }
    }

    /**
     * @param $lotteryName
     * @return Lottery
     */
    private function getLotteryByName($lotteryName)
    {
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        return $lottery;
    }

    /**
     * @return array
     */
    private function getAllLotteries()
    {
        $lotteries = $this->lotteryRepository->findAll();
        return $lotteries;
    }

    /**
     * @param $lotteryName
     * @param \DateTime $now
     * @return array
     */
    private function getLotteryAndNowDate($lotteryName, \DateTime $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }

        /** @var Lottery $lottery */
        $lottery = $this->getLotteryByName($lotteryName);
        return array($now, $lottery);
    }

    private function getAllLotteriesAndNowDate(\DateTime $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }

        /** @var Lottery $lottery */
        $lotteries = $this->getAllLotteries();
        return array($now, $lotteries);
    }

    /**
     * @return UserNotificationResultsStrategy
     */
    private function obtainNotificationResultStrategy()
    {
        $notificationResultsStrategy = new UserNotificationResultsStrategy($this->userService);
        return $notificationResultsStrategy;
    }

    private function sendEmailPurchase(User $user, PlayConfig $playConfig)
    {
        $emailBaseTemplate = new EmailTemplate();
        $emailTemplate = new PurchaseConfirmationEmailTemplate($emailBaseTemplate, new JackpotDataEmailTemplateStrategy($this));
        if ($playConfig->getFrequency() >= 4) {
            $emailTemplate = new PurchaseSubscriptionConfirmationEmailTemplate($emailBaseTemplate, new JackpotDataEmailTemplateStrategy($this));
            $emailTemplate->setDraws($playConfig->getFrequency());
            $emailTemplate->setStartingDate($playConfig->getStartDrawDate()->format('d-m-Y'));
        }
        $emailTemplate->setLine([$playConfig]);
        $emailTemplate->setUser($user);

        $this->emailService->sendTransactionalEmail($user, $emailTemplate);
    }

    private function sendPowerBallEmailPurchase(User $user, PlayConfig $playConfig)
    {
        $emailBaseTemplate = new EmailTemplate();
        $emailTemplate = new PowerBallPurchaseConfirmationEmailTemplate($emailBaseTemplate, new JackpotDataEmailTemplateStrategy($this));
        if ($playConfig->getFrequency() >= 4) {
            $emailTemplate = new PowerballPurchaseSubscriptionConfirmationEmailTemplate($emailBaseTemplate, new JackpotDataEmailTemplateStrategy($this));
            $emailTemplate->setDraws($playConfig->getFrequency());
            $emailTemplate->setStartingDate($playConfig->getStartDrawDate()->format('d-m-Y'));
        }
        $emailTemplate->setLine($playConfig);
        $emailTemplate->setUser($user);

        $this->emailService->sendTransactionalEmail($user, $emailTemplate);
    }

}
