<?php

namespace EuroMillions\admin\controllers;

use EuroMillions\admin\services\TranslationService;

class TranslationController extends AdminControllerBase
{
    /** @var TranslationService $translationService */
    private $translationService;

    public function initialize()
    {
        parent::initialize();
        $this->translationService = $this->domainAdminServiceFactory->getTranslationService();
    }

    public function indexAction()
    {
        $this->view->setVars([
            'needLanguagesMenu' => true,
        ]);
    }

    public function categoriesAction()
    {
        $message = $this->cookies->get('message');
        $this->cookies->set('message', '');
        $this->view->setVars([
            'needLanguagesMenu' => true,
            'errorMessage' => $message,
            'categoriesList' => $this->translationService->getAllTranslationCategories(),
        ]);
    }

    /**
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function createCategoryAction()
    {
        if (!empty($this->request->getPost('name'))) {
            $this->translationService->createCategory([
                'name' => $this->request->getPost('name'),
                'code' => $this->request->getPost('code'),
                'description' => $this->request->getPost('description'),
            ]);

            return $this->redirectToCategory('Category saved correctly');
        }

        return $this->redirectToCategory('Category wasn\'t saved');
    }

    /**
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function editCategoryAction()
    {
        if ($this->request->getPost('id')) {
            $this->translationService->editCategory([
                'id' => $this->request->getPost('id'),
                'code' => $this->request->getPost('code'),
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
            ]);

            return $this->redirectToCategory('Category edited correctly');
        }

        return $this->redirectToCategory('Category wasn\'t edited');
    }

    /**
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function deleteCategoryAction()
    {
        if ($this->request->get('id')) {
            $this->translationService->deleteCategory($this->request->get('id'));

            return $this->redirectToCategory('Category deleted correctly');
        }
        return $this->redirectToCategory('Category wasn\'t deleted');
    }

    /**
     * @param null $message
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    private function redirectToCategory($message = null)
    {
        $this->cookies->set('message', $message);
        return $this->response->redirect('/admin/translation/categories');
    }
}