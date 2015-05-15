<?php
namespace app\repositories;

use Doctrine\ORM\EntityRepository;

class LanguageRepository extends EntityRepository
{
    public function getAvailableLanguages()
    {
        return $this->findAll();
    }

    public function getActiveLanguages()
    {
        return $this->findBy(['active' => true]);
    }
}