/* global bangrang_checkout_params */
/* global daum */
jQuery( function( $ ) {

    // bangrang_checkout_params is required to continue, ensure the object exists
    if ( typeof bangrang_checkout_params === 'undefined' ) {
        return false;
    }

    var bangrang_checkout_form = {
        $checkout_form: (bangrang_checkout_params.is_checkout == 1) ? $( 'form.checkout' ) : $( 'form.wc_ace_shipping_form' ),
        selectedShippingAddressMethod: false,
        init: function() {
            // shipping address methods.
            this.$checkout_form.on( 'click', 'input[name="shipping_address_method"]', this.shipping_address_method_selected );

            // Postcode search
            this.append_search_postcode();
            this.$checkout_form.on( 'click', '.btn-search-postcode', this.search_postcode );

            // Address fields
            this.$checkout_form.on( 'change', '#ship-to-different-address input', this.ship_to_different_address );

            // Trigger events
            this.$checkout_form.find( '#ship-to-different-address input' ).change();
            this.init_shipping_address_methods();
        },

        ship_to_different_address: function() {
            $( 'div.shipping-address-method-fields' ).hide();
            var is_checked = $( this ).is( ':checked' );
            if ( is_checked ) {
                $( 'div.shipping-address-method-fields' ).slideDown();
            }

            $( '.woocommerce-billing-fields' ).find( 'p.address-field' ).each( function( i, field ) {
                if ( is_checked ) {
                    // Hide billing address fields.
                    $( field ).filter( ':visible' ).slideUp( 0 );
                    $( field ).removeClass( 'validate-required' );
                } else {
                    $( field ).slideDown( 230 );
                    $( field ).addClass( 'validate-required' );
                    if ( $( field ).find( 'label .required' ).length === 0 ) {
                        // todo 필수 translate or parameter required!
                        $( field ).find( 'label' ).append( '&nbsp;<abbr class="required" title="' + '필수' + '">*</abbr>' );
                    }
                }
                $( field ).find( 'label .optional' ).remove();
            });
        },

        init_shipping_address_methods: function() {
            var $shipping_address_methods = this.$checkout_form.find( 'input[name="shipping_address_method"]' );

            // If there is one method, we can hide the radio input
            if ( 1 === $shipping_address_methods.length ) {
                $shipping_address_methods.eq(0).hide();
            }

            // If there was a previously selected method, check that one.
            if ( bangrang_checkout_form.selectedShippingAddressMethod ) {
                $( '#' + bangrang_checkout_form.selectedShippingAddressMethod ).prop( 'checked', true );
            }

            // If there are none selected, select the first.
            if ( 0 === $shipping_address_methods.filter( ':checked' ).length ) {
                $shipping_address_methods.eq(0).prop( 'checked', true );
            }

            if ( $shipping_address_methods.length > 1 ) {

                // Hide open descriptions.
                $( 'div.shipping_address_box' ).filter( ':visible' ).slideUp( 0 );
            }

            // Trigger click event for selected method
            $shipping_address_methods.filter( ':checked' ).eq(0).trigger( 'click' );
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

            // Show shipping address fields(only direct).
            var selected_value = $( this ).val();

            $( '.shipping_address' ).find( 'p.address-field' ).each( function( i, field ) {
                if ( selected_value === 'direct' ) {
                    $( field ).slideDown( 230 );
                    $( field ).addClass( 'validate-required' );
                    if ( $( field ).find( 'label .required' ).length === 0 ) {
                        // todo 필수 translate or parameter required!
                        $( field ).find( 'label' ).append( '&nbsp;<abbr class="required" title="' + '필수' + '">*</abbr>' );
                    }
                } else {
                    if ( bangrang_checkout_params.is_checkout == 1 ) {
                        $( field ).filter( ':visible' ).slideUp( 0 );
                    }
                    $( field ).removeClass( 'validate-required' );
                }
                $( field ).find( 'label .optional' ).remove();
            });

            var selectedShippingAddressMethod = $( '.woocommerce-checkout input[name="shipping_address_method"]:checked' ).attr( 'id' );

            if ( selectedShippingAddressMethod !== bangrang_checkout_form.selectedShippingAddressMethod ) {
                $( document.body ).trigger( 'shipping_address_method_selected' );
            }

            bangrang_checkout_form.selectedShippingAddressMethod = selectedShippingAddressMethod;
        },

        append_search_postcode: function() {
            $( '#billing_address_1_field > label' ).append(
                '<a data-type="billing" class="btn-search-postcode" style="cursor:pointer;"><i class="fa fa-search"></i></a>'
            );
            $( '#shipping_address_1_field > label' ).append(
                '<a data-type="shipping" class="btn-search-postcode" style="cursor:pointer;"><i class="fa fa-search"></i></a>'
            );
        },

        search_postcode: function () {
            var type = $( this ).data( 'type' );
            new daum.Postcode( {
                oncomplete: function ( data ) {
                    if ( bangrang_checkout_params.postcode_digit == '5' ) {
                        $( '#' + type + '_postcode' ).val( data.zonecode );
                    } else {
                        $( '#' + type + '_postcode' ).val( data.postcode );
                    }
                    $( '#' + type + '_address_1' ).val( data.address );

                    $( '#' + type + '_address_2' ).focus();
                }
            } ).open();
        }
    };

    bangrang_checkout_form.init();
});
