<?php
namespace EuroMillions\repositories;

use Doctrine\ORM\EntityRepository;
use EuroMillions\entities\EuroMillionsDraw;
use EuroMillions\entities\Lottery;

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

    public function getNextJackpot($lotteryName, \DateTime $date = null)
    {
        if(!$date) {
            $date = new \DateTime();
        }
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT ld.jackpot'
                .' FROM '.$this->getEntityName().' ld JOIN ld.lottery l'
                .' WHERE l.name = :lottery_name AND ld.draw_date > :date'
                .'  OR (ld.draw_date = :date AND l.draw_time > :time)'
                .' ORDER BY ld.draw_date ASC')
            ->setMaxResults(1)
            ->setParameters(['lottery_name' => $lotteryName, 'date' => $date->format("Y-m-d"), 'time' => $date->format("H:i:s")])
            ->getResult();
        return $result[0]['jackpot'];
    }

    public function getLastResult(Lottery $lottery, \DateTime $date = null)
    {
        if (!$date) {
            $date = new \DateTime();
        }
        $draw_date = $lottery->getLastDrawDate($date);
        /** @var EuroMillionsDraw[] $result */
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT ld'
                . ' FROM ' . $this->getEntityName() . ' ld JOIN ld.lottery l'
                . ' WHERE l.name = :lottery_name AND ld.draw_date = :date')
            ->setMaxResults(1)
            ->setParameters(['lottery_name' => $lottery->getName(), 'date' => $draw_date->format("Y-m-d")])
            ->getResult();
        return $result[0]->getResult();
    }
}