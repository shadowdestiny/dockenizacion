<?php


namespace EuroMillions\web\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\entities\Transaction;


class TransactionService
{

    protected $entityManager;
    protected $transactionRepository;

    public function __construct( EntityManager $entityManager )
    {
        $this->entityManager = $entityManager;
    }


    public function storeTransaction( $transactionType , array $data, $userId, \DateTime $now = null)
    {
        if( null == $now ) {
            $now = new \DateTime();
        }
        list($partOne,$partTwo) = explode('_',$transactionType);
        $class = 'EuroMillions\web\components\transaction\\'.ucfirst($partOne).ucfirst($partTwo).'Generator';
        try {
            /** @var Transaction $entity */
            $entity = $class::build($data);
            $entity->setUser($userId);
            $entity->setDate($now);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } catch ( \Exception $e ) {
            return new ActionResult(false);
        }
        return new ActionResult(true, $entity);
    }
}