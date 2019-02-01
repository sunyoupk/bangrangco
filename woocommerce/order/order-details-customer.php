<?php
/**
 * Order Customer Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-customer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$show_shipping        = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
$is_gift              = $order->get_meta( '_is_gift' ) == 'yes' ? true : false;
$has_shipping_address = empty( $order->get_shipping_address_1() ) || empty( $order->get_shipping_address_2() ) ? false : true;
$order_status         = $order->get_status();
$is_editable          = in_array( $order_status, array( 'on-hold', 'gift-addressing' ) );
?>

<form name="checkout" class="checkout wc_ace_shipping_form">
    <section class="woocommerce-customer-details woocommerce-shipping-fields">

        <h2 class="woocommerce-column__title"><?php esc_html_e( 'Shipping address', 'woocommerce' ); ?></h2>

        <address>
            <h3 id="ship-to-different-address">
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                    <input id="ship-to-different-address-checkbox"
                           class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" <?php checked( $is_gift, 1 ); ?>
                           type="checkbox" name="ship_to_different_address" value="1"
                           <?php echo $is_editable == false ? 'disabled' : ''; ?>
                    />
                    <span><?php _e( '선물하기', 'bangrang' ); ?></span>
                </label>
            </h3>

			<?php
			echo '<div>';

			if ( $is_editable ) {
				echo bangrang_before_checkout_shipping_form( $order->get_meta( '_shipping_address_method' ) );
			}
			?>

			<?php if ( is_order_received_page() ) {
				echo '</div>';
			} ?>

            <div class="woocommerce-shipping-fields__field-wrapper">
				<?php
				$shipping_fields = apply_filters( 'woocommerce_checkout_fields', array(
					'shipping' => WC()->countries->get_address_fields(
						'',
						'shipping_'
					),
				) );
				$fields          = $shipping_fields['shipping'];

				foreach ( $fields as $key => $field ) {
					if ( is_order_received_page() || ! $is_editable ) {
						$field['custom_attributes']['readonly'] = 'readonly';
					}
					switch ( $key ) {
						case 'shipping_first_name':
							$value = esc_html( $order->get_shipping_first_name() );
							break;
						case 'shipping_phone':
							$value = esc_html( $order->get_meta( '_shipping_phone' ) );
							break;
						case 'shipping_address_1':
							$value = esc_html( $order->get_shipping_address_1() );
							break;
						case 'shipping_address_2':
							$value = esc_html( $order->get_shipping_address_2() );
							break;
						case 'shipping_postcode':
							$value = esc_html( $order->get_shipping_postcode() );
							break;
						default:
							$value = null;
							break;
					}
					woocommerce_form_field( $key, $field, $value );
				}
				?>
            </div>

			<?php if ( ! is_order_received_page() ) {
				echo '</div>';
			} ?>

			<?php if ( $is_editable ) { ?>
				<?php if ( is_order_received_page() ) { ?>
                    <p>
                        <a class="button" href="<?php echo esc_url( $order->get_view_order_url() ); ?>">주소 변경</a>
                    </p>
				<?php } else { ?>
                    <p>
                        <button type="button" class="button send-address">주소입력 폼 재전송</button>
                        <button type="submit" class="button">주소저장</button>
                    </p>
				<?php } ?>

			<?php } ?>

        </address>

		<?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>

    </section>
</form>