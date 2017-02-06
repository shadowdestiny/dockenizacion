<?php


namespace EuroMillions\web\repositories;


use Doctrine\ORM\Query\ResultSetMapping;
use EuroMillions\web\entities\Transaction;

class TransactionRepository extends RepositoryBase
{

    public function getTransactionsByType( Transaction $transaction )
    {
        $class = get_class($transaction);
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT t'
                . ' FROM \EuroMillions\web\entities\Transaction t '
                . ' where t INSTANCE OF '.$class )
            ->getResult();
        return count($result) > 0 ? $result : [];

    }

    public function getNextId()
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT max(t.id)'
                .' FROM \EuroMillions\web\entities\Transaction t')
            ->getResult();

        return (int) ($result[0][1]) +1 ;
    }
}