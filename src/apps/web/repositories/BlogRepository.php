<?php

namespace EuroMillions\web\repositories;

use EuroMillions\web\entities\Blog;

class BlogRepository extends RepositoryBase
{
    /**
     * @param $post
     *
     * @return Blog
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
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

    /**
     * @param $post
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updatePost($post)
    {
        /** @var Blog $blog */
        $blog = $this->find($post['id']);
        $blog->setTitle($post['title']);
        $blog->setTitleTag($post['title_tag']);
        $blog->setUrl($post['url']);
        $blog->setDescription($post['description']);
        $blog->setDescriptionTag($post['description_tag']);
        $blog->setCanonical($post['canonical']);
        $blog->setLanguage($post['language']);
        $blog->setPublished($post['published'] ? true : false );
        $blog->setContent($post['content']);
        $blog->setImage($post['image']);

        $this->getEntityManager()->persist($blog);
        $this->getEntityManager()->flush($blog);
    }

    /**
     * @param $id
     *
     * @param $language
     *
     * @param $condition
     *
     * @return Blog
     *
     */

    public function getNextPrevPost($id, $language, $condition)
    {
        $qb = $this->createQueryBuilder('blog');
        $qb->where('blog.language like :language');
        if($condition =='next')
        {
            $qb->andWhere('blog.id > :identifier')
                ->orderBy('blog.id', 'asc');
        }
        else
        {
            $qb->andWhere('blog.id < :identifier')
                ->orderBy('blog.id', 'desc');
        }
        $qb->setParameter('identifier', $id);
        $qb->setParameter('language', $language);

        $result=  $qb->getQuery()
                     ->getResult();

        if(!is_null($result))
        {
            return $result[0];
        }

        return null;
    }
}