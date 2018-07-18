<?php
/**
 * Created by PhpStorm.
 * User: ace
 * Date: 18/07/2018
 * Time: 5:14 PM
 */

if ( ! function_exists( 'bangrang_is_woocommerce_activated' ) ) {
	/**
	 * Query WooCommerce activation
	 */
	function bangrang_is_woocommerce_activated() {
		return class_exists( 'WooCommerce' ) ? true : false;
	}
}
