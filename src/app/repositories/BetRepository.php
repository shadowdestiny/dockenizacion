<?php


namespace EuroMillions\repositories;


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
}