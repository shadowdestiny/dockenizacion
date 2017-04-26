<?php

namespace EuroMillions\web\repositories;

use Doctrine\ORM\EntityRepository;

class TranslationDetailRepository extends EntityRepository
{
    //TODO:set cache and default language
    public function getTranslation($language, $key)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT td.value'
                . ' FROM ' . $this->getEntityName() . ' td JOIN td.translation t'
                . ' WHERE td.lang = :lang AND t.translationKey = :key')
            ->setMaxResults(1)
            ->setParameters(['lang' => $language, 'key' => $key])
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
}