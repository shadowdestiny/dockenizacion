<?php


namespace EuroMillions\web\repositories;


class CurrencyRepository extends RepositoryBase
{

    public function getAllCurrencies()
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT *'
                .' FROM '.$this->getEntityName().' c ')
            ->useResultCache(true)
            ->getResult();
        return !empty($result) ? $result[0] : null;
    }

}