jQuery(function() {
    wfesm_view_stock();
    wfesm_edit_products();
    wfesm_trash_product();
    wfesm_refresh_products();
    categories_saving();
    stock_status();
    stock_quanitity();
});


function get_checked_categories(id) {

    var array_data = new Array();

    jQuery("[main-category-wrapper='" + id + "'] .save-this-category:checked").each(function() {
        array_data.push(jQuery(this).val());
    });

    return array_data.join('\n');
}


function categories_saving() {
    jQuery('body').on('change', ".save-this-category", function() {
        //alert( 10 )
        var id = jQuery('[name="uploading-product-id"]').val();
        if (hard_trim(get_checked_categories(id)) == '') {
            alert('Please make sure you set at least 1 category to the product');
        } else {


            //return;

            ajaxify({
                action: 'wfesm_save_categories',
                categories: get_checked_categories(id),
                id: id
            }, '', false, true);
        }
    });



    jQuery('body').on('mouseover', '.wfsmp-inner-row', function() {

        var product_id = jQuery(this).attr('data-product-row');

        jQuery('[name="uploading-product-id"]').val(jQuery(this).attr('data-product-row'));

        jQuery("[edit-thumbnail='" + product_id + "']").show();

        //console.log( jQuery( this ).attr( 'data-product-row' ) );
    });

    jQuery('body').on('mouseout', '.wfsmp-inner-row', function() {

        var product_id = jQuery(this).attr('data-product-row');
        jQuery("[edit-thumbnail='" + product_id + "']").hide();

    });
}


function stock_status() {
    //wfesm_st_update
    //wfesm_pid

    jQuery('body').on('click', '.stock-toggle', function(e) {
        if (e) e.preventDefault();
        var wfesm_pid = jQuery(this).attr('data-product');

        ajaxify({
            action: 'wfesm_st_update',
            wfesm_pid: wfesm_pid
        }, '', false, false, wfesm_pid);


    });
}


function stock_quanitity() {
    jQuery('body').on('keypress', '.the-stock-quanitity-input', function(event) {
        if (event.which == 13 || event.keyCode == 13) {
            var q = jQuery(this).val();
            var product_id = jQuery(this).attr('data-input-product');

            //alert( q+"\n"+product_id );

            if (hard_trim(q) == '') {
                alert("Please enter a valid number");
                return;
            }

            ajaxify({
                action: 'wfesm_update_stock_quanitity',
                product_id: product_id,
                quantity: q

            }, '', false, false, product_id);
        }

    });


    jQuery('body').on('change click', '.the-stock-quanitity-input', function(event) {

        var q = jQuery(this).val();

        if (q != "" && q > 0) {
            var product_id = jQuery(this).attr('data-input-product');

            //alert( q+"\n"+product_id );

            if (hard_trim(q) == '') {
                alert("Please enter a valid number");
                return;
            }

            ajaxify({
                action: 'wfesm_update_stock_quanitity',
                product_id: product_id,
                quantity: q

            }, '', false, false, product_id);
        }

    });

}

function loading(text = 'Please Wait ...', show = true) {
    if (show) {
        jQuery('.loader-wrapper').show().find('.loader-content').html(text);
        jQuery('body').addClass('open-customs');
        //jQuery( '#loading' ).q_dialog( 'show' ).find( 'h3' ).html( text ); 
    } else {
        //jQuery( '#loading' ).q_dialog( 'hide' );
        jQuery('.loader-wrapper').hide().find('.loader-content').html(text);
        jQuery('body').removeClass('open-customs');
    }
}


function s_(text = 'Success!', show = true) {
    if (show)
        jQuery('#success').q_dialog('open').find('h3').html(text);
    else
        jQuery('#success').q_dialog('close');
}


function e_(text = 'Something Went Wrong, Please check your internet connection!', show = true) {
    if (show)
        jQuery('#error').q_dialog('open').find('h3').html(text);
    else
        jQuery('#error').q_dialog('close');
}

function wfesm_view_stock() {
    jQuery('body').on('click', '.wfsmp-view-stock', function(event) {
        if (event) event.preventDefault();

        var id = jQuery(this).attr('data-product');
        ajaxify({
            action: 'wfesm_view_product',
            id: id

        }, 'display-product-details');

        jQuery('#view-product-details').q_dialog('open');

    });
}


function wfesm_edit_products() {
    //wfsmp-edit-product

    jQuery('body').on('click', '.wfsmp-edit-product', function(event) {
        if (event) event.preventDefault();

        var id = jQuery(this).attr('data-product');

        jQuery('#edit-products-dialog').q_dialog('open');
        jQuery('[name="uploading-product-id"]').val(id);

        jQuery('.display-editing').html('');

        ajaxify({
            action: 'wfesm_display_editing',
            id: id
        }, 'display-editing');

    });
}


