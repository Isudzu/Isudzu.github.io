<?php

/**
 * Register and render plugins shortcodes
 *
 * Class Send_Pulse_Newsletter_Shortcodes
 */
class Send_Pulse_Newsletter_Shortcodes {

	/**
	 * SP_Shortcodes constructor.
	 */
	public function __construct() {

		add_action('init', [$this, 'init']);
	}

	/**
	 * Init action
	 */
	public function init() {
		add_shortcode( 'sendpulse-form',  [ $this, 'subscribe_form' ]  );
	}

	/**
     * Generate subscribe form shortcode
     *
	 * @return string Subscribe form html.
	 */
	public function subscribe_form() {
		$is_name = Send_Pulse_Newsletter_Settings::get_option('is_name','sp_api_setting');
	    ob_start();
	    ?>
			<form class="sp-form js-sp-form">
				<div class="sp-form__message"></div>
				<input type="email" name="email" class="sp-form__input sp-form__email" placeholder="<?php _e('Email', 'sendpulse-newsletter'); ?>" required="required">

            <?php if ('on' == $is_name) { ?>
                <input type="text" name="name" class="sp-form__input sp-form__name" placeholder="<?php _e('Name', 'sendpulse-newsletter'); ?>" >
            <?php } ?>

				<input type="submit" class="sp-form__input sp-form__submit" value="<?php _e('Subscribe', 'sendpulse-newsletter'); ?>">
				<input type="hidden" name="action" value="sendpulse_subscribe" >
				<?php wp_nonce_field('sendpulse_subscribe'); ?>
			</form>
	<?php

        $output  = ob_get_clean();

	return apply_filters( 'sendpulse_subscribe_form', $output);
	}


}

new Send_Pulse_Newsletter_Shortcodes();