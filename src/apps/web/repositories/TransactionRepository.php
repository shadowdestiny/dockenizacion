<?php


namespace EuroMillions\web\repositories;


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

}