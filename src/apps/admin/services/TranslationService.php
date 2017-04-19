<?php

namespace EuroMillions\admin\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\TranslationCategory;
use EuroMillions\web\repositories\TranslationCategoryRepository;
use Phalcon\Exception;

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

    /**
     * @param $arrayCategory
     *
     * @throws Exception
     */
    public function createCategory($arrayCategory)
    {
        try {
            $this->entityManager->persist(new TranslationCategory($arrayCategory));
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new Exception("Was not possible to create Category");
        }
    }

    /**
     * @param $arrayCategory
     *
     * @throws Exception
     */
    public function editCategory($arrayCategory)
    {
        try {
            /** @var TranslationCategory $translationCategory */
            $translationCategory = $this->translationCategoryRepository->findOneBy(['id' => $arrayCategory['id']]);

            $translationCategory->setCategoryName($arrayCategory['name']);
            $translationCategory->setCategoryCode($arrayCategory['code']);
            $translationCategory->setDescription($arrayCategory['description']);

            $this->entityManager->persist($translationCategory);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new Exception("Was not possible to create Category");
        }
    }

    /**
     * @param int $id
     * @throws Exception
     */
    public function deleteCategory($id)
    {
        try {
            $this->entityManager->remove($this->translationCategoryRepository->find($id));

            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new Exception("Was not possible to delete TrackingCode data");
        }
    }

}