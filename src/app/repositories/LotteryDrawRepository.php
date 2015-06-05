<?php
namespace EuroMillions\repositories;

use Doctrine\ORM\EntityRepository;

class LotteryDrawRepository extends EntityRepository
{
    public function getLastJackpot($lotteryName, $date = null)
    {
        if(!$date) {
            $date = date("Y-m-d");
        }
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT ld.jackpot'
                .' FROM '.$this->getEntityName().' ld JOIN ld.lottery l'
                .' WHERE l.name = :lottery_name AND ld.draw_date < :date'
                .' ORDER BY ld.draw_date DESC')
            ->setMaxResults(1)
            ->setParameters(['lottery_name' => $lotteryName, 'date' => $date])
            ->getResult();
        return $result[0]['jackpot'];
    }

    public function getNextJackpot($lotteryName, $date = null)
    {
        if(!$date) {
            $date = date("Y-m-d");
        }
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT ld.jackpot'
                .' FROM '.$this->getEntityName().' ld JOIN ld.lottery l'
                .' WHERE l.name = :lottery_name AND ld.draw_date >= :date'
                .' ORDER BY ld.draw_date DESC')
            ->setMaxResults(1)
            ->setParameters(['lottery_name' => $lotteryName, 'date' => $date])
            ->getResult();
        return $result[0]['jackpot'];
    }
}