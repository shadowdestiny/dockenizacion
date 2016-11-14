<?php

namespace EuroMillions\web\repositories;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\interfaces\IReports;
use Doctrine\ORM\Query\ResultSetMapping;

class ReportsRepository implements IReports
{

    private $entityManager;
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function getMonthlySales(\DateTime $date)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('month','month');
        $rsm->addScalarResult('total_bets','total_bets');
        $rsm->addScalarResult('gross_sales','gross_sales');
        $rsm->addScalarResult('gross_margin','gross_margin');
        $rsm->addScalarResult('winnings','winnings');
        $result = $this->getEntityManager()
            ->createQuery(
                "SELECT MONTHNAME(d.draw_date) as month,count(b.id) as total_bets, count(b.id) * 3.00 as gross_sales, count(1) * 0.50 as gross_margin,
                (select SUM(t.wallet_after_winnings_amount)
                FROM transactions t
                WHERE MONTHNAME(t.date)=MONTHNAME(d.draw_date)
                    and entity_type='winnings_received') as winnings
                FROM bets b
                JOIN euromillions_draws d on d.id=b.euromillions_draw_id
                JOIN play_configs p on p.id=b.playConfig_id
                GROUP BY MONTH(d.draw_date", $rsm)
            ->getResult();
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