<?php
namespace EuroMillions\web\components;

use EuroMillions\web\repositories\TranslationDetailRepository;
use Phalcon\Translate\Adapter;

class EmTranslationAdapter extends Adapter
{
    protected $language;
    /** @var TranslationDetailRepository  */
    protected $repository;

    public function __construct($language, TranslationDetailRepository $repository)
    {
        $this->setLanguage($language);
        $this->repository = $repository;
    }

    /**
     * Returns the translation related to the given key
     *
     * @param string $index
     * @param array $placeholders
     * @return    string
     */
    public function query($index, $placeholders = null)
    {
        $translation = $this->repository->getTranslation($this->language, $index);
        if($placeholders) {
            foreach($placeholders as $key => $value) {
                $translation = str_replace('%' . $key . '%', $value, $translation);
            }
        }
        return $translation;
    }

    /**
     * Check whether is defined a translation key in the internal array
     *
     * @param string $index
     * @return bool
     */
    public function exists($index)
    {
        // TODO: Implement exists() method.
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }
}