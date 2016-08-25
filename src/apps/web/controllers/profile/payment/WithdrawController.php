<?php


namespace EuroMillions\web\controllers\profile\payment;


use EuroMillions\web\controllers\AccountController;
use EuroMillions\web\entities\User;
use EuroMillions\web\forms\BankAccountForm;
use EuroMillions\web\forms\CreditCardForm;
use EuroMillions\web\vo\dto\UserDTO;
use Money\Currency;

class WithdrawController extends AccountController
{

    /**
     * @return \Phalcon\Mvc\View
     */
    public function withDrawAction()
    {
        $errors = [];
        $user_id = $this->authService->getCurrentUser();
        $form_errors = $this->getErrorsArray();
        /** @var User $user */
        $user = $this->userService->getUser($user_id->getId());
        $credit_card_form = new CreditCardForm();
        $countries = $this->getCountries();
        $credit_card_form = $this->appendElementToAForm($credit_card_form);
        $bank_account_form = new BankAccountForm($user, ['countries' => $countries] );
        $site_config_dto = $this->siteConfigService->getSiteConfigDTO($user->getUserCurrency(), $user->getLocale());
        $symbol = $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'];
        $ratio = $this->currencyConversionService->getRatio(new Currency('EUR'), $user->getUserCurrency());

        if($this->request->isPost()) {
            if ($bank_account_form->isValid($this->request->getPost()) == false) {
                $messages = $bank_account_form->getMessages(true);
                /**
                 * @var string $field
                 * @var Message\Group $field_messages
                 */
                foreach ($messages as $field => $field_messages) {
                    $errors[] = $field_messages[0]->getMessage();
                    $form_errors[$field] = ' error';
                }
            }else {
                $result = $this->userService->createWithDraw($user, [
                    'bank-name' => $this->request->getPost('bank-name'),
                    'bank-account' => $this->request->getPost('bank-account'),
                    'bank-swift' => $this->request->getPost('bank-swift'),
                    'amount' => $this->request->getPost('amount')
                ]);
                if($result->success()){
                    $user = $this->userService->getUser($user_id->getId());
                    $bank_account_form = new BankAccountForm($user, ['countries' => $countries] );
                    $entity = $bank_account_form->getEntity();
                    $entity->amount = '';
                    $msg = $result->getValues();
                }else{
                    $errors[] = $result->errorMessage();
                }
            }
        }
        $wallet_dto = $this->domainServiceFactory->getWalletService()->getWalletDTO($user);
        $this->view->pick('account/wallet');
        $this->tag->prependTitle('Request a Withdrawal');
        return $this->view->setVars([
            'which_form' => 'withdraw',
            'form_errors' => $form_errors,
            'bank_account_form' => $bank_account_form,
            'user' => new UserDTO($user),
            'errors' => empty($errors) ? [] : $errors,
            'msg' => empty($msg) ? '' : $msg,
            'symbol' => $symbol,
            'ratio' => $ratio,
            'credit_card_form' => $credit_card_form,
            'show_form_add_fund' => false,
            'wallet' => $wallet_dto,
            'show_box_basic' => false,
            'site_config' => $site_config_dto
        ]);

    }

}