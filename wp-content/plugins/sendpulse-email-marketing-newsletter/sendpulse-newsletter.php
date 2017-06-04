<?php
/*
	Plugin Name: SendPulse Email Marketing Newsletter
	Plugin URI: https://wordpress.org/plugins/sendpulse-email-marketing-newsletter/
	Description: Add e-mail subscription form, send marketing newsletters and create autoresponders.
	Version: 1.2.0
	Author: SendPulse
	Author URI: https://sendpulse.com
	License:     GPL2
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
	Text Domain: sendpulse-newsletter
	Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require 'vendor/autoload.php';

/**
 * Main plugin class.
 *
 * Class Send_Pulse_Newsletter
 */
class Send_Pulse_Newsletter {

	/**
	 * @var string Plugin version
	 */
	private $version = '1.2.0';

	/**
	 * @var string Plugin url. Useful for enqueue assets.
	 */
	protected $plugin_url;


	/**
	 * Send_Pulse_Newsletter constructor.
	 */
	public function __construct() {

		$this->plugin_url = plugins_url( '/', __FILE__ );

		// For compatibility php <= 5.6 array()

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );


		if ( $this->check_requirements() ) {

			$this->inc();

			// For compatibility php <= 5.6 array()

			add_action( 'wp_enqueue_scripts', array( $this, 'front_assets' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );

		}





	}

	/**
	 * Include libraries and additional class.
	 */
	protected function inc() {

		//Autoload don't work
		include_once ('vendor/wensleydale/sendpulse-rest-api-php/src/sendpulseInterface.php');
		include_once ('vendor/wensleydale/sendpulse-rest-api-php/src/sendpulse.php');

		include_once( 'inc/class-sendpulse-newsletter-api.php' );
		include_once( 'inc/class-sendpulse-newsletter-settings.php' );
		include_once( 'inc/class-senpulse-newsletter-shortcodes.php' );
		include_once( 'inc/class-sendpulse-newsletter-ajax.php' );
		include_once( 'inc/class-sendpulse-newsletter-users.php' );

	}

	/**
	 * Enqueue assets.
	 */
	public function front_assets() {

		// For compatibility php <= 5.6 array()

		$prefix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG )? '' : '.min';

		wp_enqueue_style('sp-style', $this->plugin_url . "assets/css/style{$prefix}.css", array(), $this->version );

		wp_enqueue_script('sp-script', $this->plugin_url . "assets/js/script{$prefix}.js", array('jquery'), $this->version, true);

		wp_add_inline_style('sp-style', Send_Pulse_Newsletter_Settings::get_option('custom_css', 'sp_visual_setting') );

		$data = array(
			'ajax_url' 		    => admin_url( 'admin-ajax.php' )
		);

		wp_localize_script( 'sp-script', 'sp_params', $data );
	}

	public function admin_assets() {
		// For compatibility php <= 5.6 array()

		$prefix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG )? '' : '.min';

		wp_enqueue_style('sp-admin-style', $this->plugin_url . "assets/css/admin{$prefix}.css", array(), $this->version );

		wp_enqueue_script('sp-admin-script', $this->plugin_url . "assets/js/admin{$prefix}.js", array('jquery'), $this->version, true);

		$data = array(
			'ajax_url' 		    => admin_url( 'admin-ajax.php' )
		);

		wp_localize_script( 'sp-admin-script', 'sp_admin_params', $data );

	}

	function load_textdomain() {
		load_plugin_textdomain( 'sendpulse-newsletter', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	/**
	 *
	 * @return bool Is check requirements?
	 */
	protected function check_requirements() {

		include_once( 'inc/class-senpulse-newsletter-requirement.php' );

		$requirement = new Send_Pulse_Newsletter_Requirement();

		return $requirement->is_success();

	}

}

new Send_Pulse_Newsletter();