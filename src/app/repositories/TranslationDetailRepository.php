<?php
namespace app\repositories;

use Doctrine\ORM\EntityRepository;

class TranslationDetailRepository extends EntityRepository
{
    public function getTranslation($language, $key)
    {
        $result = $this->getEntityManager()
            ->createQuery('SELECT td.value FROM app\entities\TranslationDetail td JOIN app\entities\Translation t WHERE td.lang = :lang AND t.key = :key')
            ->setMaxResults(1)
            ->setParameters(['lang' => $language, 'key'=>$key])
            ->getResult();
        return $result[0]['value'];
    }
}