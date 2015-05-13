<?php
namespace app\components;

use app\repositories\TranslationDetailRepository;
use Doctrine\ORM\EntityManager;
use Phalcon\Translate\Adapter;

class EmTranslationAdapter extends Adapter
{
    protected $language;
    /** @var TranslationDetailRepository  */
    protected $repository;

    public function __construct($language, EntityManager $entityManager = null)
    {
        $this->language = $language;
        $this->repository = $entityManager->getRepository('app\entities\TranslationDetail');
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
        return $this->repository->getTranslation($this->language, $index);
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
}