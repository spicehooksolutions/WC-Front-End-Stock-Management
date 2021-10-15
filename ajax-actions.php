<?php 

add_action( 'wp_ajax_wfesm_view_product', 'wfesm_view_product' );
add_action( 'wp_ajax_nopriv_wfesm_view_product', 'wfesm_view_product' );

if (!function_exists('wfesm_view_product')) {

function wfesm_view_product(){


	$id = sanitize_text_field($_POST['id']);
	wfesm_get_product_data( (int)$id );

	wp_die(); 
}
}



add_action( 'wp_ajax_wfesm_trash_product', 'wfesm_trash_product' );
add_action( 'wp_ajax_nopriv_wfesm_trash_product', 'wfesm_trash_product' );

if (!function_exists('wfesm_trash_product')) {

function wfesm_trash_product(){

	$id  = sanitize_text_field($_POST['id']);

	$delete = wfesm_deleteProduct( $id );

	if( $delete )
		echo wfesm_stock_management_refresh_data();
	else
		echo $delete;


	wp_die();
}

}

add_action( 'wp_ajax_wfesm_refresh_products', 'wfesm_refresh_products' );
add_action( 'wp_ajax_nopriv_wfesm_refresh_products', 'wfesm_refresh_products' );

if (!function_exists('wfesm_refresh_products')) {

function wfesm_refresh_products(){
	echo wfesm_stock_management_refresh_data();

	wp_die();
}
}

add_action( 'wp_ajax_wfesm_save_admin_optons', 'wfesm_save_admin_optons' );
add_action( 'wp_ajax_nopriv_wfesm_save_admin_optons', 'wfesm_save_admin_optons' );

if (!function_exists('wfesm_save_admin_optons')) {

function wfesm_save_admin_optons(){



	$category = sanitize_text_field($_POST['category']);
	$stock = sanitize_text_field($_POST['stock']);
	$product_image = ($_POST['product_image']);
	$quantity = sanitize_text_field($_POST['quantity']);

	$save = new WoocommerceFrontEndStockManagement();
	$save->create_table();
	$save->save( $category, $stock, $product_image, $quantity );

	wp_die();
}

}

add_action( 'wp_ajax_wfesm_display_editing', 'wfesm_display_editing' );
add_action( 'wp_ajax_nopriv_wfesm_display_editing', 'wfesm_display_editing' );
//add_action( 'wp_footer', 'wfesm_display_editing' );

if (!function_exists('wfesm_display_editing')) {

function wfesm_display_editing(){

	$settings = new WoocommerceFrontEndStockManagement();
	$settings->create_table(); // if database table is not availble it will be created if, if it's available it will be ignored

	$id = sanitize_text_field($_POST['id']);

    $product = wc_get_product( $id );

     $stock = wfesm_get_stock($product); 

     $form_edit_stock = new WFSEForm( 4 ); 
	$form_edit_stock->init(); 


     if( $settings->is_product_image_allowed() ):

	    $thumbnail = "<img src='".get_the_post_thumbnail_url( $id, 'post-thumbnail' )."' alt='".$product_name."' style='height: 100px;' data-product-image='$id' />";

	    echo "<center>$thumbnail</center>";


		
		$form_edit_stock->formGroup( 'Product Image', '<a href="" class="upload-edit-product-image-trigger wfsmp-btn"><span class="dashicons dashicons-upload"></span> '.esc_html('Click to Update').'</a>' );
	endif;

	if( $settings->is_category_allowed() ):
		$form_edit_stock->formGroup( 'Categories', wfesm_get_all_product_categories( $id ) );
	endif;


	if( $settings->is_stock_allowed() ):
		if( wfesm_is_instock( $product ) )
			$form_edit_stock->formGroup( 'Stock', ' <a href="" class="stock-toggle wfsmp-btn-primary wfsmp-btn" data-product="'.$id.'"  data-stock-id="' . $product_id . '" >'.$stock.' - '.esc_html('Click to Update').'</a>  ' );
		else
			$form_edit_stock->formGroup( 'Stock', ' <a href="" class="stock-toggle wfsmp-btn-danger wfsmp-btn" data-product="'.$id.'" data-stock-id="' . $product_id . '" >'.$stock.' - '.esc_html('Click to Update').'</a> ' );
		
	endif; 

	$form_edit_stock->close( '' );


	if( !$settings->is_product_image_allowed() && !$settings->is_stock_allowed() && !$settings->is_category_allowed() ){
		?> 
       <div class='wfsmp-error-reporting'><?php echo esc_html('Oops! Silence here? Seems like you disabled all settings. Re-enable them in WFSMP settings. Thank you');?></div>

		<?php
	}

	wp_die();
}

}



