<?php

namespace EuroMillions\web\repositories;

use EuroMillions\web\entities\Blog;

class BlogRepository extends RepositoryBase
{

    public function savePost($post)
    {
        $blog = new Blog();
        $blog->initialize([
            'title' => $post['title'],
            'title_tag' => $post['title_tag'],
            'url' => $post['url'],
            'description' => $post['description'],
            'description_tag' => $post['description_tag'],
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
        $blog->setTitle($post['title']);
        $blog->setTitleTag($post['title_tag']);
        $blog->setUrl($post['url']);
        $blog->setDescription($post['description']);
        $blog->setCanonical($post['canonical']);
        $blog->setLanguage($post['language']);
        $blog->setPublished($post['published'] ? true : false );
        $blog->setContent($post['content']);
        $blog->setImage($post['image']);
        $blog->setDate(new \DateTime());

        $this->getEntityManager()->persist($blog);
        $this->getEntityManager()->flush($blog);
    }
}