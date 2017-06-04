<?php

/**
 * Handle ajax actions.
 *
 * Class Send_Pulse_Newsletter_Ajax
 */
class Send_Pulse_Newsletter_Ajax {

	/**
	 * @var array List supported ajax actions.
	 */
	protected $actions = ['subscribe'];

	/**
	 * Register ajax actions for logged and un-logged user.
	 *
	 * Send_Pulse_Newsletter_Ajax constructor.
	 */
	public function __construct() {

		foreach ($this->actions as $action ) {
			add_action('wp_ajax_nopriv_sendpulse_' . $action, [$this, $action ] );
			add_action('wp_ajax_sendpulse_' . $action, [$this, $action ] );
		}

		add_action('wp_ajax_sendpulse_import', [$this, 'import']);

	}

	/**
	 * Handle subscribe ajax action.
	 */
	public function subscribe() {
		check_ajax_referer('sendpulse_subscribe');

		$email = isset($_POST['email']) ? sanitize_email( $_POST['email'] ) : '';
		$name = isset($_POST['name']) ? sanitize_text_field( $_POST['name'] ) : '';

		try {

			// Email is required.

			if ( empty($email) ) {
				throw new Exception(__('Please, enter email', 'sendpulse-newsletter'));
			}

			// Validation email.

			if (!is_email($email)) {
				throw new Exception(__('Please, check email', 'sendpulse-newsletter'));
			}

			$api = new Send_Pulse_Newsletter_API();

			$emails = array(
				array(
					'email' => $email,
					'variables' => array(
						'name' => $name
					)
				)
			);

			$result = $api->addEmails( $api->default_book, $emails );

			if (isset($result->is_error) && $result->is_error) {
				$msg = isset($result->message) ? $result->message : __('Something went wrong', 'sendpulse-newsletter');
				throw new Exception( $msg );
			}

			wp_send_json_success(['msg' => __('You subscribe successfully. Thanks!', 'sendpulse-newsletter')]);

		} catch (Exception $e) {

			wp_send_json_error(['msg' => $e->getMessage() ]);

		}


	}

	/**
	 * Handle import ajax action.
	 */
	public function import() {

		check_ajax_referer('sendpulse_import');

		$book = isset($_POST['book']) ? sanitize_text_field( $_POST['book'] ) : '';
		$role = isset($_POST['role']) ? sanitize_text_field( $_POST['role'] ) : '';

		$msg = []; // log emulation

		if ( empty($book) ) {
			$msg[] = (__('Please, select Address Book', 'sendpulse-newsletter'));
		}

		if (empty($role)) {
			$msg[] = (__('Please, select Users Role', 'sendpulse-newsletter'));
		}

		if ( empty($msg) ) {

			$msg[] = current_time('mysql'). ' ' . __('Import start', 'sendpulse-newsletter');


			$api = new Send_Pulse_Newsletter_API();

			$emails = [];

			$users = get_users([
				'role' => $role
				]
			);


			foreach ($users as $user ) {
				$emails[] = [
					'email' => $user->user_email,
					'variables' => [
						'name' => $user->display_name
					]
				];

				$msg[] = sprintf('%s: %s %s',__('Add user', 'sendpulse-newsletter'), $user->user_email, $user->display_name );
			}

			$result = $api->addEmails( $book, $emails );

			if (isset($result->is_error) && $result->is_error) {
				$msg[] = isset( $result->message ) ? $result->message : __( 'Something went wrong. Import unsuccessful', 'sendpulse-newsletter' );
			}


			$msg[] = current_time('mysql') . ' ' . __('Import finished', 'sendpulse-newsletter');

		}

		wp_send_json_success(['msg' => implode("\n", $msg )]);


	}

}

new Send_Pulse_Newsletter_Ajax();