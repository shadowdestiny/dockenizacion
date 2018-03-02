<?php

namespace EuroMillions\web\controllers;

use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\entities\Blog;

class BlogController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $this->tag->prependTitle($this->languageService->translate('blogindex_name'));
        MetaDescriptionTag::setDescription($this->languageService->translate('blogindex_desc'));

        return $this->view->setVars([
            'postsBlog' => $this->blogService->getPostsPublishedListByLanguage($this->router->getParams()['language']),
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