function wfesm_trash_product() {
    //wfsmp-trash-product
    jQuery('body').on('click', '.wfsmp-trash-product', function(event) {
        if (event) event.preventDefault();

        var id = jQuery(this).attr('data-product');

        var confirm_delete = confirm('Sure to delete this product?');

        if (confirm_delete) {
            ajaxify({
                action: "wfesm_trash_product",
                id: id
            }, 'product-ajax');
        }


    });
}


function wfesm_refresh_products() {

    jQuery('body').on('click', '.wfsmp-refresh', function(event) {
        if (event) event.preventDefault();

        ajaxify({
            action: "wfesm_refresh_products"
        }, 'product-ajax');

    })

}

function get_ajax_url() {
    return jQuery('#wfsmp-ajax-url').val();
}

function ajaxify(object_data, display_class = '', test = false, update_silently = false, product_id = 0) {
    var action = object_data['action'];

    if (!update_silently) loading();
    jQuery.ajax({
        url: get_ajax_url(),
        method: 'post',
        data: get_ajax_data(object_data),
        async: true,
        cache: false,
        success: function(data) {
            loading('', false);


            if (test)
                alert(data);


            if (action == 'wfesm_save_admin_optons') {
                s_('Saved!');
            }

            if (action == 'wfesm_update_stock_quanitity') {
                if (data == '') {
                    return;
                }

                if (parseInt(data) > 0) {
                    jQuery('[data-stock-id=' + product_id + ']').addClass('wfsmp-btn-primary').html('<strong>In stock</strong> - Click to Update');
                    jQuery('[data-stock-id=' + product_id + ']').removeClass('wfsmp-btn-danger');
                    jQuery('[stock-id="' + product_id + '"]').removeClass('out-ofstock').addClass('in-stock').html('In stock');

                    jQuery('[data-flat-sock-id="' + product_id + '"]').removeClass('out-ofstock').addClass('in-stock').html('In Stock');

                } else {
                    jQuery('[data-stock-id=' + product_id + ']').addClass('wfsmp-btn-danger');
                    jQuery('[data-stock-id=' + product_id + ']').removeClass('wfsmp-btn-primary').html('<strong>Out of stock</strong> - Update');
                    jQuery('[stock-id="' + product_id + '"]').addClass('out-ofstock').removeClass('in-stock').html('Out of stock');

                    jQuery('[data-flat-sock-id="' + product_id + '"]').removeClass('in-stock').addClass('out-ofstock').html('Out of Stock');
                }
            }

            if (action == 'wfesm_st_update') {
                if (jQuery('[data-stock-id=' + product_id + ']').hasClass('wfsmp-btn-primary')) {
                    jQuery('[data-stock-id=' + product_id + ']').addClass('wfsmp-btn-danger');
                    jQuery('[data-stock-id=' + product_id + ']').removeClass('wfsmp-btn-primary').html('<strong>Out of stock</strong> - Update');
                    jQuery('[stock-id="' + product_id + '"]').addClass('out-ofstock').removeClass('in-stock').html('Out of stock');
                } else {
                    jQuery('[data-stock-id=' + product_id + ']').addClass('wfsmp-btn-primary').html('<strong>In stock</strong> - Click to Update');
                    jQuery('[data-stock-id=' + product_id + ']').removeClass('wfsmp-btn-danger');
                    jQuery('[stock-id="' + product_id + '"]').removeClass('out-ofstock').addClass('in-stock').html('In stock');
                }

                jQuery("[data-falt-product-q='" + product_id + "']").html(data);
                jQuery("[data-input-product='" + product_id + "']").val(data);
            }

            if (display_class != '') {
                jQuery('.' + display_class).html(data);



                if (action == 'wfesm_refresh_products') {
                    jQuery('.' + display_class).html(data).hide().fadeIn('slow');
                }

                if (display_class == 'product-ajax') {
                    jQuery("#wfesm_table").DataTable();
                }

                return;
            }

        },
        error: function() {
            loading('', false);
            if (update_silently) e_('Your last chnages could have not been saved due to problems contacting the server');
            else e_();
        }
    });
}


function get_ajax_data(array_data) {
    var dataToPass = "";

    const all_keys = Object.keys(array_data);

    var totalCount = all_keys.length;

    index = 1;

    jQuery.each(array_data, function(key, value) {
        if (index != totalCount) {
            dataToPass += key + "=" + value + "&";
        } else {
            dataToPass += key + "=" + value;
        }

        index++;
    });

    return dataToPass;

}



function hard_trim(str) {
    return jQuery.trim(str.replace(/[\t\n]+/g, ''));
}