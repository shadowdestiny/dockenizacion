<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 3/10/18
 * Time: 10:52
 */

namespace EuroMillions\web\controllers;

use Captcha\Captcha;
use EuroMillions\shared\helpers\HeaderControllerTrait;
use EuroMillions\shared\helpers\SiteHelpers;
use EuroMillions\shared\services\SiteConfigService;
use EuroMillions\web\components\ReCaptchaWrapper;
use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\components\TrackingCodesHelper;
use EuroMillions\web\components\ViewHelper;
use EuroMillions\web\forms\ForgotPasswordForm;
use EuroMillions\web\forms\ResetPasswordForm;
use EuroMillions\web\forms\SignInForm;
use EuroMillions\web\forms\SignUpForm;
use EuroMillions\web\services\AuthService;
use EuroMillions\web\services\CartService;
use EuroMillions\web\services\ChristmasService;
use EuroMillions\web\services\CurrencyConversionService;
use EuroMillions\web\services\CurrencyService;
use EuroMillions\web\services\GeoService;
use EuroMillions\web\services\LanguageService;
use EuroMillions\web\services\LotteryService;
use EuroMillions\web\services\TransactionService;
use EuroMillions\web\services\UserPreferencesService;
use EuroMillions\web\services\UserService;
use EuroMillions\web\vo\Email;
use EuroMillions\shared\vo\results\ActionResult;
use Phalcon\Http\Client\Provider\Curl;
use Phalcon\Validation\Message;


class LandingsController extends PublicSiteControllerBase
{
  use HeaderControllerTrait;

  /** @var  GeoService */
  protected $geoService;
  /** @var LotteryService */
  protected $lotteryService;
  /** @var  LanguageService */
  protected $languageService;
  /** @var  CurrencyService */
  protected $currencyService;
  /** @var  UserService */
  protected $userService;
  /** @var  AuthService */
  protected $authService;
  /** @var  UserPreferencesService */
  protected $userPreferencesService;
  /** @var  SiteConfigService $siteConfigService */
  protected $siteConfigService;
  /** @var  CartService $cartService */
  protected $cartService;
  /** @var  CurrencyConversionService */
  protected $currencyConversionService;
  /** @var  TransactionService */
  protected $transactionService;
  /** @var  ChristmasService */
  protected $christmasService;

  const IP_DEFAULT = '127.0.0.1';
  public function initialize(AuthService $authService = null, GeoService $geoService = null, LanguageService $languageService = null)
  {
      parent::initialize();
      $this->authService = $authService ? $authService : $this->domainServiceFactory->getAuthService();
      $this->geoService = $geoService ? $geoService : $this->domainServiceFactory->getServiceFactory()->getGeoService();
      $this->languageService = $languageService ? $languageService : $this->domainServiceFactory->getLanguageService();
  }

  public function afterExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
  {
      $this->setTopNavValues();
  }

  public function setTopNavValues()
  {
      $this->setValues($this->domainServiceFactory);
      $this->view->setVar('user_language', $this->userLanguage);
      $this->view->setVar('current_currency', $this->currentCurrency);
      $this->view->setVar('user_logged', $this->isLogged);
      $this->view->setVar('user_currency', $this->userCurrency);
      $this->view->setVar('user_currency_code', $this->userCurrencyCode);
      $this->view->setVar('currencies', $this->currencies);
      $this->view->setVar('currency_list', $this->currencyList);
      $this->view->setVar('active_languages', $this->activeLanguages);
  }

  public function signInAction()
  {

      $errors = [];
      $sign_in_form = new SignInForm();
      $form_errors = $this->getErrorsArray();
      $sign_up_form = $this->getSignUpForm();

      $url_redirect = $this->session->get('original_referer');

      if ($this->request->isPost()) {
          if ($sign_in_form->isValid($this->request->getPost()) === false) {
              $messages = $sign_in_form->getMessages(true);
              /**
               * @var string $field
               * @var Message\Group $field_messages
               */
              foreach ($messages as $field => $field_messages) {
                  $errors[] = $field_messages[0]->getMessage();
                  $form_errors[$field] = ' error';
              }
          } else {
              $userCheck = $this->authService->check([
                  'email' => $this->request->getPost('email'),
                  'password' => $this->request->getPost('password'),
                  'remember' => $this->request->getPost('remember'),
                  'ipaddress' => !empty($this->request->getClientAddress(true)) ? $this->request->getClientAddress(true) : self::IP_DEFAULT,
              ], 'string');

              if (!$userCheck['bool']) {
                  if ($userCheck['error'] == 'disabledUser') {
                      $errors[] = $this->languageService->translate('signin_msg_closed');
                  } else {
                      $errors[] = 'Incorrect email or password.';
                  }
              } else {
                  return $this->response->redirect('/');
              }
          }
      }

      $this->view->pick('sign-in/index');
      $this->tag->prependTitle($this->languageService->translate('signintag_name'));
      MetaDescriptionTag::setDescription($this->languageService->translate('signintag_desc'));


      return $this->view->setVars([
          'which_form' => 'in',
          'signinform' => $sign_in_form,
          'signupform' => $sign_up_form,
          'errors' => $errors,
          'currency_list' => [],
          'form_errors_login' => $form_errors,
          'time_to_remain_draw' => false,
          'last_minute' => false,
          'draw_date' => '',
          'canonical' => 'canonical_signin',
          'timeout_to_closing_modal' => 30 * 60 * 1000,
          'minutes_to_close' => false,
          'form_errors' => $this->getErrorsArray(),
          'pageController' => 'signin'
      ]);
  }

