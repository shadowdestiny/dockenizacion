<?php
namespace EuroMillions\shared\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\SiteConfig;

class SiteConfigService
{
    protected $configEntity;

    public function __construct(EntityManager $entityManager)
    {
        $site_config_repository = $entityManager->getRepository('EuroMillions\web\entities\SiteConfig');

        $result = $entityManager->createQuery(
            "SELECT s from {$site_config_repository->getClassName()} s"
        )
            ->useResultCache(true)
            ->getResult();
        /** @var SiteConfig $config */
        foreach ($result as $config) {
            $this->configEntity[$config->getName()] = $config->getValue();
        }
    }

    /**
     * @param $name
     * @return string
     */
    public function get($name)
    {
        return $this->configEntity[$name];
    }
}