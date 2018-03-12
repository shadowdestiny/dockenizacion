<?php

namespace EuroMillions\admin\controllers;

use EuroMillions\web\services\BlogService;

class BlogController extends AdminControllerBase
{
    /** @var BlogService $blogService */
    private $blogService;

    public function initialize()
    {
        parent::initialize();
        $this->blogService = $this->domainAdminServiceFactory->getBlogService();
    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function indexAction()
    {
        return $this->view->setVars([
            'postsList' => $this->blogService->getPostsList()
        ]);
    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function createPostAction()
    {

        if ($this->request->isPost() && $this->request->getPost()) {
            $post['title'] = $this->request->getPost('title');
            $post['title_tag'] = $this->request->getPost('title_tag');
            $post['url'] = $this->request->getPost('url');
            $post['title'] = $this->request->getPost('title');
            $post['description'] = $this->request->getPost('description');
            $post['description_tag'] = $this->request->getPost('description_tag');
            $post['canonical'] = $this->request->getPost('canonical');
            $post['language'] = $this->request->getPost('language');
            $post['published'] = $this->request->getPost('published') ? true : false;
            $post['content'] = $this->request->getPost('content');
            $post['image'] = $this->request->getPost('image');
            $post['date'] = new \DateTime('now');

            $this->blogService->savePost($post);
        }
        $this->view->pick('blog/createPost');
        return $this->view->setVars([
            'postsList' => $this->blogService->getPostsList()
        ]);
    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function editPostAction()
    {
        $this->view->pick('blog/createPost');
        return $this->view->setVars([
            'post' => $this->blogService->getPostById($this->request->get('id'))
        ]);
    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function updatePostAction()
    {
        if ($this->request->isPost()) {
            $this->blogService->updatePost($this->request->getPost());
        }
        $this->view->pick('blog/index');
        return $this->view->setVars([
            'postsList' => $this->blogService->getPostsList()
        ]);
    }
}