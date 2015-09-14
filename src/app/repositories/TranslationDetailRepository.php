<?php
namespace EuroMillions\repositories;

use Doctrine\ORM\EntityRepository;

class TranslationDetailRepository extends EntityRepository
{
    public function getTranslation($language, $key)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT td.value'
                .' FROM '.$this->getEntityName().' td JOIN td.translation t'
                .' WHERE td.lang = :lang AND t.key = :key')
            ->setMaxResults(1)
            ->setParameters(['lang' => $language, 'key'=>$key])
            ->useResultCache(true)
            ->getResult();
        if ($result) {
            return $result[0]['value'];
        } else {
            return $key;
        }
    }
}