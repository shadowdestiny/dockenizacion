<?php

namespace EuroMillions\web\controllers;

use EuroMillions\shared\components\widgets\PaginationWidget;
use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\entities\Blog;

class BlogController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $this->tag->prependTitle($this->languageService->translate('blogindex_name'));
        MetaDescriptionTag::setDescription($this->languageService->translate('blogindex_desc'));

        $posts = $this->blogService->getPostsPublishedListByLanguage($this->router->getParams()['language']);

        $page = (!empty($this->request->get('page'))) ? $this->request->get('page') : 1;
        $paginator = $this->getPaginatorAsArray(!empty($posts) ? $posts : [], 10, $page);
        $paginatorView = (new PaginationWidget($paginator, $this->request->getQuery()))->render();

        return $this->view->setVars([
            'postsBlog' => $paginator->getPaginate()->items,
            'paginator_view' => $paginatorView,
            'pageController' => 'blogIndex',
        ]);
    }

    public function postAction()
    {
        /** @var Blog $postData */
        $postData = $this->blogService->getPostByUrlAndLanguage($this->router->getParams()[0], $this->router->getParams()['language']);

        if (!empty($postData)) {
            $this->tag->prependTitle($postData->getTitleTag());
            MetaDescriptionTag::setDescription($postData->getDescriptionTag());

            return $this->view->setVars([
                'postData' => $postData,
                'pageController' => 'blogIndex',
            ]);
        }

        return $this->response->redirect('/' . $this->languageService->translate('link_blogindex'));
    }
}
