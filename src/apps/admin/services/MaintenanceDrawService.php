<?php


namespace EuroMillions\admin\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\admin\vo\ActionResult;
use EuroMillions\web\entities\EuroMillionsDraw;
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

}