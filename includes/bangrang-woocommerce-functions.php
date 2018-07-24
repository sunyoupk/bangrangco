<?php
/**
 * Created by PhpStorm.
 * User: ace
 * Date: 19/07/2018
 * Time: 10:08 AM
 */

add_filter( 'storefront_customizer_css', 'bangrang_storefront_customizer_css', 10, 1 );
add_filter( 'woocommerce_checkout_fields', 'bangrang_unset_checkout_fields', 10, 1 );
add_filter( 'woocommerce_billing_fields', 'bangrang_billing_fields', 10, 2 );
add_filter( 'woocommerce_shipping_fields', 'bangrang_shipping_fields', 10, 2 );

add_action( 'woocommerce_before_checkout_shipping_form', 'bangrang_before_checkout_shipping_form', 10, 1 );
add_action( 'woocommerce_after_checkout_shipping_form', 'bangrang_after_checkout_shipping_form', 10, 1 );
add_action( 'woocommerce_after_checkout_validation', 'bangrang_after_checkout_validation', 10, 2 );


if ( ! function_exists( 'bangrang_unset_checkout_fields' ) ) {
	/**
	 * Unset checkout fields.
	 */
	function bangrang_unset_checkout_fields( $fields ) {

		unset( $fields['billing']['billing_last_name'] );
		unset( $fields['billing']['billing_company'] );
		unset( $fields['billing']['billing_country'] );
		unset( $fields['billing']['billing_state'] );
		unset( $fields['billing']['billing_city'] );

		$fields['billing']['billing_address_1']['required'] = 0;
		$fields['billing']['billing_postcode']['required']  = 0;

		unset( $fields['shipping']['shipping_last_name'] );
		unset( $fields['shipping']['shipping_company'] );
		unset( $fields['shipping']['shipping_country'] );
		unset( $fields['shipping']['shipping_state'] );
		unset( $fields['shipping']['shipping_city'] );

		$fields['shipping']['shipping_address_1']['required'] = 0;
		$fields['shipping']['shipping_postcode']['required']  = 0;
		$fields['shipping']['shipping_address_method'] = array(
			'type' => 'radio',
		);

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
				background-color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], - 5 ) . ';
			}

			.shipping-address-method-fields .shipping_address_methods > li:not(.woocommerce-notice) {
				background-color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], - 10 ) . ';
			}

			.shipping-address-method-fields .shipping_address_methods > li:not(.woocommerce-notice):hover {
				background-color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], - 15 ) . ';
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
		$shipping_address_methods = array(
			'direct' => array(
				'input_id'    => 'shipping_address_method_direct',
				'input_value' => 'direct',
				'label'       => '주소 직접입력',
				'description' => '선물로 보낼 주소를 직접 입력합니다.',
			),
			'sms'    => array(
				'input_id'    => 'shipping_address_method_sms',
				'input_value' => 'sms',
				'label'       => '주소 입력폼 전송(SMS)',
				'description' => '받는 분이 주소를 직접 입력하도록 입력 화면의 URL을 SMS로 전송합니다.',
			),
			'kakao'  => array(
				'input_id'    => 'shipping_address_method_kakao',
				'input_value' => 'kakao',
				'label'       => '주소 입력폼 전송(kakao talk)',
				'description' => '받는 분이 주소를 직접 입력하도록 입력 화면의 URL을 kakao talk로 전송합니다.',
			),
		);
		?>
        <div class="shipping-address-method-fields">
            <p class="form-row form-row-wide shipping-address-method-field validate-required"
               id="shipping_address_method_field" data-priority="50">
                <ul class="bangrang_shipping_methods shipping_address_methods methods">
                    <?php foreach ( $shipping_address_methods as $name => $props ) { ?>
                        <li class="shipping_address_method <?php echo esc_attr( $props['input_id'] ); ?>">
                            <input id="<?php echo esc_attr( $props['input_id'] ); ?>" type="radio" class="input-radio"
                                   name="shipping_address_method" value="<?php echo esc_attr( $props['input_value'] ); ?>"
                                   data-order_button_text="">
                            <label for="<?php echo esc_attr( $props['input_id'] ); ?>"><?php echo esc_html( $props['label'] ); ?></label>
                            <div class="shipping_address_box <?php echo esc_attr( $props['input_id'] ); ?>">
                                <p><?php echo esc_html( $props['description'] ); ?></p>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </p>
        </div>
		<?php
	}
}

