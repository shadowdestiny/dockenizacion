<?php

namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\repositories\BlogRepository;


class BlogService
{
    /** @var BlogRepository $blogRepository */
    private $blogRepository;

    public function __construct(EntityManager $entityManager)
    {
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

    /**
     * @param $language
     *
     * @return array
     */
    public function getPostsPublishedListByLanguage($language)
    {
        return $this->blogRepository->findBy(['language' => $language, 'published' => 1]);
    }

    /**
     * @param $url
     * @param $language
     *
     * @return null|object
     */
    public function getPostByUrlAndLanguage($url, $language)
    {
        return $this->blogRepository->findOneBy(['url' => $url, 'language' => $language, 'published' => 1]);
    }

}
