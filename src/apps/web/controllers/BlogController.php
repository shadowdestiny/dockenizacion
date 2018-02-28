<?php

namespace EuroMillions\web\controllers;

use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\entities\Blog;

class BlogController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $this->tag->prependTitle($this->languageService->translate('contact_name'));
        MetaDescriptionTag::setDescription($this->languageService->translate('contact_desc'));

        return $this->view->setVars([
            'postsBlog' => $this->blogService->getPostsPublishedListByLanguage($this->router->getParams()['language'])
        ]);
    }

    public function postAction()
    {
        /** @var Blog $postData */
        $postData = $this->blogService->getPostByUrlAndLanguage($this->router->getParams()[0], $this->router->getParams()['language']);

        if (!empty($postData)) {
            $this->tag->prependTitle($postData->getTitle());
            MetaDescriptionTag::setDescription($postData->getDescription());

            return $this->view->setVars([
                'postData' => $postData,
            ]);
        }

        //TODO: Redirect to index Blog
        return $this->response->redirect('/');
    }
}
