<?php


namespace EuroMillions\admin\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\Matcher;
use EuroMillions\web\repositories\MatcherRepository;

class MillonService
{

    private $entityManager;
    /** @var MatcherRepository $matcherRepository */
    private $matcherRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->matcherRepository = $this->entityManager->getRepository('EuroMillions\web\entities\Matcher');
    }


    public function findWinnerMillon(\DateTime $date, $millon)
    {
        $result = $this->matcherRepository->fetchRaffleMillionByDrawDate($date);
        $usersWinners = [];
        /** @var Matcher $matcher */
        foreach($result as $matcher) {
            $million = explode('-',$matcher->getRaffleMillion());
            if($million[0] == $millon) {
                $usersWinners[] = $matcher->getUser()->getEmail();
            }
        }
        return $usersWinners;
    }



}