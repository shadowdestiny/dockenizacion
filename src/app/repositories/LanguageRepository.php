<?php
namespace EuroMillions\repositories;

use Doctrine\ORM\EntityRepository;

class LanguageRepository extends EntityRepository
{
    /**
     * @return array[\EuroMillions\entities\Language]
     */
    public function getAvailableLanguages()
    {
        return $this->findAll();
    }

    /**
     * @return array[\EuroMillions\entities\Language]
     */
    public function getActiveLanguages()
    {
        return $this->findBy(['active' => true]);
    }

    public function getActiveLanguage($ccode)
    {
        return $this->findOneBy(['ccode' => $ccode, 'active'=> true]);
    }
}