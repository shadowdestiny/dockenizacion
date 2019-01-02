<?php


namespace EuroMillions\web\repositories;


use Doctrine\ORM\Query\ResultSetMapping;
use EuroMillions\web\entities\Transaction;
use EuroMillions\web\entities\User;
use Doctrine\ORM\Tools\Pagination\Paginator;

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
                'SELECT p.lottery_id, date, entity_type, data, (wallet_after_uploaded_amount + wallet_after_winnings_amount) as balance, (wallet_before_subscription_amount - wallet_after_subscription_amount) as automaticMovement
                FROM transactions t
                INNER JOIN playconfig_transaction pt on t.id = pt.transactionID
                INNER JOIN play_configs p on p.id = pt.playConfig_id
                WHERE entity_type in ("ticket_purchase", "automatic_purchase") and t.user_id = "' . $userId . '"
                group by t.id
                order by date desc'
                , $rsm)->getResult();
    }

    public function getSubscriptionIDByPlayConfig($playConfigID)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                ' SELECT t.id'
                . ' FROM EuroMillions\web\entities\SubscriptionPurchaseTransaction t'
                . ' WHERE t.playConfigId= :playConfigId')
            ->setParameters(['playConfigId' => $playConfigID]
            )
            ->getResult()[0];
        return $result['id'];
    }

    public function getDepositsByUserId($userId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('date','date');
        $rsm->addScalarResult('entity_type','entity_type');
        $rsm->addScalarResult('movement','movement');
        $rsm->addScalarResult('balance','balance');

        return $this->getEntityManager()
            ->createNativeQuery(
                'SELECT t.date, t.entity_type, (if(t.entity_type = "deposit", (t.wallet_after_uploaded_amount - t.wallet_before_uploaded_amount),(select (d.wallet_after_subscription_amount - d.wallet_before_subscription_amount) from transactions as d where t.transactionID=d.transactionID and d.entity_type="ticket_purchase"))) as movement, (if(t.entity_type = "deposit",(t.wallet_after_uploaded_amount + t.wallet_after_winnings_amount), (select (d.wallet_after_uploaded_amount + d.wallet_after_winnings_amount) from transactions as d where t.transactionID=d.transactionID and d.entity_type="ticket_purchase"))) as balance
                FROM transactions as t
                WHERE t.entity_type in ("deposit", "subscription_purchase") and t.user_id = "' . $userId . '"
                order by t.date desc'
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

    public function getAwardedByDate($date)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('date','date');

        return $this->getEntityManager()
            ->createNativeQuery(
                'SELECT *
                FROM transactions
                WHERE entity_type = "winnings_received" AND DATE_FORMAT(date,\'%Y-%m-%d\') = "' . $date . '" 
                order by date desc'
                , $rsm)->getResult();
    }

    public function getSubscriptionBalanceByLottery($lotteryId, $userId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('total_subscription','total_subscription');
        $result = $this->getEntityManager()
            ->createNativeQuery(
                                    'select SUM(t.wallet_after_subscription_amount-t.wallet_before_subscription_amount)
                           -
                           (select
                                SUM(tr.wallet_before_subscription_amount-tr.wallet_after_subscription_amount) from transactions tr
                              where
                                exists( select pt.id from playconfig_transaction pt
                                  inner join play_configs pl on pl.id=pt.playConfig_id and pl.lottery_id="'.$lotteryId.'" where pt.transactionID=tr.id)
                                and
                                tr.entity_type IN ("ticket_purchase","automatic_purchase")
                                and tr.user_id="'.$userId.'"
                                ) as total_subscription
                    from transactions t
                    where
                      exists(
                          select pt.id from playconfig_transaction pt
                            inner join play_configs pl on pl.id=pt.playConfig_id and pl.lottery_id="'.$lotteryId.'" where pt.transactionID=t.id
                      )
                    and t.entity_type IN("subscription_purchase")
                    and t.user_id="'.$userId.'"',
            $rsm)->getResult();

        return $result[0]['total_subscription'];



    }


    public function getLastTransactionIDAsPurchaseType($userId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('transactionID', 'transactionID');

        return $this->getEntityManager()
            ->createNativeQuery(
                'select transactionID from transactions
                      where transactionID is not null and user_id="'.$userId .'" 
                      group by transactionID order by date DESC limit 1;'
                , $rsm)->getResult();
    }

    public function getTransactionsDTOByUser($userId, $page = null)
    {

        if(is_null($page))
        {
            return $this->findBy(['user' => $userId], ['id' => 'DESC']);
        }
        else
        {
            $dql = "SELECT t FROM ".$this->getEntityName()." t Where t.user= :userid order by t.id DESC ";
            $query = $this->getEntityManager()
                ->createQuery($dql)
                ->setParameter('userid', $userId)
                ->setFirstResult(10 * ($page - 1)) // Offset
                ->setMaxResults(10); // Limit;

            return new Paginator($query);
        }
    }
}