  public function signUpAction()
  {
      $errors = [];
      $sign_in_form = new SignInForm();
      $form_errors = $this->getErrorsArray();
      $sign_up_form = $this->getSignUpForm();
      $url_redirect = $this->session->get('original_referer');

      if ($this->request->isPost()) {
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

              $register_result = $this->authService->register($credentials);

              if (!$register_result->success()) {
                  $errors[] = $register_result->errorMessage();
              } else {
                  return $this->response->redirect('/?register=user');
              }
          }
      }

      //$this->view->pick('sign-in/sign-up');  -> Deleted. If not charge all sign-up page instead the landing
      $this->tag->prependTitle($this->languageService->translate('signuptag_name'));
      MetaDescriptionTag::setDescription($this->languageService->translate('signuptag_desc'));
      return $this->view->setVars([
          'which_form' => 'up',
          'signinform' => $sign_in_form,
          'signupform' => $sign_up_form,
          'errors' => $errors,
          'form_errors_login' => $this->getErrorsArray(),
          'currency_list' => [],
          'time_to_remain_draw' => false,
          'last_minute' => false,
          'draw_date' => '',
          'canonical' => 'canonical_signup',
          'timeout_to_closing_modal' => 30 * 60 * 1000,
          'minutes_to_close' => false,
          'form_errors' => $form_errors,
          'pageController' => 'signup'
      ]);
  }

  public function validateAction($token)
  {
      $result = $this->authService->validateEmailToken($token);
      if ($result->success()) {
          $this->authService->confirmUser($token);
          return $this->response->redirect('/');
      } else {
          $message = 'Sorry, the token you used is no longer valid. (message was: "' . $result->getValues() . '""). Click here to request a new one.'; //EMTD click here has to work and send a new token to the user.
      }
      $this->view->setVar('message', $message);
  }

  public function resendTokenAction()
  {
      if($this->authService->isLogged())
      {
          $this->authService->resendToken();
          return $this->response->redirect('/');
      }
  }

  public function logoutAction()
  {
      $this->authService->logout();
      $this->response->redirect('/');
  }


  public function forgotPasswordAction()
  {
      $errors = [];
      $forgot_password_form = new ForgotPasswordForm();
      $message = '';

      //get captcha instance
      $config = $this->di->get('config')['recaptcha'];
      $captcha = new ReCaptchaWrapper(new Captcha());
      $captcha->getCaptcha()->setPublicKey($config['public_key']);
      $captcha->getCaptcha()->setPrivateKey($config['private_key']);

      if ($this->request->isPost()) {
          if ($forgot_password_form->isValid($this->request->getPost()) == false) {
              $errors[] = 'Invalid email address. Please verify what you have inserted and try again.';
          } else {
              $email = $this->request->getPost('email');
              $reCaptchaResult = $captcha->check()->isValid();
              $result = new ActionResult(false);
              if (!empty($email) && !empty($reCaptchaResult)) {
                  $result = $this->authService->forgotPassword(new Email($email));
              }
              if ($result->success() && $reCaptchaResult) {
                  $message = $result->getValues();
              } else {
                  if (empty($reCaptchaResult)) $errors[] = 'You are a robot... or you forgot to check the Captcha verification.';
                  $errors[] = $result->errorMessage();
              }
          }
      }

      $this->tag->prependTitle($this->languageService->translate('forgotpw_name'));
      MetaDescriptionTag::setDescription($this->languageService->translate('forgotpw_desc'));

      $this->view->pick('sign-in/forgot-psw');
      return $this->view->setVars([
          'forgot_password_form' => $forgot_password_form,
          'captcha' => $captcha->html(),
          'errors' => $errors,
          'currency_list' => [],
          'message' => $message,
          'pageController' => 'forgotpsw'
      ]);
  }

  public function passwordResetAction($token)
  {
      $result = $this->authService->resetPassword($token);
      $form_errors = $this->getErrorsArray();
      $myaccount_passwordchange_form = new ResetPasswordForm();
      if ($result->success()) {
          //$message = 'Your password was reset!';
          $this->view->pick('recovery/index');
          return $this->view->setVars([
              'currency_list' => [],
              'token' => $token,
              'message' => false,
              'errors' => [],
              'reset_password_form' => $myaccount_passwordchange_form,
              'form_errors' => $form_errors,
          ]);
      } else {
          //EMTD redirect
          //$message = 'Sorry, the token you used is no longer valid.';
      }
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

    public function mainAction()
    {
        $this->view->pick('_elements/landing--blue');
    }

    public function mainEMAction()
    {
        $this->view->pick('_elements/landing--blue--EM');
    }

    public function mainPBAction()
    {
        $this->view->pick('_elements/landing--blue--PB');
    }

    public function mainMMAction()
    {
        $this->view->pick('_elements/landing--blue--MM');
    }

    public function mainorangeAction()
    {
        $this->view->pick('_elements/landing--orange');
        $this->signUpAction();
    }

    public function mainorangeEMAction()
    {
        $this->view->pick('_elements/landing--orange--EM');
        $this->signUpAction();
    }

    public function mainorangePBAction()
    {
        $this->view->pick('_elements/landing--orange--PB');
        $this->signUpAction();
    }

    public function mainorangeMMAction()
    {
        $this->view->pick('_elements/landing--orange--MM');
        $this->signUpAction();
    }



}
