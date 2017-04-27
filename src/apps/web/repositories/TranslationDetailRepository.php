<?php

namespace EuroMillions\web\repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class TranslationDetailRepository extends EntityRepository
{
    /**
     * @param $language
     * @param $key
     *
     * @return mixed
     */
    public function getTranslation($language, $key)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT td.value'
                . ' FROM ' . $this->getEntityName() . ' td JOIN td.translation t JOIN td.language l'
                . ' WHERE td.lang = :lang AND t.translationKey = :key AND l.active = :active')
            ->setMaxResults(1)
            ->setParameters(['lang' => $language, 'key' => $key, 'active' => 1])
            ->useResultCache(true, 3600)
            ->getResult();

        if ($result) {
            return $result[0]['value'];
        } else {
            $result = $this->getEntityManager()
                ->createQuery(
                    'SELECT td.value'
                    . ' FROM ' . $this->getEntityName() . ' td JOIN td.translation t'
                    . ' WHERE td.lang = :lang AND t.translationKey = :key')
                ->setMaxResults(1)
                ->setParameters(['lang' => 'en', 'key' => $key])
                ->useResultCache(true, 3600)
                ->getResult();

            if ($result) {
                return $result[0]['value'];
            }

            return $key;
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
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('translationKey', 'translationKey');
        $rsm->addScalarResult('description', 'description');
        $rsm->addScalarResult('value', 'value');
        $rsm->addScalarResult('lang', 'lang');
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('language_id', 'language_id');
        if (empty($key)) {
            $where = 'WHERE translationCategory_id = ' . $categoryId;
        } else {
            $where = 'WHERE translationCategory_id = ' . $categoryId . ' AND translationKey like \'%' . $key . '%\'';
        }
        return $this->getEntityManager()->createNativeQuery(
            'SELECT translationKey, description, td.value, td.lang, t.id, td.language_id
                FROM translations t
                LEFT JOIN translation_details td ON t.id = td.translation_id
                ' . $where . '
                ORDER BY translationKey, lang ASC', $rsm)->getResult();
    }

    /**
     * @param array $translationData
     */
    public function saveTranslation(array $translationData)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $translationExist = $this->getEntityManager()->createNativeQuery(
            'SELECT id
                 FROM translation_details
                 WHERE translation_id = ' . $translationData['translationId'] . ' AND language_id = ' . $translationData['languageId'] .
            ' AND lang = "' . $translationData['lang'] . '"', $rsm)->getResult();
        if (empty($translationExist)) {
            $sql = 'INSERT INTO translation_details (translation_id, language_id, value, lang)
                VALUES (' . $translationData['translationId'] . ', ' . $translationData['languageId'] . ', "' . $translationData['value'] . '", "' . $translationData['lang'] . '")';
        } else {
            $sql = 'UPDATE translation_details SET value = "' . $translationData['value'] . '"
            WHERE id = ' . $translationExist[0]['id'];
        }

        $this->getEntityManager()->getConnection()->executeQuery($sql);
    }
}