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
$show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
$is_gift = empty( $order->get_meta( '_shipping_address_method' ) ) ? false : true;
$has_shipping_address = empty( $order->get_shipping_address_1() ) || empty( $order->get_shipping_address_2() ) ? false : true;
$order_status = $order->get_status();
?>
<section class="woocommerce-customer-details">

	<?php if ( $show_shipping ) : ?>

	<section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses">
		<div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1">

	<?php endif; ?>

	<h2 class="woocommerce-column__title"><?php esc_html_e( '구매자 정보', 'wc-ace' ); ?></h2>

	<address>
		<?php
            // This code doesn't use.
            // echo wp_kses_post( $order->get_formatted_billing_address( __( 'N/A', 'woocommerce' ) ) );
		?>
        <p>이름: <?php echo esc_html( $order->get_billing_first_name() ); ?></p>
        <p>연락처: <?php echo esc_html( $order->get_billing_phone() ); ?></p>
        <p>이메일: <?php echo esc_html( $order->get_billing_email() ); ?></p>

		<?php if ( $is_gift ) : ?>
        <p>배송방법: 선물하기</p>
		<?php if ( ! $has_shipping_address || $order_status == 'on-hold' ) { ?>
                <p><a class="button">주소입력 폼 재전송</a></p>
        <?php } ?>
        <?php endif; ?>

<!--		--><?php //if ( $order->get_billing_phone() ) : ?>
<!--			<p class="woocommerce-customer-details--phone">--><?php //echo esc_html( $order->get_billing_phone() ); ?><!--</p>-->
<!--		--><?php //endif; ?>
<!---->
<!--		--><?php //if ( $order->get_billing_email() ) : ?>
<!--			<p class="woocommerce-customer-details--email">--><?php //echo esc_html( $order->get_billing_email() ); ?><!--</p>-->
<!--		--><?php //endif; ?>

	</address>

	<?php if ( $show_shipping ) : ?>

		</div><!-- /.col-1 -->

		<div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2">
			<h2 class="woocommerce-column__title"><?php esc_html_e( 'Shipping address', 'woocommerce' ); ?></h2>
			<address>
				<?php
                // Customization.
                //echo wp_kses_post( $order->get_formatted_shipping_address( __( 'N/A', 'woocommerce' ) ) );
                ?>
				<?php if ( $is_gift ) : ?>
                <p>받는분: <?php echo esc_html( $order->get_shipping_first_name() ); ?></p>
                <p>연락처: <?php echo esc_html( $order->get_meta( '_shipping_phone' )); ?></p>
				<?php endif; ?>
                <p>
                    <?php echo esc_html( $order->get_shipping_address_1()); ?> <br/>
                    <?php echo esc_html( $order->get_shipping_address_2()); ?> <br/>
                    (우) <?php echo esc_html( $order->get_shipping_postcode()); ?>
                </p>
			</address>
		</div><!-- /.col-2 -->

	</section><!-- /.col2-set -->

	<?php endif; ?>

	<?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>

</section>
