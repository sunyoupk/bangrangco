<?php
/**
 * Class Bangrang_WooCommerce
 * WooCommerce customization
 */
class Bangrang_WooCommerce {

	private static $scripts = array();

	private static $wp_localize_scripts = array();

	/**
	 * Hook Bangrang_WooCommerce.
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'load_scripts' ) );
		add_action( 'wp_print_scripts', array( __CLASS__, 'localize_printed_scripts' ), 5 );
		add_action( 'wp_print_footer_scripts', array( __CLASS__, 'localize_printed_scripts' ), 5 );
	}

	public static function load_scripts() {
		if ( ! did_action( 'before_woocommerce_init' ) ) {
			return;
		}
		self::register_scripts();

		// 조건에 따라 추가된 스크립트를 enqueue 해줄 수 있다.
        // Enqueue something scripts.
	}

	public static function localize_printed_scripts() {
		foreach ( self::$scripts as $handle ) {
			self::localize_script( $handle );
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

		// 추후 테마에 적용할 스크립트가 있다면 아래의 배열에 추가한다.
//		$register_scripts = array(
//			'bangrang-checkout' => array(
//				'src'     => get_stylesheet_directory_uri() . '/assets/js/bangrang-checkout' . $suffix . '.js',
//				'deps'    => array( 'jquery', 'woocommerce', 'wc-country-select', 'wc-address-i18n', 'postcode-api' ),
//				'version' => BANGRANG_VERSION,
//			),
//		);
//		foreach ( $register_scripts as $name => $props ) {
//			self::register_script( $name, $props['src'], $props['deps'], $props['version'] );
//		}
	}

	private static function register_script( $handle, $path, $deps = array( 'jquery' ), $version = BANGRANG_VERSION, $in_footer = true ) {
		self::$scripts[] = $handle;
		wp_register_script( $handle, $path, $deps, $version, $in_footer );
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
		global $wp, $theorder;

		switch ( $handle ) {
			case 'bangrang-checkout':
				$params = array(
					'ajax_url'            => WC()->ajax_url(),
					'wc_ajax_url'         => WC_AJAX::get_endpoint( '%%endpoint%%' ),
					'checkout_url'        => WC_AJAX::get_endpoint( 'checkout' ),
					'is_checkout'         => is_page( wc_get_page_id( 'checkout' ) ) && empty( $wp->query_vars['order-pay'] ) && ! isset( $wp->query_vars['order-received'] ) ? 1 : 0,
					'is_editable'         => is_a( $theorder, 'WC_Order' ) ? in_array( $theorder->get_status(), array(
						'on-hold',
						'gift-addressing'
					) ) : true,
					'debug_mode'          => defined( 'WP_DEBUG' ) && WP_DEBUG,
					'i18n_checkout_error' => esc_attr__( 'Error processing checkout. Please try again.', 'woocommerce' ),
					'postcode_digit'      => '5',
				);
				break;
			default:
				$params = false;
		}

		return $params;
	}
}

Bangrang_WooCommerce::init();
