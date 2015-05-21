<?php
namespace EuroMillions\components;

use EuroMillions\repositories\TranslationDetailRepository;
use Doctrine\ORM\EntityManager;
use Phalcon\Translate\Adapter;

class EmTranslationAdapter extends Adapter
{
    protected $language;
    /** @var TranslationDetailRepository  */
    protected $repository;

    public function __construct($language, EntityManager $entityManager = null)
    {
        $this->setLanguage($language);
        $this->repository = $entityManager->getRepository('EuroMillions\entities\TranslationDetail');
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