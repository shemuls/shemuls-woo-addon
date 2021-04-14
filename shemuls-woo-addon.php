<?php
/*
Plugin Name: Shemuls Woo Addon
Plugin URI: http://www.shemuls.com
Description: The most unique toolkit for Elementor for creating woocommerce design.
Version: 1.1.1
Requires PHP: 5.6
Author: shemuls.com
Author URI: http://www.shemuls.com
Text Domain: shemuls-woo-addon
Domain Path: /languages
License: GPL-3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.txt
Elementor tested up to: 3.1.4
Elementor Pro tested up to: 3.2.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

final class Shemuls_Woo_Addon {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.5.11';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '6.0';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * The single instance of the class.
	 */
	protected static $instance = null;

	public static function get_instance() {
		if ( ! isset( static::$instance ) ) {
			static::$instance = new static;
		}

		return static::$instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */

	protected function __construct() {
		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}
		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}

		require_once('widgets/s-product-slider.php');
		require_once('widgets/s-product-archive.php');
        
		// Register Widget
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

		// Register Widget Styles
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_styles' ] );
	}

	public function register_widgets() {

		if ( class_exists( 'WooCommerce' ) ) {
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\S_Product_Slider() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\S_Product_Archive() );
		}
	}

	public function widget_styles() {
		wp_enqueue_style( 'plugin-name-stylesheet', plugins_url( '/css/shemuls-woo-addon.css', __FILE__ ) );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
		/* 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'shemuls-woo-addon' ),
			'<strong>' . esc_html__( 'Shemuls Woo Addon', 'shemuls-woo-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'shemuls-woo-addon' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
		/* 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'shemuls-woo-addon' ),
			'<strong>' . esc_html__( 'Shemuls Woo Addon', 'shemuls-woo-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'shemuls-woo-addon' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
		/* 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'shemuls-woo-addon' ),
			'<strong>' . esc_html__( 'Shemuls Woo Addon', 'shemuls-woo-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'shemuls-woo-addon' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

}

add_action( 'init', 'my_elementor_init' );
function my_elementor_init() {
	Shemuls_Woo_Addon::get_instance();
}