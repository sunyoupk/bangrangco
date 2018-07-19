/* global bangrang_checkout_params */
jQuery( function( $ ) {

    // bangrang_checkout_params is required to continue, ensure the object exists
    if ( typeof bangrang_checkout_params === 'undefined' ) {
        return false;
    }

    var bangrang_checkout_form = {
        $checkout_form: $( 'form.checkout' ),
        selectedShippingAddressMethod: false,
        init: function() {
            // shipping address methods.
            this.$checkout_form.on( 'click', 'input[name="shipping_address_method"]', this.shipping_address_method_selected );
        },

        shipping_address_method_selected: function( e ) {
            e.stopPropagation();

            if ( $( '.shipping_address_methods input.input-radio' ).length > 1 ) {
                var target_shipping_address_box = $( 'div.shipping_address_box.' + $( this ).attr( 'ID' ) ),
                    is_checked         = $( this ).is( ':checked' );

                if ( is_checked && ! target_shipping_address_box.is( ':visible' ) ) {
                    $( 'div.shipping_address_box' ).filter( ':visible' ).slideUp( 230 );

                    if ( is_checked ) {
                        target_shipping_address_box.slideDown( 230 );
                    }
                }
            } else {
                $( 'div.shipping_address_box' ).show();
            }

            if ( $( this ).data( 'order_button_text' ) ) {
                $( '#place_order' ).text( $( this ).data( 'order_button_text' ) );
            } else {
                $( '#place_order' ).text( $( '#place_order' ).data( 'value' ) );
            }

            var selectedShippingAddressMethod = $( '.woocommerce-checkout input[name="shipping_address_method"]:checked' ).attr( 'id' );

            if ( selectedShippingAddressMethod !== bangrang_checkout_form.selectedShippingAddressMethod ) {
                $( document.body ).trigger( 'shipping_address_method_selected' );
            }

            bangrang_checkout_form.selectedShippingAddressMethod = selectedShippingAddressMethod;
        }
    };

    bangrang_checkout_form.init();
});
