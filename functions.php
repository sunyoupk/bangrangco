<?php
/**
 * 방랑부부 테마 functions
 * User: Sunyoup Kim
 * Date: 02/07/2018
 * Time: 1:47 PM
 */


/**
 * Assign the bangrang version to a var
 */
$theme            = wp_get_theme( get_stylesheet() );
$bangrang_version = $theme['Version'];
define( 'BANGRANG_VERSION', $bangrang_version );

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

$bangrang = (object) array(
	'version' => $bangrang_version,
	'main'    => require_once 'includes/class-bangrang.php',
//	'walker_nav' => require 'includes/class-bangrang-walker-nav.php',
//	'customizer' => require 'includes/customizer/class-bangrang-customizer.php',
);

require_once 'includes/bangrang-core-functions.php';

if ( bangrang_is_woocommerce_activated() ) {
	$bangrang->woocommerce = require_once 'includes/class-bangrang-woocommerce.php';
	require_once 'includes/bangrang-woocommerce-functions.php';
	//	include_once 'includes/woocommerce/bangrang-woocommerce-template-hooks.php';
//	include_once 'includes/woocommerce/bangrang-woocommerce-template-functions.php';
}
