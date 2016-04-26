<?php


namespace EuroMillions\admin\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\admin\vo\dto\DrawDTO;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\repositories\LotteryDrawRepository;
use EuroMillions\web\repositories\LotteryRepository;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use Money\Money;

class MaintenanceDrawService
{

    private $entityManager;

    /** @var  LotteryDrawRepository */
    protected $lotteryDrawRepository;
    /** @var  LotteryRepository */
    protected $lotteryRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->lotteryDrawRepository = $this->entityManager->getRepository('EuroMillions\web\entities\EuroMillionsDraw');
        $this->lotteryRepository = $this->entityManager->getRepository('EuroMillions\web\entities\Lottery');
    }

    public function updateLastResult(array $regular_numbers, array $lucky_numbers, Money $jackpot_value, $id_draw)
    {
        /** @var EuroMillionsDraw $lottery_draw */
        $lottery_draw = $this->lotteryDrawRepository->findOneBy(['id' => $id_draw]);
        if (null !== $lottery_draw) {
            try {
                $lottery_draw->setJackpot($jackpot_value);
                if(count($regular_numbers) > 0 && count($lucky_numbers) > 0) {
                    $lottery_draw->createResult($regular_numbers, $lucky_numbers);
                } else {
                    $lottery_draw->createResult([],[]);
                }
                $this->entityManager->flush();
                return new ActionResult(true);
            } catch (\Exception $e) {
                return new ActionResult(false);
            }
        } else {
            return new ActionResult(false);
        }
    }

    public function getAllDrawsByLottery($lotteryName)
    {
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        /** @var EuroMillionsDraw $draw */
        $draw = $this->lotteryDrawRepository->findBy(['lottery' => $lottery]);
        if (null !== $draw) {
            return new ActionResult(true, $draw);
        } else {
            return new ActionResult(false, 'Error fetching');
        }
    }

    public function getDrawById($id)
    {
        /** @var EuroMillionsDraw $draw */
        $draw = $this->lotteryDrawRepository->findOneBy(['id' => $id]);
        if (null !== $draw) {
            /** @var DrawDTO $draw_dto */
            $draw_dto = new DrawDTO($draw);
            $draw_dto->setRegularNumbers(implode(',',$draw_dto->getRegularNumbers()));
            $draw_dto->setLuckyNumbers(implode(',',$draw_dto->getLuckyNumbers()));
            $draw_dto->checkResultAndCleanValuesIfAreEmpty();
            $draw_dto->sanetizeWinnersBreakDown();
            return new ActionResult(true, $draw_dto);
        } else {
            return new ActionResult(false, 'Error fetching');
        }
    }

    public function storeBreakDown( array $breakdown, $idDraw)
    {
        try {
            /** @var EuroMillionsDraw $lottery_draw */
            $lottery_draw = $this->lotteryDrawRepository->findOneBy(['id' => $idDraw]);
            $lottery_draw->createBreakDown($breakdown);
            $this->entityManager->persist($lottery_draw);
            $this->entityManager->flush();
        } catch ( \Exception $e ) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getDrawByDate(\DateTime $date)
    {
        $draw = $this->lotteryDrawRepository->findOneBy(['draw_date' => $date]);
        if (null !== $draw) {
            return new ActionResult(true, $draw);
        } else {
            return new ActionResult(false);
        }
    }

}