add_action( 'wp_ajax_wfesm_save_categories', 'wfesm_save_categories' );
add_action( 'wp_ajax_nopriv_wfesm_save_categories', 'wfesm_save_categories' );

if (!function_exists('wfesm_save_categories')) {

function wfesm_save_categories(){

	$categories = ($_POST['categories']);
	$id = sanitize_text_field($_POST['id']);

	$array = explode( "\n" , $categories);

	$new_array = array();

	foreach ($array as $key => $value) {
		array_push( $new_array ,  (int)wfesm_remove_spaces( $value ) );
	}

	//var_dump( $new_array );

	wp_set_object_terms( (int)$id, $new_array , 'product_cat');


	wp_die();
}

}


add_action( 'wp_ajax_wfesm_update_product_image', 'wfesm_update_product_image' );
add_action( 'wp_ajax_nopriv_wfesm_update_product_image', 'wfesm_update_product_image' );

if (!function_exists('wfesm_update_product_image')) {

function wfesm_update_product_image(){

	$id = sanitize_text_field($_POST['uploading-product-id']); 

	$file_name = sanitize_file_name($_FILES['single-image-uploader']['name']);
    $file_size = sanitize_text_field($_FILES['single-image-uploader']['size']);
    $file_type = sanitize_text_field($_FILES['single-image-uploader']['type']);
    $tmpt_name  = sanitize_text_field($_FILES['single-image-uploader']['tmp_name']);

    $allowed_extensions = array("png", "jpg", "jpeg");  
    $extension = pathinfo( $file_name, PATHINFO_EXTENSION );

    $printable_allowed_extension = implode( ', ', $allowed_extensions ); 

    if( !in_array( $extension , $allowed_extensions ) ){
    	echo "$extension not allowed!. Allowed extensions are: ".$printable_allowed_extension;
    	wp_die();
    }

    // removing white space
	$file_without_white_space = preg_replace( '/\s+/', '-', $file_name );
	$clean_file = preg_replace('/[^A-Za-z0-9.\-]/', '', $file_without_white_space);

	$image_name       = $clean_file;
    $upload_dir       = wp_upload_dir(); // Set upload folder
	$image_data       = file_get_contents($tmpt_name); // Get image data
    $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
    $filename         = basename( $unique_file_name ); // Create image file name


    // Check folder permission and define file location
    if( wp_mkdir_p( $upload_dir['path'] ) ) {
        $file = $upload_dir['path'] . '/' . $filename;
    } else {
        $file = $upload_dir['basedir'] . '/' . $filename;
    }


    // Create the image  file on the server
    file_put_contents( $file, $image_data );


     // Check image file type
    $wp_filetype = wp_check_filetype( $filename, null );


    // Set attachment data
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title'     => sanitize_file_name( $filename ),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );


     // Create the attachment
    $attach_id = wp_insert_attachment( $attachment, $file, $id );


     // Include image.php
    require_once(ABSPATH . 'wp-admin/includes/image.php');


      // Define attachment metadata
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );


    // Assign metadata to attachment
    wp_update_attachment_metadata( $attach_id, $attach_data );


    // And finally assign featured image to post
    set_post_thumbnail( $id, $attach_id );


    echo get_the_post_thumbnail_url( $id, 'post-thumbnail' );


	wp_die();
}

}

add_action( 'wp_ajax_wfesm_update_stock_quanitity', 'wfesm_update_stock_quanitity' );
add_action( 'wp_ajax_nopriv_wfesm_update_stock_quanitity', 'wfesm_update_stock_quanitity' );

if (!function_exists('wfesm_update_stock_quanitity')) {

function wfesm_update_stock_quanitity(){
	$product_id = sanitize_text_field($_POST['product_id']);
	$quantity = sanitize_text_field($_POST['quantity']);

	wfesm_save_stock_Q( $product_id, $quantity ); 

	wp_die();
}
}

/* AJAX FUNCTIONS */

if (!function_exists('wfesm_save_stock_Q')) {

function wfesm_save_stock_Q( $product_id, $quantity ){
	// Get an instance of the WC_Product object
	$product = new WC_Product( $product_id );


	if( $quantity == 0 ){
		//echo "out of sock";
		$product->set_stock_status('outofstock');
	} else {
		$product->set_stock_status('instock');
		//echo "in stock";
	}

	$product->set_stock_quantity( $quantity );

	$product->set_manage_stock( true );


	$product->save();

	echo $quantity; 
}
}

