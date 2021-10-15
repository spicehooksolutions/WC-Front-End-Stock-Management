<?php 

add_action( 'wp_footer', 'wfesm_dialogs' );

if (!function_exists('wfesm_dialogs')) {

function wfesm_dialogs(){

	?> 
	<style type="text/css">
		div[data-show="dialog"]{
			background: rgba(255, 255, 255, 0.7) ;
			z-index: 9999999999999999999999999999;
		}

		div.dialog-header .dialog-header-nicer{
			background: none;
			border-radius: 5px;

		}


		div.dialog-header .dialog-header-nicer h3{
			color:  <?php echo wfesm_get_primary_color(); ?>;
		}




		div.dialog-content{
			border-radius: 5px;
			padding: 0; 
			right: -10 !important; 
			min-height: 100%;
			width:  100px;
			border-radius: 0;
			box-shadow: -13px 5px 5px -8px rgba(136,132,132,0.75);
			-webkit-box-shadow: -13px 5px 5px -8px rgba(136,132,132,0.75);
			-moz-box-shadow: -13px 5px 5px -8px rgba(136,132,132,0.75);

		}

		#loading div.dialog-content,
		#error div.dialog-content,
		#success div.dialog-content{
			box-shadow: none;
			min-height: 100px;
			background: none;
		}

		#loading div.dialog-content h3,
		#error div.dialog-content h3,
		#success div.dialog-content h3{
			font-size:  16px;
		}

		div.dialog-content-nicer, div.chat-dialog-body, div.dialog-body-nicer{
			min-height: 100%;
		}

		div.dialog-content div.dialog-content-nicer{
			border: none !important;
			padding: 0 !important;
			border-bottom: 1px solid #e1e1e1;
		}

		div.dialog-content div.dialog-content-nicer div.dialog-body-nicer{
			border: none;
		}

		div.dialog-body-nicer{
			min-height: 100%;

		}

		.dialog-footer .dialog-footer-nicer{
			border: none;
		}
	</style>
	<?php
	/* DIALOG HTMLS */


	/* STARDARD DIALOGS */

	//login dialog
	$logn_dialog = new WFSEDialog( 'login-dialog' );
		$dialog_start=$logn_dialog->start(); 

		echo $dialog_start;
		   	// login users
			if( isset($_POST['wfsmp-login-user']) ){
				$remember_me = 0;

				$user = sanitize_text_field($_POST['wfsmp-login-user']);
				$password = ($_POST['wfsmp-login-password']);
				$rem_checkbox = sanitize_text_field($_POST['wfsmp-login-rem-me']);

				if( isset( $rem_checkbox ) ){
					$remember_me = 1; 
				} else {
					$remember_me = 0; 
				}

				// echo $remember_me; 

				if( empty( $user ) || empty( $password ) ){
					esc_html( "<div class='wfsmp-error-reporting'>Please fill in all the fields</div>" );
				} else {
					wfesm_custom_auth( $user, $password, $remember_me );
				}


			}

			echo "<center><h3>Please login to manage your store products</h3></center><br />";
			$form = new WFSEForm( 3 ); 
			$form->init(); 
			$form->textBox( esc_html('Email /Username'), 'wfsmp-login-user' );
			$form->textBox( esc_html('Password'), 'wfsmp-login-password', 'password' );
			$form->formGroup( esc_html('Remember me'), $form->check_box( 'wfsmp-login-rem-me', true ) );
			$form->formGroup( '', '<a href="'.wp_lostpassword_url().'" target="_blank">'.esc_html('Forgot Passowrd?').'</a>' );
			$form->close( esc_html('Login') ); 

			//$form->formGroup( '', '<a href="'.home_url().'">Go to homepage</a>' );

			$dialog_close=$logn_dialog->close();

			echo $dialog_close;


	// view products dilaog
	$product_details = new WFSEDialog('view-product-details', esc_html('Product Details'), "" , true);
	
	$dialog_start=$product_details->start(); 

	echo $dialog_start;

		?> <div class="display-product-details"></div> <?php
	$dialog_close=$product_details->close();
	echo $dialog_close;


	// edit products dialog
	$edit_products_dialog = new WFSEDialog('edit-products-dialog', esc_html('Edit Product'), null, true);
	$dialog_start=$edit_products_dialog->start();
	echo $dialog_start;

	?>
		<h4><?php echo esc_html('Update the product');?></h4>
		<p><?php echo esc_html('Changes will be saved automatically');?></p>

		<div class="display-editing">
			 
		</div>
	<?php



$dialog_close=$edit_products_dialog->close();

echo $dialog_close;

	/*LOADING AND ERROR DIALOGS*/

	?> 
	<div id='loading' data-show='dialog'> 
		<div class="dialog-content">
			<div class="dialog-content-nicer" style="background: #FFFF02;">
				<div class="dialog-body">
					<div class="dialog-body-nicer">
						<center><img src="<?php echo WFESM_PLUGIN_URL; ?>/icons/loader.gif"/ class="loginloader"></center>
					</div>
				</div>
			</div>
		</div>
	</div>


		
	<div id="success" data-show="dialog" data-zoom-out="dialog" data-close="#success">
	    <div class="dialog-content">
	    	<div class="dialog-content-nicer" style="background: #FFFF02;">
		        <div class="dialog-body" style="text-align: center; color: green;">
		        	<div class="dialog-body-nicer">
			            <div class="close" data-zoom-out="dialog" data-close="#success">&times;</div>
			            <img src="<?php echo WFESM_PLUGIN_URL; ?>/icons/success.png" style="float: left;"/>
			            <h3><?php echo esc_html('Loading Please Wait ...');?></h3>
			        </div>
		        </div>
		    </div>
	    </div>
	</div>



		<div id='error' data-show='dialog'  data-zoom-out='dialog' data-close='#error'> 
			<div class="dialog-content">
				<div class="dialog-content-nicer" style="background: #FFFF02;">
					<div class="dialog-body">
						<div class="dialog-body-nicer">
							<div class="close" data-zoom-out='dialog' data-close='#error'>&times;</div>
							<center><img src='<?php echo WFESM_PLUGIN_URL; ?>/icons/alert-triangle-red-32.png' style="float: left;" /> <h3><?php echo esc_html('Something went wrong');?></h3></center>
						</div>
					</div>
				</div>
			</div>
		</div>

		<style>

			.loader-wrapper{
				display: none;
			}

			.my-custom-ajax-loader{
				position: fixed;
				background:  rgba( 0, 0, 0, .5 );
				top:  0;
				right:  0;
				bottom:  0;
				left:  0;
				z-index:  99999999999999999999999999999999999999999;
				 /* this is what centers your element in the fixed wrapper*/
			  display: flex;
			  flex-flow: column nowrap;
			  justify-content: center; /* aligns on vertical for column */
			  align-items: center;
			  #opacity: 0;
			}

			.loader-content{
				background: white;
				color:  #000;
				font-weight: 800;
				padding:  20px;
				text-align: center;
				margin:  0 auto;
				border-radius: 5px;
				position: fixed;
			}
		</style>

		<div class="loader-wrapper">
			<div class='my-custom-ajax-loader'>
				<div class='loader-content'><?php echo esc_html('Please Wait ...');?></div>
			</div>
		</div>
	<?php 

}

}

if (!function_exists('wfesm_get_year')) {
function wfesm_get_year(){
	return "2021";
}
}