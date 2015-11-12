<?php
namespace EuroMillions\web\services;

use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\entities\Language;
use EuroMillions\web\interfaces\ILanguageStrategy;
use EuroMillions\web\repositories\LanguageRepository;

class LanguageService
{
    /** @var \EuroMillions\web\repositories\LanguageRepository  */
    protected $languageRepository;
    protected $languageStrategy;
    protected $translationAdapter;

    public function __construct(ILanguageStrategy $languageStrategy, LanguageRepository $languageRepository, EmTranslationAdapter $translationAdapter)
    {
        $this->languageRepository = $languageRepository;
        $this->languageStrategy = $languageStrategy;
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
        //EMTD after MVP
    }

    public function getLocale()
    {
        $language_code = $this->languageStrategy->get();
        /** @var Language $language_entity */
        $language_entity = $this->languageRepository->getActiveLanguage($language_code);
        if (!$language_entity) {
            $language_entity = $this->languageRepository->findOneBy(['ccode' => 'en']);
        }
        return $language_entity->getDefaultLocale();
    }
}