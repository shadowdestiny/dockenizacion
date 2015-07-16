<?php
namespace EuroMillions\services;

use EuroMillions\components\EmTranslationAdapter;
use Doctrine\ORM\EntityManager;
use EuroMillions\entities\Language;
use EuroMillions\exceptions\InvalidLanguageException;
use EuroMillions\interfaces\ILanguageStrategy;
use EuroMillions\repositories\LanguageRepository;

class LanguageService
{
    /** @var \EuroMillions\repositories\LanguageRepository  */
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
        $language_entity = $this->languageRepository->getActiveLanguage($language_code);
        if (!$language_entity) {
            throw new InvalidLanguageException("There's no active language with ccode $language_code");
        }
        return $language_entity->getDefaultLocale();
    }
}