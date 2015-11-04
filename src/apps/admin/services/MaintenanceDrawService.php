<?php


namespace EuroMillions\admin\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\admin\vo\ActionResult;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\repositories\LotteryDrawRepository;
use EuroMillions\web\repositories\LotteryRepository;

class MaintenanceDrawService
{

    private $entityManager;

    /** @var  LotteryDrawRepository */
    protected $lotteryDrawRepository;
    /** @var  LotteryRepository*/
    protected $lotteryRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->lotteryDrawRepository = $this->entityManager->getRepository('EuroMillions\web\entities\EuroMillionsDraw');
        $this->lotteryRepository = $this->entityManager->getRepository('EuroMillions\web\entities\Lottery');
    }

    public function updateLastResult(array $regular_numbers, array $lucky_numbers, $id_draw)
    {
        /** @var EuroMillionsDraw $lottery_draw */
        $lottery_draw = $this->lotteryDrawRepository->findOneBy(['id' => $id_draw]);
        if(!empty($lottery_draw)) {
            try{
                $lottery_draw->createResult($regular_numbers,$lucky_numbers);
                $this->entityManager->flush();
                return new ActionResult(true);
            }catch(\Exception $e) {
                return new ActionResult(false);
            }
        }else{
            return new ActionResult(false);
        }
    }

    public function getAllDrawsByLottery($lotteryName)
    {
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        /** @var EuroMillionsDraw $draw */
        $draw = $this->lotteryDrawRepository->findBy(['lottery' => $lottery]);
        if(!empty($draw)){
            return new ActionResult(true,$draw);
        }else{
            return new ActionResult(false,'Error fetching');
        }
    }

    public function getDrawById($id)
    {
        /** @var EuroMillionsDraw $draw */
        $draw = $this->lotteryDrawRepository->findOneBy(['id' => $id]);
        if(!empty($draw)){
            return new ActionResult(true,$draw);
        }else{
            return new ActionResult(false,'Error fetching');
        }
    }

}