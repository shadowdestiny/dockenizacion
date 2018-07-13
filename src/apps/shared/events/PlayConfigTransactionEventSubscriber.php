<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 26/06/18
 * Time: 11:08
 */

namespace EuroMillions\shared\events;


use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use EuroMillions\web\entities\AutomaticPurchaseTransaction;
use EuroMillions\web\entities\DepositTransaction;
use EuroMillions\web\entities\PlayConfigTransaction;
use EuroMillions\web\entities\SubscriptionPurchaseTransaction;
use EuroMillions\web\entities\TicketPurchaseTransaction;
use EuroMillions\web\entities\Transaction;


class PlayConfigTransactionEventSubscriber implements EventSubscriber
{

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::postPersist
        );
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getObjectManager();
        try
        {
            $playConfigRepository = $entityManager->getRepository('EuroMillions\web\entities\PlayConfig');
            $transactionRepository = $entityManager->getRepository('EuroMillions\web\entities\Transaction');
            // perhaps you only want to act on some "Product" entity
            if ($entity instanceof TicketPurchaseTransaction) {
                $transaction = $transactionRepository->findOneBy(['transactionID' => $entity->getTransactionID()]);
                foreach ($entity->getPlayConfigs() as $playConfigId)
                {
                    $this->insert($playConfigRepository,
                                  $playConfigId,
                                  $entity,
                                  $entityManager
                    );
                    if($transaction instanceof SubscriptionPurchaseTransaction)
                    {
                        $this->insert($playConfigRepository,
                                      $playConfigId,
                                      $transaction,
                                      $entityManager
                        );
                    }
                }
            }
            if( $entity instanceof AutomaticPurchaseTransaction) {
                    $this->insert($playConfigRepository,
                                  $entity->getPlayConfigs(),
                                  $entity,
                                  $entityManager);
            }


        } catch (\Exception $e)
        {

        }
    }

    /**
     * @param $playConfigRepository
     * @param $playConfigId
     * @param $entity
     * @param $entityManager
     * @return array
     */
    public function insert($playConfigRepository, $playConfigId, $entity, $entityManager)
    {
        $playConfig = $playConfigRepository->find($playConfigId);
        $playConfigTransaction = new PlayConfigTransaction();
        $playConfigTransaction->setPlayConfig($playConfig);
        $playConfigTransaction->setTransaction($entity);
        $entityManager->persist($playConfigTransaction);
        $entityManager->flush();
    }
}