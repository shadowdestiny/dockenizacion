<?php

namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\repositories\BlogRepository;


class BlogService
{
    private $entityManager;

    /** @var BlogRepository $blogRepository */
    private $blogRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->blogRepository = $entityManager->getRepository('EuroMillions\web\entities\Blog');
    }

    /**
     * @return array
     */
    public function getPostsList()
    {
        return $this->blogRepository->findAll();
    }
}
