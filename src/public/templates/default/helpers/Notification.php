<?
class Zend_View_Helper_Notification {
	public $view;

	/**
	 *
	 * @param string $icon -> info, ask, check, star, alert, alert-red
	 * @param string $text
	 * @param string $type -> yesno, okreload, billing, limit, ok
	 * @param integer $userId
	 * @return string
	 */
	public function Notification ($msgKey, $popup = false, $data = Array())
	{

		/** UserNotification
			* $this->systemClass; $class
			* $this->systemTitle; $title
			* $this->systemText;  $text
		 *
		 * Systemmessage
			* $this->systemIcon; $icon
			* $this->systemType; $type
		 */


		/**

				$title = $this->view->translate('notice_error_label'); //notice_info_label
				$text = $this->view->translate('notice_transkey');
				$icon = 'remove-sign';
				$class = 'danger'; //info //success
				$action = 'yesno  ok okreload';
		 */

		switch($msgKey) {
			case "user_auth_login_error":
				$title = $this->view->translate('notice_error_label');
				$text = $this->view->translate('notice_username_or_password_wrong');
				$icon = 'remove-sign';
				$class = 'danger';
				$type = 'okreload';
				break;


			//case "sendPwdError":
			case "user_auth_forgotpassword_error":
				$title = $this->view->translate('notice_error_label');
				$text = $this->view->translate('notice_send_password_error');
				$icon = 'remove-sign';
				$class = 'danger';
				$type = 'okreload';
				break;

			//case "sendPwd":
			case "user_auth_forgotpassword_success":
				$title = $this->view->translate('notice_info_label');
				$text = $this->view->translate('notice_send_password_success');
				$icon = 'ok-sign';
				$class = 'success';
				$type = 'okreload';
				break;

			//case "sendPwdError":
			case "user_account_changepassword_success":
				$title = $this->view->translate('notice_info_label');
				$text = $this->view->translate('notice_change_password_success');
				$icon = 'ok-sign';
				$class = 'success';
				$type = 'ok';
				break;

			//case "sendPwd":
			case "user_auth_verify_sendmailsuccess":
				$title = $this->view->translate('notice_info_label');
				$text = $this->view->translate('notice_send_verifymail_success');
				$icon = 'ok-sign';
				$class = 'success';
				$type = 'ok';
				break;

			case "notice_email_not_verified":
				$title = $this->view->translate('notice_info_label');
				$text = $this->view->translate('notice_email_not_verified',Array(0 => ' <a class="popup" data-href="' . $this->view->url(Array(),'User_VerifyMail',true) . '"><strong>', 1 => '</strong></a>'));
				$icon = 'ok-sign';
				$class = 'info';
				$type = 'ok';
				break;

			//case "changeMail":
			case "user_auth_changemail_success":
				$title = $this->view->translate('notice_info_label');
				$text = $this->view->translate('notice_change_mail_success');
				$icon = 'ok-sign';
				$class = 'success';
				$type = 'ok';
				break;
			//case "sendPwdError":
			case "user_auth_changemail_error":
				$title = $this->view->translate('notice_error_label');
				$text = $this->view->translate('notice_change_mail_error');
				$icon = 'remove-sign';
				$class = 'danger';
				$type = 'okreload';
				break;

			//case
			case "play_index_index_empty_lines":
				$title = $this->view->translate('notice_error_label');
				$text = $this->view->translate('notice_no_lines_selected');
				$icon = 'info-sign';
				$class = 'danger';
				$type = 'ok';
				break;

			//case
			case "play_index_index_finalize_line":
				$title = $this->view->translate('notice_error_label');
				$text = $this->view->translate('notice_some_lines_empty');
				$icon = 'info-sign';
				$class = 'danger';
				$type = 'ok';
				break;

			case "error_no_biller_selected":
				$title = $this->view->translate('notice_info_label');
				$text = $this->view->translate('notice_please_select_biller');
				$icon = 'info-sign';
				$class = 'info';
				$type = 'ok';
				break;

			case "info_wrong_payin_amount":
				$title = $this->view->translate('notice_info_label');
				$text = $this->view->translate('notice_wrong_payin_amount', array($data['min'],$data['max']));
				$icon = 'info-sign';
				$class = 'info';
				$type = 'ok';
				break;

			case "info_ticket_sale_closed":
				$title = $this->view->translate('notice_info_label');
				$text = $this->view->translate('notice_ticket_sale_closed_buy_next');
				$icon = 'info-sign';
				$class = 'info';
				$type = 'ok';
				break;

			case "notice_no_payout_account_added":
				$title = $this->view->translate('notice_info_label');
				$text = $this->view->translate('notice_no_payout_account_added',array('<a class="popup" data-href="' . $this->view->url(Array(),'Billing_Payout_Account',true) . '">', '</a>'));
				$icon = 'info-sign';
				$class = 'info';
				$type = 'ok';
				break;

			case "billing_payout_account_added_successfull":
				$title = $this->view->translate('notice_info_label');
				$text = $this->view->translate('notice_billing_payout_account_added_successfull');
				$icon = 'info-sign';
				$class = 'info';
				$type = 'okreload';
				break;

			case "notice_payout_waiting_for_approval":
				$title = $this->view->translate('notice_info_label');
				$text = $this->view->translate('notice_payout_waiting_for_approval');
				$icon = 'info-sign';
				$class = 'info';
				$type = 'ok';
				break;

			case "notice_payout_added_successfull":
				$title = $this->view->translate('notice_info_label');
				$text = $this->view->translate('notice_payout_added_successfull');
				$icon = 'info-sign';
				$class = 'info';
				$type = 'ok';
				break;

			case "contact_submit_successfull":
				$title = $this->view->translate('notice_info_label');
				$text = $this->view->translate('notice_contact_submit_successfull');
				$icon = 'ok-sign';
				$class = 'info';
				$type = 'okclose';
				break;

			case "question_delete_bank_account":
				$title = $this->view->translate('notice_info_label');
				$text = $this->view->translate('question_delete_bank_account');
				$icon = 'remove-sign';
				$class = 'danger';
				$type = 'yesno';
				$json = json_encode($data);
				break;

			case "notice_delete_bank_account_ok":
				$title = $this->view->translate('notice_info_label');
				$text = $this->view->translate('notice_delete_bank_account_ok');
				$icon = 'ok-sign';
				$class = 'info';
				$type = 'okreload';
				$json = json_encode($data);
				break;
			case "error_payout_account_exist":
				$title = $this->view->translate('notice_error_label');
				$text = $this->view->translate('notice_error_payout_bank_account_exist');
				$icon = 'remove-sign';
				$class = 'danger';
				$type = 'ok';
				break;

			case "notice_data_updated_successfull":
				$title = '';
				$text = $this->view->translate('notice_data_updated_successfull');
				$icon = 'ok-sign';
				$class = 'success';
				$type = 'okreload';
				break;
			case "notice_permission_denied":
				$title = '';
				$text = $this->view->translate('notice_permission_denied');
				$icon = 'remove-sign';
				$class = 'danger';
				$type = 'okreload';
				break;
			case "error":
			default:
				$title = $this->view->translate('notice_error_label');
				$text = $this->view->translate('notice_not_specified_error');
				$icon = 'remove-sign';
				$class = 'danger';
				$type = 'okreload';
				break;
		}

		$this->view->systemTitle	= $title;
		$this->view->systemText		= $text;
		$this->view->systemIcon		= $icon;
		$this->view->systemClass	= $class;
		$this->view->systemType		= $type;
		if(isset($json)){
			$this->view->json		= $json;
		}




		if ($popup){
			$c = $this->view->render("global/systemmessage.phtml");
		} else {
			$c = $this->view->render("global/usernotification.phtml");
		}

		return $c;
		}

	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}
}