if (!function_exists('wfesm_custom_auth')) {

function wfesm_custom_auth( $user, $password, $remember_me ) {


	if( $remember_me == 1 ){
		$remember_me = true;
	} else{
		$remember_me = false;
	}

    $creds = array(
        'user_login'    => $user,
        'user_password' => $password,
        'remember'      => $remember_me
    );
 
    $user = wp_signon( $creds, true );
 
    if ( is_wp_error( $user ) ) {
        echo "<div class='wfsmp-error-reporting'>".$user->get_error_message()."</div>";
    } else {
    	// refresh here
          ?> 
        <style type="text/css">
        	#loading{
        		display: block;
        	}
        </style>

        <script type="text/javascript">
        	jQuery( function(){
        		window.location.href = '';
        	});
        </script>
        <?php
    }
}

}

if(!function_exists('wfesm_stock_management_refresh_data')) {

function wfesm_stock_management_refresh_data(){
	ob_start();
	$settings_ = new WoocommerceFrontEndStockManagement();
	$settings_->create_table();

	$result=NULL;


    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1
    );

    $loop = new WP_Query( $args );

    $producthtml="";
    while ( $loop->have_posts() ) : $loop->the_post();
               global $product;
        $product_id = $product->get_id(); 
        $product_name = get_the_title(); 

        $stock = wfesm_get_stock($product); 

        if( wfesm_is_instock( $product ) ){
        	if( $settings_->is_stock_allowed() )
				$stock_btn =' <center><a href="" class="stock-toggle wfsmp-btn-primary wfsmp-btn" data-product="'.$product_id.'"  data-stock-id="' . $product_id . '">'.$stock.' - '.esc_html('Click to Update').'</a></center>';
        	else 
				$stock_btn =' <center><span class="in-stock" data-flat-sock-id="'.$product_id.'" >'.$stock.'</span></center>';

        } else{
        	if( $settings_->is_stock_allowed() )
				$stock_btn ='<center><a href="" class="stock-toggle wfsmp-btn-danger wfsmp-btn" data-product="'.$product_id.'" data-stock-id="' . $product_id . '" >'.$stock.' - '.esc_html('Update').'</a> </center>  ';
            else 
				$stock_btn ='<center><span class="out-ofstock" data-flat-sock-id="'.$product_id.'"  >'.$stock.'</span> </center>  ';
        }

        if( $settings_->is_product_image_allowed() ){
        	$thumbnail = "<img src='".get_the_post_thumbnail_url( null, 'post-thumbnail' )."' alt='".$product_name."' style='height: 50px;' data-product-image='$product_id' /><br /> <a href='' edit-thumbnail='$product_id' class='upload-edit-product-image-trigger wfsmp-btn'><span class='dashicons dashicons-edit'></span> Edit</a>";
        } else{
        	$thumbnail = "<img src='".get_the_post_thumbnail_url( null, 'post-thumbnail' )."' alt='".$product_name."' style='height: 50px;' data-product-image='$product_id' />";
        }

        $thumbnail_body_td = '<td><center>'.$thumbnail.'</center></td>';


        $stock_body_td = '<td>'.$stock_btn.'</td>';

        if( $settings_->is_quantity_allowed() ){
        	$quantity = '<input data-input-product="'.$product_id.'" class="the-stock-quanitity-input" type="number" value="'.wfesm_get_Q( $product ).'" />';
        } else {
        	$quantity = '<span data-falt-product-q="'.$product_id.'">'.wfesm_get_Q( $product ).'</span>'; 
        }


        if( $settings_->is_category_allowed() ){
        	$categories_role = 'edit';
        } else {
        	$categories_role = 'view';
        }




        $producthtml .='<tr class="wfsmp-inner-row" data-product-row="'.$product_id.'">
        					<td>'.$product->get_id().'</td>
        					'.$thumbnail_body_td.'
        					<td>'.get_the_title().'</td>
        					
        					'.$stock_body_td.'

        					<td>'.wfesm_get_all_product_categories( $product_id, $categories_role ).'</td>
        					<td>'.$quantity.'</td>
        					<td>
        						<a href="javascript:void(0);" class="wfsmp-btn wfsmp-btn-primary wfsmp-btn-full wfsmp-view-stock" data-product="'.$product_id.'" ><span class="dashicons dashicons-visibility"></span> '.esc_html('More Details').'</a>
        						
        					</td> 
        				</tr>';
    endwhile;

    wp_reset_query();


    $result .="
	
	<style>
		
		.dataTable .instock
		{
			display:block;
			color:white;
			background-color:#0c820c87;
			padding:5px 5px;
			text-align:center;
		}
		
		.dataTable .outofstock
		{
			display:block;
			color:#f6f6c7;
			background-color:#be5a5a;
			padding:5px 5px;
			text-align:center;
		}
    
        </style>";

        $user = wp_get_current_user();

//if(isset( $user->roles[0] ) && ($user->roles[0] == 'shop_manager' || $user->roles[0] == 'administrator') )
//{
	
       $thumbnail_head_td = '<th scope="col">'.esc_html('Product Image').'</th>';
  	   $thumbnail_footer_td = '<th scope="col">'.esc_html('Product Image').'</th>';

  	   $stock_head_td = '<th scope="col">'.esc_html('Stock Status').'</th>';
  	   $stock_footer_td = '<th scope="col">'.esc_html('Stock Status').'</th>';

	$result.='<div class="max-width">
    
    <table id="wfesm_table" class="display" style="width:100%">
	<thead>
		<tr>
            <th scope="col">#</th>
			'.$thumbnail_head_td.'
			<th scope="col">'.esc_html('Name').'</th>
           	'.$stock_head_td.'
			   <th>'.esc_html('Categories').'</th>
           	<th>'.esc_html('Quantity').'</th>
           	<th scope="col">'.esc_html('Action').'</th>
		</tr>
	</thead>
	<tbody>
    '.$producthtml.'
	</tbody>
	<tfoot>
		<tr>
            <th scope="col">#</th>
			'.$thumbnail_footer_td.'
			<th scope="col">'.esc_html('Name').'</th>
           	'.$stock_footer_td.'
           	<th>'.esc_html('Categories').'</th>
			   <th>'.esc_html('Quantity').'</th>
			   <th scope="col">'.esc_html('Action').'</th>
		</tr>
	</tfoot>
</table>

</div>';


	ob_get_clean();
return $result;
	
}

}

