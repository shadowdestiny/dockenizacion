<?php
namespace app\services;

use app\components\EmTranslationAdapter;
use app\repositories\LanguageRepository;
use Doctrine\ORM\EntityManager;

class LanguageService
{
    protected $languageRepository;
    protected $language;
    protected $translationAdapter;

    public function __construct($language, EntityManager $entityManager, EmTranslationAdapter $translationAdapter = null)
    {
        $this->language = $language;
        $this->languageRepository = $entityManager->getRepository('app\entities\Language');
        $this->translationAdapter = $translationAdapter? $translationAdapter: new EmTranslationAdapter($this->language, $entityManager);
    }

    public function availableLanguages()
    {
        return $this->languageRepository->getAvailableLanguages();
    }

    public function translate($key, array $placeholders = null)
    {
        return $this->translationAdapter->_($key, $placeholders);
    }
}