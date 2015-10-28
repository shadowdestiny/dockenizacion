<?php
namespace EuroMillions\web\repositories;

use Doctrine\ORM\EntityRepository;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Lottery;

class LotteryDrawRepository extends EntityRepository
{
    public function getLastJackpot($lotteryName, $date = null)
    {
        if(!$date) {
            $date = date("Y-m-d");
        }
        /** @var EuroMillionsDraw[] $result */
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT ld'
                .' FROM '.$this->getEntityName().' ld JOIN ld.lottery l'
                .' WHERE l.name = :lottery_name AND ld.draw_date < :date'
                .' ORDER BY ld.draw_date DESC')
            ->setMaxResults(1)
            ->setParameters(['lottery_name' => $lotteryName, 'date' => $date])
            ->useResultCache(true)
            ->getResult();
        return $result[0]->getJackpot();
    }

    public function getNextJackpot($lotteryName, \DateTime $date = null)
    {
        if(!$date) {
            $date = new \DateTime();
        }
        /** @var EuroMillionsDraw[] $result */
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT ld'
                .' FROM '.$this->getEntityName().' ld JOIN ld.lottery l'
                .' WHERE l.name = :lottery_name AND ld.draw_date > :date'
                .'  OR (ld.draw_date = :date AND l.draw_time > :time)'
                .' ORDER BY ld.draw_date ASC')
            ->setMaxResults(1)
            ->setParameters(['lottery_name' => $lotteryName, 'date' => $date->format("Y-m-d"), 'time' => $date->format("H:i")])
            ->useResultCache(true)
            ->getResult();
        return $result[0]->getJackpot();
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
            ->useResultCache(true)
            ->getResult();
        return $result[0]->getResult();
    }

    public function getNextDraw(Lottery $lottery, \DateTime $date = null)
    {
        if (!$date) {
            $date = new \DateTime();
        }

        /** @var EuroMillionsDraw $result */
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT ld'
                . ' FROM ' . $this->getEntityName() . ' ld JOIN ld.lottery l'
                . ' WHERE l.name = :lottery_name AND ld.draw_date = :date')
            ->setParameters(['lottery_name' => $lottery->getName(), 'date' => $date->format("Y-m-d")])
            ->useResultCache(true)
            ->getResult();
        return (!empty($result)) ? $result[0] : [];

    }

    public function getBreakDownData(Lottery $lottery, \DateTime $date = null)
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
            ->useResultCache(true)
            ->getResult();

        return $result[0]->getBreakDown();
    }

}