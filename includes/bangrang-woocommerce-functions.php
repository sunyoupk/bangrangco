<?php
/**
 * Created by PhpStorm.
 * User: ace
 * Date: 19/07/2018
 * Time: 10:08 AM
 */

add_filter( 'woocommerce_checkout_fields', 'bangrang_unset_checkout_fields' );
add_filter( 'storefront_customizer_css', 'bangrang_storefront_customizer_css' );
add_action( 'woocommerce_before_checkout_shipping_form', 'bangrang_before_checkout_shipping_form' );


if ( ! function_exists( 'bangrang_unset_checkout_fields' ) ) {
	/**
	 * Unset checkout fields.
	 */
	function bangrang_unset_checkout_fields( $fields ) {
		unset( $fields['billing']['billing_last_name'] );
		unset( $fields['billing']['billing_company'] );
		unset( $fields['billing']['billing_country'] );
		unset( $fields['billing']['billing_city'] );

		unset( $fields['shipping']['shipping_last_name'] );
		unset( $fields['shipping']['shipping_company'] );
		unset( $fields['shipping']['shipping_country'] );
		unset( $fields['shipping']['shipping_city'] );

		return $fields;
	}
}

if ( ! function_exists( 'bangrang_storefront_customizer_css' ) ) {
	/**
	 * Add bangrang shipping_address_method style to storefront theme customize css.
	 */
	function bangrang_storefront_customizer_css( $styles ) {
		$storefront_customizer = new Storefront_Customizer();
		$storefront_theme_mods = $storefront_customizer->get_storefront_theme_mods();

        $styles .= '
            .shipping-address-method-fields .shipping_address_methods > li .shipping_address_box,
			.shipping-address-method-fields .place-order {
				background-color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], -5 ) . ';
			}

			.shipping-address-method-fields .shipping_address_methods > li:not(.woocommerce-notice) {
				background-color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], -10 ) . ';
			}

			.shipping-address-method-fields .shipping_address_methods > li:not(.woocommerce-notice):hover {
				background-color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], -15 ) . ';
			}
			
			.shipping-address-method-fields .shipping_address_methods li input[type=radio]:first-child:checked+label:before {
				color: ' . $storefront_theme_mods['accent_color'] . ';
			}
		';

		return $styles;
	}
}


if ( ! function_exists( 'bangrang_before_checkout_shipping_form' ) ) {
	/**
	 * Defer shipping form fields.
	 */
	function bangrang_before_checkout_shipping_form( $checkout ) {
?>
		<div class="shipping-address-method-fields">
			<p class="form-row form-row-wide shipping-address-method-field validate-required" id="shipping_address_method_field" data-priority="50">
				<ul class="bangrang_shipping_methods shipping_address_methods methods">

                    <li class="shipping_address_method shipping_address_method_direct">
                        <input id="shipping_address_method_direct" type="radio" class="input-radio" name="shipping_address_method" value="direct" data-order_button_text="">
                        <label for="shipping_address_method_direct">주소 직접입력</label>
                        <div class="shipping_address_box shipping_address_method_direct" style="display: none;">
                            <p>선물로 보낼 주소를 직접 입력합니다.</p>
                        </div>
                    </li>

                    <li class="shipping_address_method shipping_address_method_sms">
                        <input id="shipping_address_method_sms" type="radio" class="input-radio" name="shipping_address_method" value="sms" data-order_button_text="">
                        <label for="shipping_address_method_sms">주소 입력폼 전송(SMS)</label>
                        <div class="shipping_address_box shipping_address_method_sms" style="display: none;">
                            <p>받는 분이 주소를 직접 입력하도록 입력 화면의 URL을 SMS로 전송합니다.</p>
                        </div>
                    </li>

                    <li class="shipping_address_method shipping_address_method_kakao">
                        <input id="shipping_address_method_kakao" type="radio" class="input-radio" name="shipping_address_method" value="kakao" data-order_button_text="">
                        <label for="shipping_address_method_kakao">주소 입력폼 전송(kakao talk)</label>
                        <div class="shipping_address_box shipping_address_method_kakao" style="display: none;">
                            <p>받는 분이 주소를 직접 입력하도록 입력 화면의 URL을 kakao talk로 전송합니다.</p>
                        </div>
                    </li>

				</ul>

			</p>

		</div>


<?php

	}
}