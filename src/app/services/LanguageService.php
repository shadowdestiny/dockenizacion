<?php
namespace EuroMillions\services;

use EuroMillions\components\EmTranslationAdapter;
use Doctrine\ORM\EntityManager;
use EuroMillions\repositories\LanguageRepository;

class LanguageService
{
    /** @var \EuroMillions\repositories\LanguageRepository  */
    protected $languageRepository;
    protected $language;
    protected $translationAdapter;

    public function __construct($language, LanguageRepository $languageRepository, EmTranslationAdapter $translationAdapter)
    {
        $this->language = $language;
        $this->languageRepository = $languageRepository;
        $this->translationAdapter = $translationAdapter;
    }

    public function availableLanguages()
    {
        return $this->languageRepository->getAvailableLanguages();
    }

    public function activeLanguages()
    {
        return $this->languageRepository->getActiveLanguages();
    }

    public function translate($key, array $placeholders = null)
    {
        return $this->translationAdapter->_($key, $placeholders);
    }

    public function setLanguage($language)
    {

    }
}