<?php


namespace EuroMillions\web\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\shared\vo\Wallet;
use EuroMillions\web\entities\Transaction;
use EuroMillions\web\entities\User;


class TransactionService
{

    protected $entityManager;
    protected $transactionRepository;

    public function __construct( EntityManager $entityManager )
    {
        $this->entityManager = $entityManager;
    }


    public function storeTransaction( $transactionType ,
                                      array $data,
                                      User $user,
                                      Wallet $walletBefore,
                                      Wallet $walletAfter,
                                      \DateTime $now = null)
    {
        if( null == $now ) {
            $now = new \DateTime();
        }
        list($partOne,$partTwo) = explode('_',$transactionType);
        $class = 'EuroMillions\web\components\transaction\\'.ucfirst($partOne).ucfirst($partTwo).'Generator';
        try {
            /** @var Transaction $entity */
            $entity = $class::build($data);
            $entity->setUser($user);
            $entity->setDate($now);
            $entity->setWalletBefore($walletBefore);
            $entity->setWalletAfter($walletAfter);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } catch ( \Exception $e ) {
            return new ActionResult(false);
        }
        return new ActionResult(true, $entity);
    }
}