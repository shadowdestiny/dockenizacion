<?php


namespace EuroMillions\repositories;


use Doctrine\ORM\Query\ResultSetMapping;
use EuroMillions\entities\PlayConfig;

class BetRepository extends RepositoryBase
{

    public function getBetsByDrawDate(\DateTime $date)
    {

    }

    public function getBetsByPlayConfig(PlayConfig $playConfig)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT b'
                . ' FROM ' . $this->getEntityName() . ' p'
                . ' WHERE b.play_config = :play_config_id')
            ->setParameters(['play_config_id' => $playConfig->getId()])
            ->getResult();

        return $result;
    }

    public function getCheckResult($date)
    {
        $rsm = new ResultSetMapping;
        $rsm->addEntityResult('EuroMillions\entities\PlayConfig', 'p');
        $rsm->addFieldResult('p','id','id');
        $rsm->addMetaResult('p','user_id','user_id');
        $rsm->addScalarResult('cnt','cnt');
        $rsm->addScalarResult('cnt_lucky','cnt_lucky');

        $result = $this->getEntityManager()
            ->createNativeQuery("SELECT p.id,p.user_id,
                      (IF(p.line_regular_number_one IN (e.result_regular_number_one,
                                                    e.result_regular_number_two,
                                                    e.result_regular_number_three,
                                                    e.result_regular_number_four,
                                                    e.result_regular_number_five), 1, 0) +
                        IF(p.line_regular_number_two IN (e.result_regular_number_one,
                                                    e.result_regular_number_two,
                                                    e.result_regular_number_three,
                                                    e.result_regular_number_four,
                                                    e.result_regular_number_five), 1, 0) +
                        IF(p.line_regular_number_three IN (e.result_regular_number_one,
                                                    e.result_regular_number_two,
                                                    e.result_regular_number_three,
                                                    e.result_regular_number_four,
                                                    e.result_regular_number_five), 1, 0) +
                        IF(p.line_regular_number_four IN (e.result_regular_number_one,
                                                    e.result_regular_number_two,
                                                    e.result_regular_number_three,
                                                    e.result_regular_number_four,
                                                    e.result_regular_number_five), 1, 0) +
                        IF(p.line_regular_number_five IN (e.result_regular_number_one,
                                                    e.result_regular_number_two,
                                                    e.result_regular_number_three,
                                                    e.result_regular_number_four,
                                                    e.result_regular_number_five), 1, 0)
                    ) as cnt,
                    (IF(p.line_lucky_number_one IN (e.result_lucky_number_one,
                                                    e.result_lucky_number_two), 1, 0) +
                        IF(p.line_lucky_number_two IN (e.result_lucky_number_one,
                                                    e.result_lucky_number_two), 1, 0)
                     ) as cnt_lucky
                from play_configs p
                INNER JOIN bets b ON b.play_config_id=p.id
                INNER JOIN euromillions_draws e on e.id=b.euromillions_draw_id
                WHERE p.active=1 and e.draw_date = ?
                having (cnt=5 and cnt_lucky=2) OR (cnt=5 and cnt_lucky=1) OR (cnt=5 and cnt_lucky=0)
                OR (cnt=4 and cnt_lucky=2) OR (cnt=4 and cnt_lucky=1) OR (cnt=4 and cnt_lucky=0)
                OR (cnt=3 and cnt_lucky=2) OR (cnt=2 and cnt_lucky=2) OR (cnt=3 and cnt_lucky=1)
                OR (cnt=3 and cnt_lucky=0) OR (cnt=1 and cnt_lucky=2) OR (cnt=2 and cnt_lucky=1)
                OR (cnt=2 and cnt_lucky=0);", $rsm);

        $result->setParameter(1,$date);

        return $result->getResult();

    }
}