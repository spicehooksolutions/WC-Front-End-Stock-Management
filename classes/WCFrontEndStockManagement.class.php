<?php
class WoocommerceFrontEndStockManagement {
	private $woocommerce_front_end_stock_management_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'woocommerce_front_end_stock_management_add_plugin_page' ) );

		
		
	}

	public function woocommerce_front_end_stock_management_add_plugin_page() {
		add_menu_page(
			'Woocommerce Front End Stock Management', // page_title
			'WFSMP', // menu_title
			'manage_options', // capability
			'woocommerce-front-end-stock-management', // menu_slug
			array( $this, 'woocommerce_front_end_stock_management_create_admin_page' ), // function
			'dashicons-feedback',
			80
		);
	}

	public function woocommerce_front_end_stock_management_create_admin_page() {
		
		wfesm_head_scripts( true );


		if( !$this->table_exists() ){
			wp_safe_redirect( admin_url( 'admin.php?page=woocommerce-front-end-stock-management' ) );
		}


		// default is empty


		?>




		<div class="wrap" style="background-color: white; margin: 25px 25px; padding: 25px 25px;">
			<h2>WC Front End Stock Management Plugin (WFSMP) Settings</h2>
			<p>Please checked the following options to be managed from Front End.</p>
			
			<script>

				jQuery( function(){
					/*
						checked: 1
						unchekced: 0
					*/
					jQuery( 'body' ).on( 'click', "[name='save-settings']", function(){

						var category;
						var stock;
						var image;
						var quantity;

						if( jQuery( "[name='settings-category']" ).is(":checked") ){
							category = 1;
						} else {
							category = 0;
						}


						if( jQuery( "[name='settings-stock']" ).is(":checked") ){
							stock = 1;
						} else {
							stock = 0;
						}


						if( jQuery( "[name='settings-prod-image']" ).is(":checked") ){
							image = 1;
						} else {
							image = 0;
						}


						if( jQuery( "[name='settings-prod-quantity']" ).is(":checked") ){
							quantity = 1;
						} else {
							quantity = 0;
						}


						ajaxify({
							action: 'wfesm_save_admin_optons', 
							category: category, 
							stock: stock, 
							product_image: image,
							quantity: quantity 
						});
					} );
				} );
			</script>

				<?php

				//$this->save( 1, 0, 1 );

				//echo $this->get( 'category', 1 ); 

				

					if( $this->is_category_allowed() ){
						$category_checked = true;
					} else {
						$category_checked = false;
					}


					if( $this->is_stock_allowed() ){
						$stock_checked = true;
					} else {
						$stock_checked = false;
					}


					if( $this->is_product_image_allowed() ){
						$product_image_checked = true;
					} else {
						$product_image_checked = false;
					}

					$form = new Form( 2, 'post', array( " onsubmit='return false;' " ) ); 

					$form->init(); 
					$form->formGroup( "Category", $form->check_box( 'settings-category', $category_checked ) ); 
					$form->formGroup( "Stock Status", $form->check_box( 'settings-stock', $stock_checked ) ); 
					$form->formGroup( "Product Image", $form->check_box( 'settings-prod-image', $product_image_checked ) ); 

					$form->formGroup( "Quantity", $form->check_box( 'settings-prod-quantity', $this->is_quantity_allowed() ) ); 

					//quantity
					$form->formGroup( "<input type='submit' name='save-settings' value='Save' class='wfsmp-btn wfsmp-btn-primary' style='cursor: pointer;' />", '' ); 

					
				?>
			</form>
		</div>
	<?php
			wfesm_dialogs();
			$this->create_table();  
	}



		public function table_exists(){
			 global $wpdb;


		    $table_name = $wpdb->base_prefix.'wfesm_settings';
		    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

		    if ( ! $wpdb->get_var( $query ) == $table_name ) {
		    	return false;
		    }

		    return true; 
		}


		public function create_table() {
		    global $wpdb;


		    $table_name = $wpdb->base_prefix.'wfesm_settings';
		    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

		    if ( ! $wpdb->get_var( $query ) == $table_name ) {

		        $charset_collate = $wpdb->get_charset_collate();

		        $sql = "CREATE TABLE `$table_name` (
		            _id int(10) UNSIGNED AUTO_INCREMENT,
		            settings text,
		            PRIMARY KEY  (_id)
		        ) $charset_collate;";

		        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		        dbDelta($sql);

		        exit();
		    } 
		}


		public function get_table(){
			global $wpdb; 

		    $table_name = $wpdb->base_prefix.'wfesm_settings';

		    return $table_name; 
		}

		public function save( $category, $stock, $product_image, $quantity ){
			 global $wpdb;

		    $wpdb->show_errors( true ); 

    		$wpdb->query($wpdb->prepare("DELETE FROM " . $this->get_table() ) );



			$json = '
				{
					"category" :  		'.$category.',
					"stock" : 	'.$stock.',
					"product_image" : 	'.$product_image.',
					"quantity": '.$quantity.'
				}
				';

    		$results = $wpdb->get_results( "SELECT * FROM " . $this->get_table() );

    		if( count( $results ) ){
    			/// update the table
    			//echo $json;
    		} else {
    			$wpdb->insert( $this->get_table(), array(
    				'settings' => $json
    			) );
    		}
		}


		private function get_settings_array(){

			global $wpdb;

			$wpdb->show_errors( true );

			$json = $wpdb->get_var("SELECT settings FROM ".$this->get_table());

			$array = array(); 

			if( $json == '' || $json == null ){
				return $array; 
			}

			return json_decode( $json, true ); 
		}


		private function get( $key, $default_value = '' ){
			// images/placeholder.png will only apply for images
			// if object is not an  image, then provide relevant default value

		
			$array = $this->get_settings_array();	

			 $setting_value = @$array[$key];

			
			if( $setting_value ===  '' || $setting_value === null ){
				return $default_value; 
			} else {
				return $setting_value; 
			}
			

			return $setting_value; 
		}


		public function is_category_allowed(){
			if( $this->get( 'category', 1 ) == 1 ){
				return true;
			}

			return false;

		}


		public function is_stock_allowed(){
			if( $this->get( 'stock', 1 ) == 1 ){
				return true;
			}

			return false;

		}

		public function is_product_image_allowed(){
			if( $this->get( 'product_image', 1 ) == 1 ){
				return true;
			}

			return false;

		}


		public function is_quantity_allowed(){
			if( $this->get( 'quantity', 1 ) == 1 ){
				return true;
			}

			return false;

		}


		//


	}
if ( is_admin() ){
	$woocommerce_front_end_stock_management = new WoocommerceFrontEndStockManagement();

}