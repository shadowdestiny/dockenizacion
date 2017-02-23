<?php


namespace EuroMillions\web\repositories;


use Doctrine\ORM\Query\ResultSetMapping;
use EuroMillions\web\entities\Transaction;
use EuroMillions\web\entities\User;

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

    public function getAutomaticAndTicketPurchaseByUserId($userId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('date','date');
        $rsm->addScalarResult('entity_type','entity_type');
        $rsm->addScalarResult('data','data');
        $rsm->addScalarResult('balance','balance');
        $rsm->addScalarResult('automaticMovement','automaticMovement');

        return $this->getEntityManager()
            ->createNativeQuery(
                'SELECT date, entity_type, data, (wallet_after_uploaded_amount + wallet_after_winnings_amount) as balance, (wallet_before_subscription_amount - wallet_after_subscription_amount) as automaticMovement
                FROM transactions
                WHERE entity_type in ("ticket_purchase", "automatic_purchase") and user_id = "' . $userId . '"
                order by date desc'
                , $rsm)->getResult();
    }

    public function getDepositsByUserId($userId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('date','date');
        $rsm->addScalarResult('data','data');
        $rsm->addScalarResult('movement','movement');
        $rsm->addScalarResult('balance','balance');

        return $this->getEntityManager()
            ->createNativeQuery(
                'SELECT date, (wallet_after_uploaded_amount - wallet_before_uploaded_amount) as movement, (wallet_after_uploaded_amount + wallet_after_winnings_amount) as balance
                FROM transactions
                WHERE entity_type = "deposit" and user_id = "' . $userId . '"
                order by date desc'
                , $rsm)->getResult();
    }

    public function getWithdrawalsByUserId($userId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('date','date');
        $rsm->addScalarResult('data','data');

        return $this->getEntityManager()
            ->createNativeQuery(
                'SELECT date, data
                FROM transactions
                WHERE entity_type = "winnings_withdraw" and user_id = "' . $userId . '"
                order by date desc'
                , $rsm)->getResult();
    }
}