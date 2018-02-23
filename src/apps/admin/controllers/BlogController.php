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
}