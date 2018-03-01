<?php

namespace EuroMillions\web\repositories;

use EuroMillions\web\entities\Blog;

class BlogRepository extends RepositoryBase
{

    public function savePost($post)
    {
        $blog = new Blog();
        $blog->initialize([
            'url' => $post['url'],
            'title' => $post['title'],
            'description' => $post['description'],
            'canonical' => $post['canonical'],
            'language' => $post['language'],
            'published' => $post['published'],
            'content' => $post['content'],
            'image' => $post['image'],
            'date' => $post['date'],
        ]);
        $this->add($blog);
        $this->getEntityManager()->flush($blog);
        return $blog;
    }

    public function updatePost($post)
    {
        /** @var Blog $blog */
        $blog = $this->find($post['id']);
        $blog->setUrl($post['url']);
        $blog->setTitle($post['title']);
        $blog->setDescription($post['description']);
        $blog->setCanonical($post['canonical']);
        $blog->setLanguage($post['language']);
        $blog->setPublished($post['published'] ? true : false );
        $blog->setContent($post['content']);
        $blog->setImage($post['image'] ? true : false);
        $blog->setDate(new \DateTime());

        $this->getEntityManager()->persist($blog);
        $this->getEntityManager()->flush($blog);
    }
}