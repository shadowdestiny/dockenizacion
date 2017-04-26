<?php

namespace EuroMillions\admin\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\Translation;
use EuroMillions\web\entities\TranslationCategory;
use EuroMillions\web\entities\Language;
use EuroMillions\web\repositories\LanguageRepository;
use EuroMillions\web\repositories\TranslationCategoryRepository;
use EuroMillions\web\repositories\TranslationDetailRepository;
use Phalcon\Exception;

class TranslationService
{
    private $entityManager;
    /** @var Translation $translationRepository */
    private $translationRepository;
    /** @var TranslationDetailRepository $translationDetailRepository */
    private $translationDetailRepository;
    /** @var TranslationCategoryRepository $translationCategoryRepository */
    private $translationCategoryRepository;
    /** @var LanguageRepository $languageRepository */
    private $languageRepository;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->translationRepository = $this->entityManager->getRepository('EuroMillions\web\entities\Translation');
        $this->translationDetailRepository = $this->entityManager->getRepository('EuroMillions\web\entities\TranslationDetail');
        $this->translationCategoryRepository = $this->entityManager->getRepository('EuroMillions\web\entities\TranslationCategory');
        $this->languageRepository = $this->entityManager->getRepository('EuroMillions\web\entities\Language');
    }

    /**
     * @param $arrayKey
     *
     * @throws Exception
     */
    public function createKey($arrayKey)
    {
        try {
            $this->entityManager->persist(new Translation($arrayKey));
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new Exception("Was not possible to create Translation Key");
        }
    }

    /**
     * @return array
     */
    public function getAllTranslationCategories()
    {
        return $this->translationCategoryRepository->findAll();
    }

    /**
     * @param $id
     *
     * @return null|object
     */
    public function getTranslationCategoryById($id)
    {
        return $this->translationCategoryRepository->findOneBy(['id' => $id]);
    }

    /**
     * @param $translations
     */
    public function saveTranslations($translations)
    {
        foreach($translations as $translationKey => $translation) {
            $translationDetailsArray = explode('|', $translationKey);
            $this->translationDetailRepository->saveTranslation([
                'translationId' => $translationDetailsArray[0],
                'languageId' => $translationDetailsArray[1],
                'value' => $translation,
                'lang' => $translationDetailsArray[2],
            ]);
        }
    }

    /**
     * @param $categoryId
     * @param $key
     *
     * @return array
     */
    public function getKeysByCategoryIdAndKey($categoryId, $key)
    {
        $translationKeys = $this->translationDetailRepository->getKeysByCategoryIdAndKey($categoryId, $key);
        $translations = [];
        if (!empty($translationKeys)) {
            $oldTranslation['translationKey'] = $translationKeys[0]['translationKey'];
            $translation = [
                'translationKey' => $translationKeys[0]['translationKey'],
                'description' => $translationKeys[0]['description'],
                'id' => $translationKeys[0]['id'],
                $translationKeys[0]['lang'] => $translationKeys[0]['value'],
            ];
            foreach ($translationKeys as $translationKey) {
                if ($oldTranslation['translationKey'] == $translationKey['translationKey']) {
                    $translation[$translationKey['lang']] = $translationKey['value'];
                } else {
                    $translations[] = $translation;
                    $translation = [
                        'translationKey' => $translationKey['translationKey'],
                        'description' => $translationKey['description'],
                        'id' => $translationKey['id'],
                        $translationKey['lang'] => $translationKey['value'],
                    ];
                }
                $oldTranslation['translationKey'] = $translationKey['translationKey'];

            }
            $translations[] = $translation;
        }
        return $translations;
    }

    public function getLanguagesActives()
    {
        return $this->languageRepository->findBy(['active' => 1], ['ccode' => 'ASC']);
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

    /**
     * @return array
     */
    public function getAllTranslationLanguages()
    {
        return $this->languageRepository->findAll();
    }

    /**
     * @param $arrayLanguage
     *
     * @throws Exception
     */
    public function createLanguage($arrayLanguage)
    {
        try {
            $this->entityManager->persist(new Language($arrayLanguage));
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new Exception("Was not possible to create Language");
        }
    }

    /**
     * @param $arrayLanguage
     *
     * @throws Exception
     */
    public function editLanguage($arrayLanguage)
    {
        try {

            /** @var Language $language */
            $language = $this->languageRepository->findOneBy(['id' => $arrayLanguage['id']]);

            $language->setCcode($arrayLanguage['ccode']);
            $language->setDefaultLocale($arrayLanguage['defaultLocale']);

            $this->entityManager->persist($language);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new Exception("Was not possible to create Language");
        }
    }

    /**
     * @param int $id
     * @throws Exception
     */
    public function deleteLanguage($id)
    {
        try {
            $this->entityManager->remove($this->languageRepository->find($id));

            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new Exception("Was not possible to delete Language data");
        }
    }

}