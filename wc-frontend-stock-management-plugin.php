<?php
/*
  Plugin Name: WC Front End Stock Management
  Plugin URI: https://spicehook.com/
  Description: WC Front End Stock Management - gives you ( Administrator / Shop Manager) to quickly manage the WooCommerce Product Stock Status, Product Stock Quantity, Product Featured Image, Product Categories assignment without entered in WordPress Administration area.  Use the following Shortcode: [wfesm_stock_management]
  Version: 1.1
  Author: SpiceHook Solutions
  Author URI: https://profiles.wordpress.org/spicehooksolutions/
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

ob_start(); // this one willl enable us to login users without warning of hearder modification information 

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

global $wpdb;

define( 'WFESM_PLUGIN_URL', WP_PLUGIN_URL."/wc-frontend-stock-management-plugin" );

define('WFESM_VERSION', 10);

add_action('plugins_loaded', 'wfesm_plugins_update');


// include relevant classes to be used in the system 

require_once dirname(__FILE__)."/classes/Dialog.class.php"; 
require_once dirname(__FILE__)."/classes/Table.class.php"; 
require_once dirname(__FILE__)."/classes/Form.class.php"; 
require_once dirname(__FILE__)."/classes/WCFrontEndStockManagement.class.php"; 

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'wfesm_add_plugin_settings_link');

if (!function_exists('wfesm_add_plugin_settings_link')) {

function wfesm_add_plugin_settings_link( $links ) {
	$links[] = '<a href="' .
		admin_url( 'admin.php?page=woocommerce-front-end-stock-management
			' ) .
		'">' . __( '<span style="font-weight: bold; color: #FD7E14;">Settings</span>' ) . '</a>';
	return $links;
}
}

if (!function_exists('wfesm_plugins_update')) {

function wfesm_plugins_update() {
 
}
}

/* Uninstall and Activation handlers */
register_activation_hook(__FILE__, 'wfesm_activate');
register_deactivation_hook(__FILE__, 'wfesm_deactivate');

register_uninstall_hook(__FILE__, 'wfesm_deactivate_uninstall');

if (!function_exists('wfesm_activate')) {

function wfesm_activate() {

    global $wpdb;
    add_option('WFESM_VERSION',WFESM_VERSION);


}
}

if (!function_exists('wfesm_deactivate_uninstall')) {

function wfesm_deactivate_uninstall() {
    global $wpdb;
    delete_option('WFESM_VERSION');
}
}

if (!function_exists('wfesm_deactivate')) {

function wfesm_deactivate() {
    delete_option('WFESM_VERSION');
}

}

// Create Shortcode wfesm_stock_management
// Shortcode: [wfesm_stock_management]

if (!function_exists('create_wfesm_stock_management_shortcode')) {

function create_wfesm_stock_management_shortcode()
{
	ob_start();

	$settings_ = new WoocommerceFrontEndStockManagement();
	$settings_->create_table(); // make sure the options table is created. this function will be ignored if table exists

	$result = null;

	if( !is_user_logged_in() ):

		?> 
		<!--  add animated class to login dialog for it to be clossable -->
			<script>
				jQuery( function(){
					jQuery( '#login-dialog' ).q_dialog( 'open' );
					hide_login_btn();
				});


				function hide_login_btn(){
					setTimeout( hide_login_btn, 1000 );
					if( jQuery( '#login-dialog' ).is( ':visible' ) ){
						jQuery( '#re-login-dialog' ).hide();
					} else {
						jQuery( '#re-login-dialog' ).show();
					}
				}
			</script>
		<?php
		
		return "<center><a href='' id='re-login-dialog' class='wfsmp-btn wfsmp-btn-primary' data-zoom-in='dialog' q-target='#login-dialog' style='padding: 10px 30px; !important' >".esc_html('Click Here to Login')."</a></center>"; 
	endif; 

	if( !current_user_can( 'manage_woocommerce' ) ){
		return "<div class='wfsmp-error-reporting'>".esc_html('You are not allowed to view this page. This page is only accessible to Shop Managers')."</div>";
	}

	$result .= "<div class='tbl-wrapper'>";

	$result .= "<a href='' class='wfsmp-btn wfsmp-btn-primary wfsmp-refresh' ><span class='dashicons dashicons-update-alt'></span> ".esc_html('Refresh')."</a>"; 

	

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

        $thumbnail_body_td = '<td style="min-width: 70px;"><center>'.$thumbnail.'</center></td>';


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
        						<a href="" class="wfsmp-btn wfsmp-btn-primary wfsmp-btn-full wfsmp-view-stock" data-product="'.$product_id.'" ><span class="dashicons dashicons-visibility"></span> '.	esc_html('More Details').'</a>
        						
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

	$thumbnail_head_td = '<th scope="col">'.esc_html('Image').'</th>';
	$thumbnail_footer_td = '<th scope="col">'.esc_html('Image').'</th>';

  

 	 $stock_head_td = '<th scope="col">'.esc_html('Stock Status').'</th>';
 	 $stock_footer_td = '<th scope="col">'.esc_html('Stock Status').'</th>';

 	$result .= "<div class='product-ajax'>";
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

$result .= "</div>";
$result .= "</div>";

$result.="<form action='' method='post' name='wfesm_form' id='wfesm_form'><input type='hidden' name='wfesm_pid' id='wfesm_pid' value='' /></form>";
$result .='<script>

jQuery(document).ready( function () {
    jQuery("#wfesm_table").DataTable();
});

/*function wfesm_change_state(pid)
{
  jQuery("#wfesm_pid").val(pid);
  jQuery("#wfesm_form").submit();
}*/
</script>';


	ob_get_clean();
return $result;
	
}

}
add_shortcode( 'wfesm_stock_management', 'create_wfesm_stock_management_shortcode' );


