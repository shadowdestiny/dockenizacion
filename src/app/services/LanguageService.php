<?php
namespace app\services;

use app\repositories\LanguageRepository;
use Doctrine\ORM\EntityManager;

class LanguageService
{
    protected $languageRepository;

    public function __construct(EntityManager $entityManager, LanguageRepository $langRepository = null)
    {
        $this->languageRepository = $langRepository ? $langRepository : $entityManager->getRepository('app\entities\Language');
    }

    public function availableLanguages()
    {
        return $this->languageRepository->getAvailableLanguages();
    }
}