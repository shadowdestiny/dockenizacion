<?php
namespace EuroMillions\web\services;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use EuroMillions\shared\config\Namespaces;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\User;
use EuroMillions\web\exceptions\BadSiteConfiguration;
use EuroMillions\web\exceptions\DataMissingException;
use EuroMillions\web\interfaces\IJackpot;
use EuroMillions\web\repositories\LotteryDrawRepository;
use EuroMillions\web\repositories\LotteryRepository;
use EuroMillions\web\services\user_notifications_strategies\UserNotificationAutoPlayNoFunds;
use EuroMillions\web\vo\enum\TransactionType;
use EuroMillions\web\vo\EuroMillionsLine;

class LotteryService
{
    /** @var EntityManager */
    private $entityManager;
    /** @var LotteryDrawRepository */
    private $lotteryDrawRepository;
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
        $lottery = $this->lotteryRepository->getLotteryByName($lotteryName);
        $jackpot_object = 'EuroMillions\web\vo\\'.$lotteryName.'Jackpot';
        try {
            $next_jackpot = $this->lotteryDrawRepository->getNextJackpot($lottery);
            return $jackpot_object::fromAmountIncludingDecimals($next_jackpot->getAmount());
        } catch (DataMissingException $e) {
            try {
                $next_jackpot = $this->lotteriesDataService->updateNextDrawJackpot($lotteryName);
                return $jackpot_object::fromAmountIncludingDecimals($next_jackpot->getAmount());
            } catch ( DataMissingException $e ) {
                return $jackpot_object::fromAmountIncludingDecimals(null);
            }
        }
    }

    public function getLastResult($lotteryName)
    {
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->getLotteryByName($lotteryName);
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
        if (!$now) {
            $now = new \DateTime();
        }
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        $next_draw_date = $lottery->getNextDrawDate($now);
        return $now->diff($next_draw_date);
    }

    public function getNextDateDrawByLottery($lotteryName, \DateTime $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        return $lottery->getNextDrawDate($now);
    }

    public function getRecurrentDrawDates($lotteryName, $iteration = 5, \DateTime $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        return $lottery->getRecurringIntervalDrawDate($iteration, $now);
    }

    public function getNextDrawByLottery($lotteryName, \DateTime $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        if (null !== $lottery) {
            $euroMillionsDraw = $this->lotteryDrawRepository->getNextDraw($lottery, $lottery->getNextDrawDate($now));
            if (null !== $euroMillionsDraw) {
                return new ActionResult(true, $euroMillionsDraw);
            } else {
                return new ActionResult(false);
            }
        }
        return new ActionResult(false);
    }

    public function getLotteryConfigByName($lotteryName)
    {
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        if (null !== $lottery) {
           return $lottery;
        } else {
            return new ActionResult(false, 'Lottery unknown');
        }
    }

    public function lastBreakDown($lotteryName, \DateTime $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
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
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        if (null !== $lottery) {
            return $lottery->getSingleBetPrice();
        } else {
            throw new BadSiteConfiguration('The lottery ' . $lotteryName . ' is not properly configured');
        }
    }


    public function getLastJackpot($lotteryName)
    {
        return $this->lotteryDrawRepository->getLastJackpot($lotteryName);
    }

    public function getBreakDownDrawByDate($lotteryName)
    {
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        if (null !== $lottery) {
            $emBreakDownData = $this->lotteryDrawRepository->getBreakDownData($lottery);
            if (null !== $emBreakDownData) {
                return new ActionResult(true, $emBreakDownData);
            } else {
                return new ActionResult(false);
            }
        } else {
            return new ActionResult(false);
        }
    }

    public function placeBetForNextDraw(Lottery $lottery, \DateTime $dateNextDraw = null)
    {
        $users = $this->userService->getUsersWithPlayConfigsForNextDraw($lottery);
        if( null != $users ) {
            /** @var User $user */
            $nextDrawDate = $lottery->getNextDrawDate($dateNextDraw);
            foreach( $users as $user ) {
                /** @var ArrayCollection $playconfigsFiltered */
                $playconfigsFiltered = $user->getPlayConfigsFilteredForNextDraw($nextDrawDate);
                if( count($playconfigsFiltered) > 0 ) {
                    //EMTD get playconfigsFiltered as array
                    $playconfigsFilteredToArray = $playconfigsFiltered->toArray();
                    $price = $this->userService->getPriceForNextDraw($lottery, $playconfigsFilteredToArray);
                    if( $price->getAmount() < $user->getBalance()->getAmount() ) {
                        $euroMillionsDraw = $this->lotteryDrawRepository->getNextDraw($lottery, $lottery->getNextDrawDate($dateNextDraw));
                        foreach( $playconfigsFilteredToArray as $playConfig ) {
                            $result = $this->betService->validation($playConfig, $euroMillionsDraw, $nextDrawDate);
                            if($result->success()) {
                                $dataTransaction = [
                                    'lottery_id' => 1,
                                    'numBets' => $playConfig->getId(),
                                ];
                                $this->walletService->payWithWallet($user,$playConfig,TransactionType::AUTOMATIC_PURCHASE,$dataTransaction);
                            }
                        }
                    } else {
                        $userNotificationAutoPlayNoFunds = new UserNotificationAutoPlayNoFunds($this->userService);
                        $hasNotification = $this->userNotificationsService->hasNotificationActive($userNotificationAutoPlayNoFunds, $user);
                        if($hasNotification) {
                            $this->emailService->sendLowBalanceEmail($user);
                        }
                    }
                }
            }
        }
    }

    public function getLotteriesOrderedByNextDrawDate( \DateTime $now = null )
    {
        if(!$now) {
            $now = new \DateTime();
        }
        $new_array = [];
        $lotteries = $this->lotteryRepository->findAll();
        /** @var Lottery $lottery */
        foreach($lotteries as $lottery) {
            $next_draw = $lottery->getNextDrawDate($now);
            $new_array[$next_draw->format('Y-m-d H:i:s')] = $lottery;
        }
        ksort($new_array);
        return count($new_array) > 0 ? $new_array : [];
    }


}