<?php

namespace EuroMillions\web\repositories;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\interfaces\IReports;

class ReportsRepository implements IReports
{

    private $entityManager;
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function getMonthlySales(\DateTime $date)
    {
//        $result = $this->getEntityManager()
//            ->createQuery(
//                'SELECT MONTHNAME(d.draw_date) as month,count(b.id) as total_bets, count(b.id) * 3.00 as gross_sales, count(1) * 0.50 as gross_margin,
//                (select SUM(t.wallet_after_winnings_amount)
//                FROM \EuroMillions\web\entities\Transaction t
//                where MONTHNAME(t.date)=MONTHNAME(d.draw_date)
//                    and entity_type='winnings_received') as winnings
//                from bets b
//                join euromillions_draws d on d.id=b.euromillions_draw_id
//                join play_configs p on p.id=b.playConfig_id
//                group by MONTH(d.draw_date')
//            ->getResult();

        $result = [];
        return $result;
    }

    public function getSalesDraw()
    {
        $result = $this->entityManager
            ->createQuery(
                'SELECT \'EM\',e.id, e.draw_date, IF(e.draw_date < now(),\'Finished\',\'Open\') as draw_status, count(b.id), count(b.id) * 3.00, count(b.id) * 0.50
                  FROM euromillions_draws e
                  JOIN bets b on b.euromillions_draw_id=e.id
                  JOIN log_validation_api l on l.bet_id=b.id
                  GROUP BY e.draw_date;')
            ->getResult();

        return $result;
    }
}