<?php

namespace EuroMillions\admin\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\repositories\TranslationCategoryRepository;

class TranslationService
{
    private $entityManager;
    /** @var TranslationCategoryRepository $translationCategoryRepository */
    private $translationCategoryRepository;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->translationCategoryRepository = $this->entityManager->getRepository('EuroMillions\web\entities\TranslationCategory');
    }

    /**
     * @return array
     */
    public function getAllTranslationCategories()
    {
        return $this->translationCategoryRepository->findAll();
    }
}