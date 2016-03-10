<?php
namespace EuroMillions\web\repositories;

class SiteConfigRepository extends RepositoryBase
{
    /**
     * @return array
     */
    public function getSiteConfig()
    {
        $result = $this->getEntityManager()->createQuery(
            "SELECT s from {$this->getClassName()} s"
        )
            ->useResultCache(true, 300, 'SiteConfig')
            ->getResult();
        return $result;
    }
}