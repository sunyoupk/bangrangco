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
$theme            = wp_get_theme( 'bangrang' );
$bangrang_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

$bangrang = (object) array(
	'version' => $bangrang_version,
	'main'    => require 'includes/class-bangrang.php',
//	'walker_nav' => require 'includes/class-bangrang-walker-nav.php',
//	'customizer' => require 'includes/customizer/class-bangrang-customizer.php',
);

include_once 'includes/bangrang-core-functions.php';
//require 'includes/bangrang-template-hooks.php';
//require 'includes/bangrang-template-functions.php';

if ( bangrang_is_woocommerce_activated() ) {
	$bangrang->woocommerce = include_once 'includes/class-bangrang-woocommerce.php';

//	include_once 'includes/woocommerce/bangrang-woocommerce-template-hooks.php';
//	include_once 'includes/woocommerce/bangrang-woocommerce-template-functions.php';
}
