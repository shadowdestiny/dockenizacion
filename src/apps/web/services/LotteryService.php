<?php

namespace EuroMillions\web\services;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\UnexpectedResultException;
use EuroMillions\shared\config\Namespaces;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\IEmailTemplate;
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
use EuroMillions\web\services\user_notifications_strategies\UserNotificationAutoPlayNoFunds;
use EuroMillions\web\services\user_notifications_strategies\UserNotificationResultsStrategy;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use EuroMillions\web\vo\dto\EuroMillionsDrawDTO;
use EuroMillions\web\vo\enum\TransactionType;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\EuroMillionsJackpot;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\Raffle;

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
                $next_jackpot = $this->lotteriesDataService->updateNextDrawJackpot($lotteryName);
                return $jackpot_object::fromAmountIncludingDecimals($next_jackpot->getAmount());
            } catch (DataMissingException $e) {
                return $jackpot_object::fromAmountIncludingDecimals(null);
            }
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
            $lottery_result = $this->lotteriesDataService->updateLastDrawResult($lotteryName);
        }
        $result['regular_numbers'] = explode(',', $lottery_result->getRegularNumbers());
        $result['lucky_numbers'] = explode(',', $lottery_result->getLuckyNumbers());
        return $result;
    }

    public function getTimeToNextDraw($lotteryName, \DateTime $now = null)
    {
        list($now, $lottery) = $this->getLotteryAndNowDate($lotteryName, $now);
        $next_draw_date = $lottery->getNextDrawDate($now);
        return $now->diff($next_draw_date);
    }

    public function getNextDateDrawByLottery($lotteryName, \DateTime $now = null)
    {
        list($now, $lottery) = $this->getLotteryAndNowDate($lotteryName, $now);
        return $lottery->getNextDrawDate($now);
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
                                'lottery_id' => 1,
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

/** Old PlaceBets */
//    public function placeBetForNextDraw(Lottery $lottery, \DateTime $dateNextDraw = null)
//    {
//        $users = $this->userService->getUsersWithPlayConfigsForNextDraw();
//
//        if (null != $users) {
//            /** @var User $user */
//            $nextDrawDate = $lottery->getNextDrawDate($dateNextDraw);
//            foreach ($users as $user) {
//                /** @var ArrayCollection $playconfigsFiltered */
//                $playconfigsFiltered = $user->getPlayConfigsFilteredForNextDraw($nextDrawDate);
//                if (count($playconfigsFiltered) > 0) {
//                    //EMTD get playconfigsFiltered as array
//                    $playconfigsFilteredToArray = $playconfigsFiltered->toArray();
//                    $price = $this->lotteriesDataService->getPriceForNextDraw($playconfigsFilteredToArray);
//                    if ($user->getWallet()->getSubscription()->getAmount() >= $price->getAmount()) {
//                        /** @var EuroMillionsDraw $euroMillionsDraw */
//                        $euroMillionsDraw = $this->lotteryDrawRepository->getNextDraw($lottery, $lottery->getNextDrawDate($dateNextDraw));
//
//                        /** @var PlayConfig $playConfig */
//                        foreach ($playconfigsFilteredToArray as $playConfig) {
//                            if (empty($this->betService->obtainBetsWithAPlayConfigAndAEuromillionsDraw($playConfig, $euroMillionsDraw))) {
//                                $result = $this->betService->validation($playConfig, $euroMillionsDraw, $nextDrawDate);
//                                if ($result->success()) {
//                                    $walletBefore = $user->getWallet();
//                                    $this->walletService->payWithSubscription($user, $playConfig);
//                                    $dataTransaction = [
//                                        'lottery_id' => 1,
//                                        'numBets' => 1,
//                                        'walletBefore' => $walletBefore,
//                                        'amountWithCreditCard' => 0,
//                                        'playConfigs' => $playConfig->getId(),
//                                        'discount' => $playConfig->getDiscount(),
//                                    ];
//                                    $this->walletService->purchaseTransactionGrouped($user, TransactionType::AUTOMATIC_PURCHASE, $dataTransaction);
//                                    $this->sendEmailPurchase($user, $playConfig);
//                                }
//                            }
//                        }
//                    } else {
//                        $userNotificationAutoPlayNoFunds = new UserNotificationAutoPlayNoFunds($this->userService);
//                        $hasNotification = $this->userNotificationsService->hasNotificationActive($userNotificationAutoPlayNoFunds, $user);
//                        if ($hasNotification) {
//                            $this->emailService->sendLowBalanceEmail($user);
//                        }
//                    }
//                }
//            }
//        }
//    }

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

}