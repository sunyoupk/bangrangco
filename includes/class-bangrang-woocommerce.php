<?php
/**
 * Created by PhpStorm.
 * User: ace
 * Date: 18/07/2018
 * Time: 5:16 PM
 */

/**
 * Class Bangrang_WooCommerce
 * WooCommerce customization
 */
class Bangrang_WooCommerce {

	private static $scripts = array();

	private static $wp_localize_scripts = array();

	/**
	 * Bangrang_WooCommerce constructor.
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'load_scripts' ) );
		add_action( 'wp_print_scripts', array( __CLASS__, 'localize_printed_scripts' ), 5 );
		add_action( 'wp_print_footer_scripts', array( __CLASS__, 'localize_printed_scripts' ), 5 );
	}

	public static function load_scripts() {
		global $post;

		if ( ! did_action( 'before_woocommerce_init' ) ) {
			return;
		}

		self::register_scripts();
//		self::register_styles();

		if ( is_checkout() ) {
			self::enqueue_script( 'bangrang-checkout' );
		}
	}

	private static function enqueue_script( $handle, $path = '', $deps = array( 'jquery' ), $version = BANGRANG_VERSION, $in_footer = true ) {
		if ( ! in_array( $handle, self::$scripts, true ) && $path ) {
			self::register_script( $handle, $path, $deps, $version, $in_footer );
		}
		wp_enqueue_script( $handle );
	}

	private static function register_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$suffix = '';

		$register_scripts = array(
			'bangrang-checkout' => array(
				'src'     => get_stylesheet_directory_uri() . '/assets/js/bangrang-checkout' . $suffix . '.js',
				'deps'    => array( 'jquery', 'woocommerce', 'wc-country-select', 'wc-address-i18n' ),
				'version' => BANGRANG_VERSION,
			),
		);
		foreach ( $register_scripts as $name => $props ) {
			self::register_script( $name, $props['src'], $props['deps'], $props['version'] );
		}
	}

	private static function register_script( $handle, $path, $deps = array( 'jquery' ), $version = BANGRANG_VERSION, $in_footer = true ) {
		self::$scripts[] = $handle;
		wp_register_script( $handle, $path, $deps, $version, $in_footer );
	}

	public static function localize_printed_scripts() {
		foreach ( self::$scripts as $handle ) {
			self::localize_script( $handle );
		}
	}

	private static function localize_script( $handle ) {
		if ( ! in_array( $handle, self::$wp_localize_scripts, true ) && wp_script_is( $handle ) ) {
			$data = self::get_script_data( $handle );

			if ( ! $data ) {
				return;
			}

			$name                        = str_replace( '-', '_', $handle ) . '_params';
			self::$wp_localize_scripts[] = $handle;
			wp_localize_script( $handle, $name, apply_filters( $name, $data ) );
		}
	}

	private static function get_script_data( $handle ) {
		global $wp;

		switch ( $handle ) {
			case 'bangrang-checkout':
				$params = array(
					'ajax_url'                  => WC()->ajax_url(),
					'wc_ajax_url'               => WC_AJAX::get_endpoint( '%%endpoint%%' ),
					'checkout_url'              => WC_AJAX::get_endpoint( 'checkout' ),
					'is_checkout'               => is_page( wc_get_page_id( 'checkout' ) ) && empty( $wp->query_vars['order-pay'] ) && ! isset( $wp->query_vars['order-received'] ) ? 1 : 0,
					'debug_mode'                => defined( 'WP_DEBUG' ) && WP_DEBUG,
					'i18n_checkout_error'       => esc_attr__( 'Error processing checkout. Please try again.', 'woocommerce' ),
				);
				break;
			default:
				$params = false;
		}

		return apply_filters( 'woocommerce_get_script_data', $params, $handle );
	}
}

return Bangrang_WooCommerce::init();
