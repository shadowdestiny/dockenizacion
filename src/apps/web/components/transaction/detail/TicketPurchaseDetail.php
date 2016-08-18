<?php


namespace EuroMillions\web\components\transaction\detail;


use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\TicketPurchaseTransaction;
use EuroMillions\web\interfaces\ITransactionDetailStrategy;
use EuroMillions\web\repositories\PlayConfigRepository;
use EuroMillions\web\vo\dto\TicketPurchaseDetailDTO;

class TicketPurchaseDetail implements ITransactionDetailStrategy
{
    protected $entityManager;
    protected $transaction;
    /** @var PlayConfigRepository $playConfigRepository  */
    protected $playConfigRepository;

    public function __construct(EntityManager $entityManager, TicketPurchaseTransaction $transaction)
    {
        $this->entityManager = $entityManager;
        $this->transaction = $transaction;
        $this->playConfigRepository = $entityManager->getRepository('EuroMillions\web\entities\PlayConfig');
    }


    public function obtainDataForDetails()
    {
        $ids = $this->transaction->getPlayConfigs();
        $playConfigCollection = $this->playConfigRepository->getPlayConfigsByCollectionIds($ids);
        $ticketPurchaseDetailDTOCollection = [];
        foreach($playConfigCollection as $playConfig) {
            $ticketPurchaseDetailDTOCollection[] = new TicketPurchaseDetailDTO($playConfig);
        }
        return $ticketPurchaseDetailDTOCollection;
    }
}