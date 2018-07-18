<?php
/**
 * Created by PhpStorm.
 * User: ace
 * Date: 18/07/2018
 * Time: 4:56 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Bangrang
 */
class Bangrang {

	/**
	 * Bangrang constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
//		add_action( 'widgets_init', array( $this, 'widgets_init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 10 );
//		add_filter( 'body_class', array( $this, 'body_classes' ) );
//		add_filter( 'wp_page_menu_args', array( $this, 'page_menu_args' ) );
//		add_filter( 'navigation_markup_template', array( $this, 'navigation_markup_template' ) );
//		add_action( 'enqueue_embed_scripts', array( $this, 'print_embed_styles' ) );
		add_filter( 'show_admin_bar', '__return_false' );
	}

	public function setup() {
	}


	/**
	 * Enqueue scripts and styles.
	 *
	 * @since  0.1
	 */
	public function scripts() {
		global $bangrang_version;
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		/**
		 * Styles
		 */
		wp_register_style( 'bangrang', get_stylesheet_directory() . '/assets/css/bangrang' . $suffix . '.css', array(), $bangrang_version );
//		wp_enqueue_style( 'bangrang-style', get_template_directory_uri() . '/style.css', array( 'bangrang' ), $bangrang_version );
//		wp_enqueue_style( 'bangrang-font', get_template_directory_uri() . '/assets/css/bookcraft-font.css', '', $bangrang_version );

		//wp_enqueue_style( 'bangrang-icons', get_template_directory_uri() . '/assets/sass/base/icons.css', '', $bangrang_version );

		/**
		 * Fonts
		 * Open+Sans:400,600,400italic,600italic|Roboto+Slab:400,700
		 */
		$google_fonts = apply_filters( 'bangrang_google_font_families', array(
			'open-sans'   => 'Open+Sans:400,600,400italic,600italic',
			'Roboto-Slab' => 'Roboto+Slab:400,700',
		) );

		$query_args = array(
			'family' => implode( '|', $google_fonts ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

		wp_enqueue_style( 'bangrang', $fonts_url, array(), null );

	}

}

return new Bangrang();
