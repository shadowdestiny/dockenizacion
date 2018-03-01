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

    public function savePost($post)
    {
        return $this->blogRepository->savePost($post);
    }

    public function updatePost($post)
    {
        $this->blogRepository->updatePost($post);
    }

    /**
     * @return object
     */
    public function getPostById($id)
    {
        return $this->blogRepository->find($id);
    }
}