/**
 * Method to delete Woo Product
 * 
 * @param int $id the product ID.
 * @param bool $force true to permanently delete product, false to move to trash.
 * @return \WP_Error|boolean
 */

if (!function_exists('wfesm_deleteProduct')) {

function wfesm_deleteProduct($id, $force = FALSE){
    $product = wc_get_product($id);

    if(empty($product))
        return new WP_Error(999, sprintf(__('No %s is associated with #%d. Please hit refresh button!', 'woocommerce'), 'product', $id));

    // If we're forcing, then delete permanently.
    if ($force)
    {
        if ($product->is_type('variable'))
        {
            foreach ($product->get_children() as $child_id)
            {
                $child = wc_get_product($child_id);
                $child->delete(true);
            }
        }
        elseif ($product->is_type('grouped'))
        {
            foreach ($product->get_children() as $child_id)
            {
                $child = wc_get_product($child_id);
                $child->set_parent_id(0);
                $child->save();
            }
        }

        $product->delete(true);
        $result = $product->get_id() > 0 ? false : true;
    }
    else
    {
        $product->delete();
        $result = 'trash' === $product->get_status();
    }

    if (!$result)
    {
        return new WP_Error(999, sprintf(__('This %s cannot be deleted, Please hit refresh button!', 'woocommerce'), 'product'));
    }

    // Delete parent product transients.
    if ($parent_id = wp_get_post_parent_id($id))
    {
        wc_delete_product_transients($parent_id);
    }
    return true;
}
}

if (!function_exists('wfesm_get_product_data')) {
function wfesm_get_product_data( $id ){

	// image, name, price, stock, short description, categories, long description will be viewable on it's own

	$product = wc_get_product($id);

	$product_name = get_the_title( $id ); 
    $thumbnail = "<img src='".get_the_post_thumbnail_url( $id, 'full' )."' alt='".$product_name."' style='height: 250px;' />";

    $stock = wfesm_get_stock( $product );
    $price = wfesm_get_price( $product ); 

    $short_description = $product->post->post_excerpt;


	?> 
		<div class="v-product v-product-wrapper">
			<div class="v-product-image">
				<center><?php echo $product_name; ?></center>
			</div>
			<div class="v-product v-product-image">
				<center><?php echo $thumbnail; ?></center>
			</div>

			<div class="v-product v-product-meta">
				<center>
					<span><?php echo $stock; ?></span>
					<span><?php echo get_woocommerce_currency_symbol() . ' ' . $price; ?></span>
					<span>ID <?php echo $id; ?></span>
						
				</center>
			</div>

			<div class="v-product v-short-description">
				<h4 style="font-weight: bold; font-size: 20px; text-align: center;"><?php echo esc_html('Short Description');?></h4>
				<div style="text-align: center;"><?php echo $short_description; ?></div>
			</div>
		</div>
	<?php

}
}

