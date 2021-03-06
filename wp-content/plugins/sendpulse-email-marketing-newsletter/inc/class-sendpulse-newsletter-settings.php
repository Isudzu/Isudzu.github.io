<?php


/**
 * Plugin settings class
 *
 * Class Send_Pulse_Newsletter
 */
class Send_Pulse_Newsletter_Settings {

	/**
	 * @var WeDevs_Settings_API Instance helper library
	 */
	private $settings_api;

	/**
	 * @var string Error message
	 */
	private $error = '';

	/**
	 * @var null|Send_Pulse_Newsletter_API Instance SP_API class
	 */
	private $api = null;

	/**
	 * @var string Plugin page slug
	 */
	private $page = 'send_pulse_settings';

	/**
	 * Send_Pulse_Newsletter constructor.
	 */
	public function __construct() {
		$this->settings_api = new WeDevs_Settings_API;





		add_action( 'admin_init', array($this, 'admin_init') );
		add_action( 'admin_menu', array($this, 'admin_menu') );

		add_action( 'wsa_form_top_sp_import_setting', array($this, 'start_import_controls'));

		add_action( 'wsa_form_bottom_sp_import_setting', array($this, 'end_import_controls'));

	}

	/**
	 * Init SendPulse API Class
	 */
	protected function init_api() {
		try {
			$this->api = new Send_Pulse_Newsletter_API();

			if ( empty($this->api->default_book) ) {
				$this->error = __('Select default book and save options', 'sendpulse-newsletter');
			}

		} catch (Exception $exception ) {
			$this->error = $exception->getMessage();
		}
	}

	/**
	 * Display settings.
	 */
	function admin_init() {

		$this->init_api();

		//set the settings
		$this->settings_api->set_sections( $this->get_settings_sections() );
		$this->settings_api->set_fields( $this->get_settings_fields() );
		//initialize settings
		$this->settings_api->admin_init();

		if ($this->error
		    && isset($_GET['page'])
		    && ($this->page == $_GET['page'])
		) {
			add_settings_error('general', 'settings_updated', $this->error, 'error');
		}
	}

	/**
	 * Add submenu in Settings
	 */
	function admin_menu() {
		add_options_page( 'Sendpulse', 'Sendpulse', 'delete_posts', 'send_pulse_settings', array($this, 'plugin_page') );
	}

