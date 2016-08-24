<?php


namespace EuroMillions\web\components\transaction\detail;


use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\WinningsReceivedTransaction;
use EuroMillions\web\interfaces\ITransactionDetailStrategy;
use EuroMillions\web\repositories\BetRepository;
use EuroMillions\web\repositories\LotteryDrawRepository;
use EuroMillions\web\repositories\LotteryRepository;
use EuroMillions\web\vo\dto\WinningReceiveDetailDTO;

class WinningReceiveDetail implements ITransactionDetailStrategy
{

    protected $entityManager;
    /** @var  WinningsReceivedTransaction $transaction */
    protected $transaction;
    /** @var  BetRepository $betRepository */
    protected $betRepository;
    /** @var  LotteryDrawRepository $lotteryDrawRepository */
    protected $lotteryDrawRepository;
    /** @var  LotteryRepository $lotteryRepository */
    protected $lotteryRepository;


    public function __construct(EntityManager $entityManager, WinningsReceivedTransaction $transaction)
    {
        $this->entityManager = $entityManager;
        $this->transaction = $transaction;
        $this->betRepository = $entityManager->getRepository('EuroMillions\web\entities\Bet');
        $this->lotteryDrawRepository = $this->entityManager->getRepository('EuroMillions\web\entities\EuroMillionsDraw');
    }


    public function obtainDataForDetails()
    {
        $this->transaction->fromString();
        $bets = $this->betRepository->obtainWinnerBetById($this->transaction->getBetId());
        /** @var EuroMillionsDraw $draw */
        $draw = $this->lotteryDrawRepository->find(
            [
                'id' => $this->transaction->getDrawId()
            ]
        );
        $winningReceiveDetailDTOCollection = [];
        foreach($bets as $bet) {
            $winningDetailDTO = new WinningReceiveDetailDTO($draw,$bet[0]);
            $winningReceiveDetailDTOCollection[] = $winningDetailDTO;
        }
        return $winningReceiveDetailDTOCollection;

    }
}