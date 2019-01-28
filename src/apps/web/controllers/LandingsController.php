<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 3/10/18
 * Time: 10:52
 */

namespace EuroMillions\web\controllers;
use EuroMillions\web\components\ViewHelper;
use EuroMillions\shared\helpers\SiteHelpers;
use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;

class LandingsController extends PublicSiteControllerBase
{

    public function mainAction()
    {
        $this->configLanding($this->router->getParams()['lottery']);

        return  $this->view->pick('_elements/landing--blue');
    }

    public function mainorangeAction()
    {
        $this->configLanding($this->router->getParams()['lottery']);

        $errors = [];
        $form_errors = $this->getErrorsArray();
        $sign_up_form = $this->getSignUpForm();
        $url_redirect = $this->session->get('original_referer');

        if ($this->request->isPost()) {
            $credentials = [
                'name' => $this->request->getPost('name'),
                'surname' => $this->request->getPost('surname'),
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'country' => $this->request->getPost('country'),
                'ipaddress' => !empty($this->request->getClientAddress(true)) ? $this->request->getClientAddress(true) : self::IP_DEFAULT,
                'default_language' => explode('_', $this->languageService->getLocale())[0],
                'phone_number' => $this->request->getPost('prefix')."-".$this->request->getPost('phone'),
                'birth_date' => $this->request->getPost('year').'-'.$this->request->getPost('month').'-'.$this->request->getPost('day')
            ];

            if ($sign_up_form->isValid($this->request->getPost()) === false || checkdate($this->request->getPost('month'), $this->request->getPost('day'), $this->request->getPost('year'))===false) {
                $messages = $sign_up_form->getMessages(true);
                /**
                 * @var string $field
                 * @var Message\Group $field_messages
                 */
                foreach ($messages as $field => $field_messages) {
                    $errors[] = $field_messages[0]->getMessage();
                    $form_errors[$field] = ' error';
                }

                if(!checkdate($this->request->getPost('month'), $this->request->getPost('day'), $this->request->getPost('year')))
                {
                    $errors[] = 'The birthdate is incorrect';
                    $form_errors['day'] = ' error';
                    $form_errors['month'] = ' error';
                    $form_errors['year'] = ' error';
                }

            } else {
                $register_result = $this->authService->register($credentials);
                if (!$register_result->success()) {
                    $errors[] = $register_result->errorMessage();
                } else {
                    return $this->response->redirect('/?register=user');
                }
            }
        }
        else
        {
            $credentials = [
                'name' => $this->request->getQuery('name'),
                'surname' => $this->request->getQuery('surname'),
                'email' => $this->request->getQuery('email')
            ];
        }

        $this->view->pick('_elements/landing--orange');

        return $this->view->setVars([
            'which_form' => 'up',
            'signupform' => $sign_up_form,
            'errors' => $errors,
            'form_errors' => $form_errors,
            'url_signup' => $url_redirect,
            'credentials' => $credentials
        ]);
    }

    private function configLanding($lottery)
    {
        $translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($this->di->get('session'), $this->di->get('request')))->get(), $this->entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));

        switch($lottery)
        {
            case 'euromillions':
                $lottery='EuroMillions';
                $lotteryTextDays=$translationAdapter->query('Every Friday and Tuesday a new draw to become a millionare');
                break;
            case 'powerball':
                $lottery= 'PowerBall';
                $lotteryTextDays=$translationAdapter->query('Every Thursday and Sunday a new draw to become a millionare');
                break;
            case 'megamillions':
                $lottery= 'MegaMillions';
                $lotteryTextDays=$translationAdapter->query('Every Wednesday and Saturday a new draw to become a millionare');
        }
        $params= $this->getJackpot($lottery);

        $this->view->setVars(['landing_jackpot_milliards' => $params['milliards'],
            'landing_jackpot_trillions'=> $params['trillions'],
            'landing_jackpot_value' => $params['jackpot_value'],
            'landing_show_day'=> $this->getNextDraw($lottery),
            'landing_lottery' => $lottery,
            'landing_lottery_text_days' => $lotteryTextDays]);
    }

    private function getJackpot($lottery)
    {
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot($lottery));
        $numbers = preg_replace('/[A-Z,.]/','',ViewHelper::formatJackpotNoCents($jackpot));
        $letters = preg_replace('/[0-9.,]/','',ViewHelper::formatJackpotNoCents($jackpot));

        return ViewHelper::setSemanticJackpotValue($numbers, $letters, $jackpot, $this->languageService->getLocale());
    }

    private function getNextDraw($lottery)
    {
        $diff= explode(':', (new \DateTime())->diff($this->lotteryService->getNextDateDrawByLottery($lottery)->modify('-1 hours'))->format('%a:%h:%i'));

        return ['days' => $diff[0], 'hours' => $diff[1], 'minutes' => $diff[2]];
    }

    /**
     * @return SignUpForm
     */
    private function getSignUpForm()
    {
        return SiteHelpers::getSignUpForm();
    }

    /**
     * @return array
     */
    private function getErrorsArray()
    {
        $form_errors = [
            'email' => '',
            'password' => '',
            'name' => '',
            'surname' => '',
            'confirm_password' => '',
            'country' => '',
            'card- number' => '',
            'card-holder' => '',
            'card-cvv' => '',
            'new-password' => '',
            'confirm-password' => '',
            'accept' => ''

        ];
        return $form_errors;
    }
}