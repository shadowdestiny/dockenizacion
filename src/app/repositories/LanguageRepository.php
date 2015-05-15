<?php
namespace app\repositories;

use Doctrine\ORM\EntityRepository;

class LanguageRepository extends EntityRepository
{
    /**
     * @return array[\app\entities\Language]
     */
    public function getAvailableLanguages()
    {
        return $this->findAll();
    }

    /**
     * @return array[\app\entities\Language]
     */
    public function getActiveLanguages()
    {
        return $this->findBy(['active' => true]);
    }
}