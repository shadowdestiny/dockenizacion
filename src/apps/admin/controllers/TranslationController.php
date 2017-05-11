<?php

namespace EuroMillions\admin\controllers;

use EuroMillions\admin\services\TranslationService;
use Phalcon\Exception;

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
        $message = $this->cookies->get('message');
        $this->cookies->set('message', '');
        $this->view->setVars([
            'needLanguagesMenu' => true,
            'errorMessage' => $message,
            'categoriesList' => $this->translationService->getAllTranslationCategories(),
        ]);
    }

    public function createKeyAction()
    {
        if (!empty($this->request->getPost('key'))) {
            $response = $this->translationService->createKey([
                'key' => $this->request->getPost('key'),
                'categoryId' => $this->translationService->getTranslationCategoryById($this->request->getPost('categoryId')),
                'description' => $this->request->getPost('description'),
            ]);
            if ($response) {
                return $this->redirectToTranslation('Key saved correctly');
            }
        }
        return $this->redirectToTranslation('Key wasn\'t saved');
    }

    public function EditKeyAction()
    {
        if (!empty($this->request->getPost('key'))) {
            $response = $this->translationService->editKey($this->request->getPost());
            if ($response) {
                return $this->redirectToTranslation('Key saved correctly');
            }
        }
        return $this->redirectToTranslation('Key wasn\'t saved');
    }

    /**
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function deleteKeyAction()
    {
        if ($this->request->get('key')) {
            $this->translationService->deleteKey($this->request->get('key'));

            return $this->redirectToTranslation('Key deleted correctly');
        }
        return $this->redirectToTranslation('Key wasn\'t deleted');
    }

    public function translationKeysResultAction()
    {
        $this->view->pick('translation/results/_translationResults');
        $this->view->setVars([
            'keysList' => $this->translationService->getKeysByCategoryIdAndKey(
                $this->request->getPost('categoryId'),
                $this->request->getPost('key')
            ),
            'languages' => $this->translationService->getAllTranslationLanguages(),
        ]);
    }

    public function saveTranslationsAction()
    {
        try {
            $this->translationService->saveTranslations($this->request->getPost());
        } catch (Exception $e) {
            return $this->redirectToTranslation('Translations wasn\'t saved correctly');
        }

        return $this->redirectToTranslation('Translations saved correctly');
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

    public function languagesAction()
    {
        $message = $this->cookies->get('message');
        $this->cookies->set('message', '');
        $this->view->setVars([
            'needLanguagesMenu' => true,
            'errorMessage' => $message,
            'languagesList' => $this->translationService->getAllTranslationLanguages(),
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

    /**
     * @param null $message
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    private function redirectToLanguage($message = null)
    {
        $this->cookies->set('message', $message);
        return $this->response->redirect('/admin/translation/languages');
    }

    /**
     * @param null $message
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    private function redirectToTranslation($message = null)
    {
        $this->cookies->set('message', $message);
        return $this->response->redirect('/admin/translation/index');
    }

    /**
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function createLanguageAction()
    {
        if (!empty($this->request->getPost('ccode'))) {
            $this->translationService->createLanguage([
                'ccode' => $this->request->getPost('ccode'),
                'defaultLocale' => $this->request->getPost('defaultLocale'),
                'active' => $this->request->getPost('active') ? 1 : 0,
            ]);

            return $this->redirectToLanguage('Language saved correctly');
        }

        return $this->redirectToLanguage('Language wasn\'t saved');
    }

    /**
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function editLanguageAction()
    {
        if ($this->request->getPost('id')) {
            $this->translationService->editLanguage([
                'id' => $this->request->getPost('id'),
                'ccode' => $this->request->getPost('ccode'),
                'defaultLocale' => $this->request->getPost('defaultLocale'),
                'active' => $this->request->getPost('active') ? 1 : 0,
            ]);


            return $this->redirectToLanguage('Language edited correctly');
        }

        return $this->redirectToLanguage('Language wasn\'t edited');
    }

    /**
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function deleteLanguageAction()
    {

        if ($this->request->get('id')) {
            $this->translationService->deleteLanguage($this->request->get('id'));

            return $this->redirectToLanguage('Language deleted correctly');
        }
        return $this->redirectToLanguage('Language wasn\'t deleted');
    }
}