	/**
	 * @return array Section
	 */
	function get_settings_sections() {
		$sections = array(
			array(
				'id'    => 'sp_api_setting',
				'title' => __( 'API Settings', 'sendpulse-newsletter' )
			),
			array(
				'id'    => 'sp_import_setting',
				'title' => __( 'Import', 'sendpulse-newsletter' )
			),
			array(
				'id'    => 'sp_visual_setting',
				'title' => __( 'Visual appearance', 'sendpulse-newsletter' )
			)
		);
		return $sections;
	}
	/**
	 * Returns all the settings fields
	 *
	 * @return array settings fields
	 */
	function get_settings_fields() {
		$settings_fields = array(
			'sp_api_setting' => array(
				array(
					'name'              => 'client_id',
					'label'             => __( 'Client ID', 'sendpulse-newsletter' ),
					'desc'              => __( 'Get from https://login.sendpulse.com/settings/', 'sendpulse-newsletter' ),
					'placeholder'       => __( 'Client ID', 'sendpulse-newsletter' ),
					'type'              => 'text',
					'default'           => '',
					'sanitize_callback' => 'sanitize_text_field'
				),
				array(
					'name'              => 'client_secret',
					'label'             => __( 'Client Secret', 'sendpulse-newsletter' ),
					'desc'              => __( 'Get from https://login.sendpulse.com/settings/', 'sendpulse-newsletter' ),
					'placeholder'       => __( 'Client Secret', 'sendpulse-newsletter' ),
					'type'              => 'text',
					'default'           => '',
					'sanitize_callback' => 'sanitize_text_field'
				)

			)
		);

		// Add customer address book list

		$books = $this->get_lists_address_book();

		if ( !empty($books) ) {
			$options = array_combine(wp_list_pluck($books, 'id'), wp_list_pluck($books, 'name'));

			$settings_fields['sp_api_setting'][] = 	array(
				'name'    => 'default_book',
				'label'   => __( 'Default Address Book', 'sendpulse-newsletter' ),
				'desc'    => __( 'Address Book for new subscribers', 'sendpulse-newsletter' ),
				'type'    => 'select',
				'default' => '',
				'options' => $options
			);
		}

		$settings_fields['sp_api_setting'][] =
			array(
				'name'  => 'is_name',
				'label' => __( 'Show field "Name" in form?', 'sendpulse-newsletter' ),
				'desc'  => __( 'Optional field in subscribe form', 'sendpulse-newsletter' ),
				'type'  => 'checkbox'
			);

		$settings_fields['sp_api_setting'][] =
			array(
				'name'  => 'is_subscribe_after_register',
				'label' => __( 'Subscribe user after register?', 'sendpulse-newsletter' ),
				'desc'  => __( 'Add all new Wordpress users to selected list', 'sendpulse-newsletter' ),
				'type'  => 'checkbox'
			);


		if ( !empty($books) ) {

			$editable_roles = array_reverse( get_editable_roles() );

			$role_options = array();

			foreach ( $editable_roles as $role => $details ) {
				$role_options[ $role ] = translate_user_role($details['name'] );
			}


			$settings_fields['sp_import_setting'] = array(
				array(
					'name'    => 'import_to_book',
					'label'   => __( 'Import to Address Book', 'sendpulse-newsletter' ),
					'desc'    => __( 'Address Book for Wordpress users import', 'sendpulse-newsletter' ),
					'type'    => 'select',
					'default' => '',
					'options' => $options
				),
				array(
					'name'    => 'import_users_group',
					'label'   => __( 'Import Users Group', 'sendpulse-newsletter' ),
					'desc'    => __( 'Users Group that will be imported', 'sendpulse-newsletter' ),
					'type'    => 'select',
					'default' => '',
					'options' => $role_options
				)

			);
		}

		// Selector currently used on front

		$css_default_selector = array('.sp-form', '.sp-form.loading::before', '.sp-form__message', '.sp-form__message.error', '.sp-form__input', '.sp-form__submit', '.sp-form__submit:hover');

		$glue = " {}\n";

		$settings_fields['sp_visual_setting'] = array(
			array(
				'name'              => 'custom_css',
				'label'             => __( 'Custom CSS', 'sendpulse-newsletter' ),
				'desc'              => __( 'Add custom CSS', 'sendpulse-newsletter' ),
				'type'              => 'textarea',
				'default'           => implode($glue, $css_default_selector ) . " {}"
			),
		);






		return $settings_fields;
	}

	/**
	 * Display setting page
	 */
	function plugin_page() {
		echo '<div class="wrap">';
		$this->settings_api->show_navigation();
		$this->settings_api->show_forms();
		echo '</div>';
	}


	/**
	 * Get the value of a settings field
	 *
	 * @param string $option settings field name
	 * @param string $section the section name this field belongs to
	 * @param string $default default text if it's not found
	 *
	 * @return mixed
	 */

	public static function get_option( $option, $section, $default = '' ) {

		$options = get_option( $section );

		if ( isset( $options[$option] ) ) {
			return $options[$option];
		}

		return $default;
	}

	// TODO: Transient or cashing; prevent get from api list

	/**
	 * Get customer address books list
	 *
	 * @return array Address books list
	 */
	protected function get_lists_address_book() {
		return ($this->api) ?  $this->api->listAddressBooks() : array();
	}

	public function start_import_controls() { ?>
		<div class="sp-import-controls">
	<?php }

	public function end_import_controls() {

		echo get_submit_button(__('Start import', 'sendpulse-newsletter'), 'primary large', 'sp-import', true, array('data-_ajax_nonce' => wp_create_nonce('sendpulse_import'), 'data-action' => 'sendpulse_import')); ?>

		<textarea rows="5" cols="55" class="sp-import-log"  id="sp-import-log" title="<?php _e('Import Log', 'sendpulse-newsletter'); ?>"></textarea>
		</div>

	<?php }

}

new Send_Pulse_Newsletter_Settings();