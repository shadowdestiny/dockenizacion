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
use EuroMillions\web\repositories\LotteryDrawRepository;
use EuroMillions\web\repositories\LotteryRepository;
use EuroMillions\web\vo\EuroMillionsLine;
use Money\Money;

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

    public function __construct(EntityManager $entityManager,
                                LotteriesDataService $lotteriesDataService,
                                UserService $userService,
                                BetService $betService,
                                EmailService $emailService)
    {
        $this->entityManager = $entityManager;
        $this->lotteryDrawRepository = $this->entityManager->getRepository(Namespaces::ENTITIES_NS . 'EuroMillionsDraw');
        $this->lotteryRepository = $this->entityManager->getRepository(Namespaces::ENTITIES_NS . 'Lottery');
        $this->lotteriesDataService = $lotteriesDataService;
        $this->emailService = $emailService;
        $this->userService = $userService;
        $this->betService = $betService;
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
     * @return Money
     */
    public function getNextJackpot($lotteryName)
    {
        $lottery = $this->lotteryRepository->getLotteryByName($lotteryName);
        try {
            return $this->lotteryDrawRepository->getNextJackpot($lottery);
        } catch (DataMissingException $e) {
            return $this->lotteriesDataService->updateNextDrawJackpot($lotteryName);
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
            return new ActionResult(true, $lottery);
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
        //EMTD @rmrbest Why there's a $now parameter if it's not used?
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
            foreach( $users as $user ) {
                /** @var ArrayCollection $playconfigsFiltered */
                $nextDrawDate = $lottery->getNextDrawDate($dateNextDraw);
                $playconfigsFiltered = $user->getPlayConfigsFilteredForNextDraw($nextDrawDate);
                if( count($playconfigsFiltered) > 0 ) {
                    //EMTD get playconfigsFiltered as array
                    $playconfigsFilteredToArray = $playconfigsFiltered->toArray();
                    $price = $this->userService->getPriceForNextDraw($lottery, $playconfigsFilteredToArray);
                    if( $price->getAmount() < $user->getBalance()->getAmount() ) {
                        $euroMillionsDraw = $this->lotteryDrawRepository->getNextDraw($lottery, $lottery->getNextDrawDate($dateNextDraw));
                        foreach( $playconfigsFilteredToArray as $playConfig ) {
                            $this->betService->validation($playConfig, $euroMillionsDraw, $nextDrawDate);
                        }
                    } else {
                        $this->emailService->sendLowBalanceEmail($user);
                    }
                }
            }
        }
    }

    public function getLotteriesOrderedByNextDrawDate()
    {
        $lotteries = $this->lotteryRepository->findBy(['draw' => 'ASC']);
        return count($lotteries) > 0 ? $lotteries : [];
    }


}