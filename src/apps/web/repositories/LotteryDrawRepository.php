<?php
namespace EuroMillions\web\repositories;

use Doctrine\ORM\EntityRepository;
use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\exceptions\DataMissingException;

class LotteryDrawRepository extends EntityRepository
{
    public function getLastJackpot($lotteryName, $date = null)
    {
        if (!$date) {
            $date = date("Y-m-d");
        }
        /** @var EuroMillionsDraw[] $result */
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT ld'
                . ' FROM ' . $this->getEntityName() . ' ld JOIN ld.lottery l'
                . ' WHERE l.name = :lottery_name AND ld.draw_date < :date'
                . ' ORDER BY ld.draw_date DESC')
            ->setMaxResults(1)
            ->setParameters(['lottery_name' => $lotteryName, 'date' => $date])
            ->useResultCache(true, 3600)
            ->getResult();
        return $result[0]->getJackpot();
    }

    public function getLastRaffle($lotteryName, $date = null)
    {
        if (!$date) {
            $date = date("Y-m-d");
        }
        /** @var EuroMillionsDraw[] $result */
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT raffle_value'
                . ' FROM ' . $this->getEntityName() . ' ld JOIN ld.lottery l'
                . ' WHERE l.name = :lottery_name AND ld.draw_date < :date'
                . ' ORDER BY ld.draw_date DESC')
            ->setMaxResults(1)
            ->setParameters(['lottery_name' => $lotteryName, 'date' => $date])
            ->useResultCache(true)
            ->getResult();
        return $result[0]->getRaffle();
    }

    /**
     * @param Lottery $lottery
     * @param \DateTime|null $date
     * @return \Money\Money
     * @throws DataMissingException
     */
    public function getNextJackpot(Lottery $lottery, \DateTime $date = null)
    {

        if (!$date) {
            $date = new \DateTime();
        }
        $next_draw_date = $lottery->getNextDrawDate($date);
        /** @var EuroMillionsDraw[] $result */
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT ld'
                . ' FROM ' . $this->getEntityName() . ' ld JOIN ld.lottery l'
                . ' WHERE l.name = :lottery_name AND ld.draw_date = :date AND l.draw_time = :time'
                . ' ORDER BY ld.draw_date ASC')
            ->setMaxResults(1)
            ->setParameters(['lottery_name' => $lottery->getName(), 'date' => $next_draw_date->format('Y-m-d'), 'time' => $lottery->getDrawTime()])
            ->useResultCache(true, 3600)
            ->getResult();
        if (!count($result)) {
            throw new DataMissingException('Couldn\'t find the next draw row in the database');
        }

        return $result[0]->getJackpot();
    }

    public function getAllNextJackpots(\DateTime $date = null)
    {
        if (!$date) {
            $date = new \DateTime();
        }
        $jackpots = null;
        /** @var EuroMillionsDraw[] $result */
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT ld'
                . ' FROM ' . $this->getEntityName() . ' ld JOIN ld.lottery l'
                . ' WHERE ld.draw_date >= :date AND l.draw_time >= :time'
                . ' ORDER BY ld.draw_date ASC')
            ->setParameters([ 'date' => $date->format('Y-m-d'), 'time' => $date->format("H:i:s")])
            ->getResult();
        if (!count($result)) {
            throw new DataMissingException('Couldn\'t find the next draw row in the database');
        }
        foreach($result as $res) {
            $jackpots[$res->getLottery()->getName()] = $res->getJackpot();
        }

        return $jackpots;
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
            ->setParameters(['lottery_name' => $lottery->getName(), 'date' => $draw_date->format('Y-m-d')])
            ->useResultCache(true, 3600)
            ->getResult();
        if (!count($result)) {
            throw new DataMissingException('Couldn\'t find the last result in the database');
        }
        return $result[0]->getResult();
    }

    public function getLastSixResults(Lottery $lottery, \DateTime $nextDrawDate)
    {
        /** @var EuroMillionsDraw[] $result */
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT ld'
                . ' FROM ' . $this->getEntityName() . ' ld JOIN ld.lottery l'
                . ' WHERE l.name = :lottery_name and ld.draw_date < :date'
                . ' ORDER BY ld.draw_date DESC')
            ->setMaxResults(6)
            ->setParameters(['lottery_name' => $lottery->getName(), 'date' => $nextDrawDate->format('Y-m-d')])
            ->useResultCache(true)
            ->getResult();
        if (!count($result)) {
            throw new DataMissingException('Couldn\'t find the last result in the database');
        }

        return $result;
    }

    public function getLastBreakdown(Lottery $lottery, \DateTime $date = null)
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
            ->setParameters(['lottery_name' => $lottery->getName(), 'date' => $draw_date->format('Y-m-d')])
            ->useResultCache(true, 3600)
            ->getResult();
        if (!count($result)) {
            throw new DataMissingException('Couldn\'t find the last result in the database');
        }
        return $result[0]->getBreakDown();
    }

    /**
     * @param Lottery $lottery
     * @param \DateTime|null $date
     * @return EuroMillionsDraw|array
     */
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
            ->setParameters(['lottery_name' => $lottery->getName(), 'date' => $date->format('Y-m-d')])
            ->useResultCache(true, 3600)
            ->getResult();

        return (!empty($result)) ? $result[0] : [];

    }

    public function getLastBreakDownData(Lottery $lottery, \DateTime $date = null)
    {
        if (!$date) {
            $date = new \DateTime();
        }
        return $this->getBreakDown($lottery, $date);


    }

    public function getBreakDownData(Lottery $lottery, \DateTime $date = null)
    {
        if (!$date) {
            $date = new \DateTime();
        }
        return $this->getBreakDown($lottery, $date);
    }


    public function getDraws(Lottery $lottery, $limit = 13)
    {
        /** @var EuroMillionsDraw[] $result */
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT ld'
                . ' FROM ' . $this->getEntityName() . ' ld JOIN ld.lottery l'
                . ' WHERE l.name = :lottery_name ORDER BY ld.draw_date DESC')
            ->setMaxResults($limit)

            ->setParameters(['lottery_name' => $lottery->getName()])
            ->useResultCache(true, 3600)
            ->getResult();
        if (!count($result)) {
            throw new DataMissingException('Couldn\'t find the results in the database');
        }
        unset($result[0]);
        return $result;
    }

    public function getLastDraw(Lottery $lottery, \DateTime $date = null)
    {
        $date = $date ?: new \DateTime();
        $draw_date = $lottery->getLastDrawDate($date);
        $result = $this->findOneBy(['draw_date' => $draw_date, 'lottery' => $lottery->getId()]);
        if (!count($result)) {
            throw new DataMissingException('Cannot find last draw on database.');
        }
        return $result;
    }



    public function giveMeLotteriesOrderedByHeldDate()
    {
        /** @var EuroMillionsDraw[] $result */
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT ed'
                . ' FROM ' . $this->getEntityName() . ' ed JOIN ed.lottery l'
                . ' WHERE '
                . ' ed.draw_date >= CURRENT_DATE()'
                . ' and l.id != 2'
                . ' order by ed.draw_date ASC')
            ->useResultCache(true, 3600)
            ->getResult();
        return $result;
    }


    public function giveMeBiggestJackpot()
    {

        /** @var EuroMillionsDraw[] $result */
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT ed'
                . ' FROM ' . $this->getEntityName() . ' ed JOIN ed.lottery l'
                . ' WHERE ed.draw_date >= current_date()'
                . ' AND l.id != 2 '
                . ' ORDER BY ed.jackpot.amount DESC')
            ->setMaxResults(1)
         //   ->useResultCache(true, 3600)
            ->getResult();
        return $result[0];
    }

    /**
     * @param Lottery $lottery
     * @param $draw_date
     * @return EuroMillionsDraw
     */
    private function getBreakDown(Lottery $lottery, $draw_date)
    {
        /** @var EuroMillionsDraw[] $result */
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT ld'
                . ' FROM ' . $this->getEntityName() . ' ld JOIN ld.lottery l'
                . ' WHERE l.name = :lottery_name AND ld.draw_date = :date')
            ->setMaxResults(1)
            ->setParameters(['lottery_name' => $lottery->getName(), 'date' => $draw_date->format('Y-m-d')])
            ->useResultCache(true, 3600)
            ->getResult();
        return $result[0];
    }

}