add_action('wp_head','wfesm_hd_func');

if (!function_exists('wfesm_hd_func')) {

function wfesm_hd_func(){
  if(isset($_POST['wfesm_pid']) && $_POST['wfesm_pid']>0){
    $product = new WC_Product( ($_POST['wfesm_pid']));

    $stock_quantity = $product->get_stock_quantity();
	$stock_status   = $product->get_stock_status();

	//instock, outofstock
	//$product->set_stock_quantity( $quantity );

	if($stock_status=='instock'){
		if( $product->managing_stock() ){
			//$product->set_stock_quantity( 0 );
			$product->set_stock_status('outofstock');
		} else {
			$product->set_stock_status('outofstock');
		}
	} else{
		if( $product->managing_stock() ){
			$product->set_stock_status('instock');
			//$product->set_stock_quantity( 1 );
		} else{
			$product->set_stock_status('instock');
		}
	}

	$product->save();

	echo $product->get_stock_quantity();



  }
}
}


add_action( 'wp_footer', 'wfesm_enqueue' );

if (!function_exists('wfesm_enqueue')) {

function wfesm_enqueue($hook) {
    
        
	wp_enqueue_script( 'ajax-script', plugins_url( '/js/wfps.js', __FILE__ ), array('jquery') );

	// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
	wp_localize_script( 'ajax-script', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
}
}

// Same handler function...
add_action( 'wp_ajax_wfesm_st_update', 'wfesm_st_update' );
add_action( 'wp_ajax_nopriv_wfesm_st_update', 'wfesm_st_update' );

if (!function_exists('wfesm_st_update')) {

function wfesm_st_update() {
	global $wpdb;
    $returnval="";
            if(isset($_POST['wfesm_pid']) && $_POST['wfesm_pid']>0)
            {
            $product = new WC_Product( $_POST['wfesm_pid']);
        
            $stock_quantity = $product->get_stock_quantity();
            $stock_status   = $product->get_stock_status();
        
				//instock, outofstock
				if($stock_status=='instock'){
					if( $product->managing_stock() ){
						//$product->set_stock_quantity( 0 );
						$product->set_stock_status('outofstock');
					} else {
						$product->set_stock_status('outofstock');
					}
				} else{
					if( $product->managing_stock() ){
						$product->set_stock_status('instock');
						//$product->set_stock_quantity( 1 );
					} else{
						$product->set_stock_status('instock');
					}
				}
            
            	$product->save();

            	echo $product->get_stock_quantity();
        
            }
            //echo  $returnval;
	wp_die();
}
}



add_action( 'wp_enqueue_scripts', 'wfesm_load_dashicons_front_end' );

if (!function_exists('wfesm_load_dashicons_front_end')) {

function wfesm_load_dashicons_front_end() {
  	wp_enqueue_style( 'dashicons' );
}
}


if (!function_exists('wfesm_get_all_product_categories')) {
function wfesm_get_all_product_categories( $post_id, $role = 'edit' ){

	

	// if role is view then no editing will be allowed

	$categories_them_all = '<div main-category-wrapper="'.$post_id.'">';


	$post = get_post( (int) $post_id );

	$taxonomy     = 'product_cat';
	$orderby      = 'name';  
	$show_count   = 0;      
	$pad_counts   = 0;      
	$hierarchical = 1;      
	$title        = '';  
	$empty        = 0;

	$args = array(
	    'taxonomy'     => $taxonomy,
	    'orderby'      => $orderby,
	    'show_count'   => $show_count,
	    'pad_counts'   => $pad_counts,
	    'hierarchical' => $hierarchical,
	    'title_li'     => $title,
	    'hide_empty'   => $empty
	);

	$n = 0; 

	$all_categories = get_categories( $args );

	foreach ($all_categories as $cat) {

	    if($cat->category_parent == 0) {

	        $category_id = $cat->term_id;
	        
	        $br = "<br />";
	        if( $n == 0 ){
	        	$br = "";
	        }  else {
	        	$br = "<br />";
	        }

	        if( has_term( $cat->name, 'product_cat', $post ) ){
	        	$checked = "checked";
	        	$display_name = "$br$cat->name";
	        } else {
	        	$checked = '';
	        	$display_name = ""; 
	        }


	       
	        if( $role == 'view' ){
	        	$categories_them_all .= "$display_name";
	        } else {
	        	$categories_them_all .= "$br<input category-edit='$post_id'  type='checkbox' $checked class='save-this-category' value='$cat->term_id'> $cat->name";
	        }
	        $args2 = array(
	            'taxonomy'     => $taxonomy,
	            'parent'       => $category_id,
	            'orderby'      => $orderby,
	            'show_count'   => $show_count,
	            'pad_counts'   => $pad_counts,
	            'hierarchical' => $hierarchical,
	            'title_li'     => $title,
	            'hide_empty'   => $empty
	        );

	        $sub_cats = get_categories( $args2 );

	        if($sub_cats) {

	            foreach($sub_cats as $sub_category) {

	            	if( $role == 'view' ){
	            		$indent = 0;
	            	} else {
	            		$indent = 25;
	            	}

	            	if( has_term( $sub_category->name, 'product_cat', $post ) ){
			        	$checked1 = "checked";
			        	$display_name1 = "<br />".wfesm_indent( $indent ) . " $sub_category->name ";
			        } else {
			        	$checked1 = '';
			        	$display_name1 = "";
			        }
	                
	                 if( $role == 'view' ){
	               		 $categories_them_all .= $display_name1;
	                } else {
	               		 $categories_them_all .= "<br />".wfesm_indent( $indent )." <input category-edit='$post_id' type='checkbox' class='save-this-category' $checked1 value='$sub_category->term_id' />  $sub_category->name ";
	                }

	              


	                 $args3 = array(
	            'taxonomy'     => $taxonomy,
	            'parent'       =>  $sub_category->term_id,
	            'orderby'      => $orderby,
	            'show_count'   => $show_count,
	            'pad_counts'   => $pad_counts,
	            'hierarchical' => $hierarchical,
	            'title_li'     => $title,
	            'hide_empty'   => $empty
	        );

	        $sub_cats3 = get_categories( $args3 );

	        if($sub_cats3) {

	            foreach($sub_cats3 as $sub_category3) {

	            	if( $role == 'view' ){
	            		$indent1 = 0;
	            	} else {
	            		$indent1 = 50;
	            	}

	            	if( has_term( $sub_category3->name, 'product_cat', $post ) ){
			        	$checked2 = "checked";
			        	$display_name2 = "<br />" . wfesm_indent( $indent1 ) . $sub_category3->name; 
			        } else {
			        	$checked2 = '';
			        	$display_name2 = ""; 

			        }
	              
	                if( $role == 'view' ){
	                	$categories_them_all .= $display_name2;
	                } else {
	                	$categories_them_all .= "<br />".wfesm_indent( $indent1 )." <input category-edit='$post_id' type='checkbox' class='save-this-category' $checked2 value='$sub_category3->term_id' />  $sub_category3->name ";

	                }
	               

	            }


	                 }

	            }
	        }

	        $n++;
	    }       
	}

	$categories_them_all .="</div>";

	return $categories_them_all;
}

}

if (!function_exists('wfesm_indent')) {

function wfesm_indent( $indent = 20 ){
	return "<span style='margin-right: ".$indent."px; display-inline-block;'></span>";
}
}

if (!function_exists('wfesm_get_Q')) {

function wfesm_get_Q( $product ){
	return $product->get_stock_quantity();
}

}

if (!function_exists('wfesm_remove_spaces')) {

function wfesm_remove_spaces($str){
  $pattern = '/\s*/m';
    $replace = '';
    $removedLinebaksAndWhitespace = preg_replace( $pattern, $replace,$str);
    return $removedLinebaksAndWhitespace;
}
}

require_once 'design.func.php';
require_once 'ajax-actions.php';
require_once 'dialogs-ui.func.php';