if (!function_exists('wfesm_get_description')) {

function wfesm_get_description( $product ){

	return nl2br( $product->get_description() );
}
}

if (!function_exists('wfesm_get_stock')) {

function wfesm_get_stock( $product ){
	$id = $product->get_id();
	if( wfesm_is_instock( $product ) ){
		return "<strong class='in-stock' stock-id='$id'>".esc_html('In Stock')."</strong>";
	}

	return "<strong class='out-ofstock' stock-id='$id'>".esc_html("Out of Stock")."</strong>";
}

}

if (!function_exists('wfesm_is_instock')) {

function wfesm_is_instock( $product ){
	if ( !$product->managing_stock() && ! $product->is_in_stock() ){
		return false; 
	} else {
		if( wfesm_get_Q( $product ) == 0 && $product->managing_stock() )
			return false; 

		return true; 
	}
}

}

/**
 * Returns product price based on sales.
 * 
 * @return string
 */

if (!function_exists('wfesm_get_price')) {

function wfesm_get_price( $product ) {
    /*if( $product->is_on_sale() ) {
        return $product->get_sale_price();
    }*/
    return $product->get_regular_price();
}
}



/* END OF FUNCTIONS */

add_action( 'wp_footer', 'wfesm_ajax_url' );
add_action( 'admin_footer', 'wfesm_ajax_url' );


if (!function_exists('wfesm_ajax_url')) {

function wfesm_ajax_url(){
	echo '<input type="hidden" id="wfsmp-ajax-url" value="'.admin_url('admin-ajax.php').'" />';
}
}

add_action( 'wp_footer', 'wfesm_upload_form' ); 

if (!function_exists('wfesm_upload_form')) {

function wfesm_upload_form(){
	?> 
	<script>
		jQuery( function(){

			jQuery( 'body' ).on( 'click', '.upload-edit-product-image-trigger', function( e ){

				if( e ) e.preventDefault(); 

				jQuery( '#triggered-single-image-upload' ).trigger( 'click' );
			});

			jQuery( 'body' ).on( 'change', '#triggered-single-image-upload', function(){
				jQuery( '#single-images-uploader' ).submit();
			});

			jQuery( 'body' ).on( 'submit', '#single-images-uploader', function( e ){
				if( e ) e.preventDefault();

				var action 		= jQuery( "[name='wfesm_update_product_image']" ).val();
				var product_id 	= jQuery( "[name='uploading-product-id']" ).val();


				var fileInputElement = document.getElementById("triggered-single-image-upload");
  				

  				 if( "undefined" == typeof fileInputElement.files[0] ){
  					alert( 'Undefined type of file\nUploading file is null, please try re-uploading again, thanks' );
  					return; 
  				}

  				var fileName = fileInputElement.files[0].name;



  				if( hard_trim( fileName ) == "" ) {
  					alert( 'Please upload a file' )
					return;
				}

				var product_id = jQuery( "[name='uploading-product-id']" ).val();

				loading('Uploading...');
				jQuery.ajax({
					url: get_ajax_url(),
					type: 'post',
					data: new FormData( this ),
					processData: false,
					contentType: false,
					async: true,
					cache: false,
					success: function( data ){
						loading( '', false );
						if( data.indexOf( 'http' ) >= 0 || data.indexOf( 'https' ) >= 0 ){
							jQuery( "[data-product-image='"+product_id+"']" ).attr( 'src', data );
							return; 
						}
						alert( data );
					}, error: function(){
						loading( '', false ); 
						e_( 'Please try again later. Connection problems' );
					}
				})

			});
		});
	</script>
	<?php

	$formUpload = new WFSEForm( 0, "post", array( "id ='single-images-uploader' style='display: none; ' action='" . WFESM_PLUGIN_URL . "/product-upload.php' enctype='multipart/form-data' " ) ); 
		$formUpload->init(); 
		$formUpload->textBox( "", "single-image-uploader", "file", "", array( "id='triggered-single-image-upload'" ) ); 
		$formUpload->textBox( "", "uploading-product-id", 'text', '' ); 
		$formUpload->textBox( "", "action", 'text', 'wfesm_update_product_image' ); 
		$formUpload->close("Upload", 'upload-product-btn');	
}

}