if ( ! function_exists( 'bangrang_after_checkout_shipping_form' ) ) {
	/**
	 * HTML element align after hide address fields.
	 *
	 * @param string $checkout
	 */
	function bangrang_after_checkout_shipping_form( $checkout ) {
		echo '<div class="clear"></div>';
	}
}

if ( ! function_exists( 'bangrang_after_checkout_validation' ) ) {
	/**
	 * Validate address fields.
	 * todo if check many fields then replace code(iterlation...).
	 *
	 * @param $data
	 * @param WP_Error $errors
	 *
	 * @see WC_Checkout::get_posted_data()
	 */
	function bangrang_after_checkout_validation( $data, $errors ) {
		if ( $data['ship_to_different_address'] ) {

			if ( $data['shipping_address_method'] === 'direct' && $data['shipping_address_1'] === '' ) {
				$field_label = sprintf( __( 'Shipping %s', 'woocommerce' ), __( 'Street address', 'woocommerce' ) );
				$errors->add( 'required-field', apply_filters( 'woocommerce_checkout_required_field_notice', sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>' ), $field_label ) );
			}

			if ( $data['shipping_address_method'] === 'direct' && $data['shipping_postcode'] === '' ) {
				$field_label = sprintf( __( 'Shipping %s', 'woocommerce' ), __( 'Postcode / ZIP', 'woocommerce' ) );
				$errors->add( 'required-field', apply_filters( 'woocommerce_checkout_required_field_notice', sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>' ), $field_label ) );
			}

		} else {
			if ( $data['billing_address_1'] === '' ) {
				$field_label = sprintf( __( 'Billing %s', 'woocommerce' ), __( 'Street address', 'woocommerce' ) );
				$errors->add( 'required-field', apply_filters( 'woocommerce_checkout_required_field_notice', sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>' ), $field_label ) );
			}

			if ( $data['billing_postcode'] === '' ) {
				$field_label = sprintf( __( 'Billing %s', 'woocommerce' ), __( 'Postcode / ZIP', 'woocommerce' ) );
				$errors->add( 'required-field', apply_filters( 'woocommerce_checkout_required_field_notice', sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>' ), $field_label ) );
			}
		}
	}
}

if ( ! function_exists( 'bangrang_billing_fields' ) ) {
	function bangrang_billing_fields( $address_fields, $country ) {
		$billing_phone_field             = $address_fields['billing_phone'];
		$billing_phone_field['class']    = array( 'form-row-last' );
		$billing_phone_field['priority'] = 20;
		unset( $address_fields['billing_phone'] );

		$address_fields = array_slice( $address_fields, 0, 1, true ) + array( 'billing_phone' => $billing_phone_field ) +
		                  array_slice( $address_fields, 1, count( $address_fields ) - 1, true );

		return $address_fields;
	}
}

if ( ! function_exists( 'bangrang_shipping_fields' ) ) {
	function bangrang_shipping_fields( $address_fields, $country ) {

		// New shipping phone filed
		$shipping_phone_field = array(
			'label'        => __( 'Phone', 'woocommerce' ),
			'required'     => 'required' === get_option( 'woocommerce_checkout_phone_field', 'required' ),
			'type'         => 'tel',
			'class'        => array( 'form-row-last' ),
			'validate'     => array( 'phone' ),
			'autocomplete' => 'tel',
			'priority'     => 20,
		);

		// Re-Order address fields.
		// We use phone field without using the last name field.
		$address_fields = array_slice( $address_fields, 0, 1, true ) + array( 'shipping_phone' => $shipping_phone_field ) +
		                  array_slice( $address_fields, 1, count( $address_fields ) - 1, true );

		return $address_fields;
	}
}