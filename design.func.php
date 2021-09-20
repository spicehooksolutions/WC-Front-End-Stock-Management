<?php
add_action( 'wp_head', 'wfesm_head_scripts' );
function wfesm_head_scripts( $admin = false ){
	
	wp_enqueue_style('wcfsm-datatable-css',plugins_url( '/css/jquery.dataTables.min.css', __FILE__ ));
	wp_enqueue_script( 'wcfsm-datatable-js', plugins_url( '/js/jquery.dataTables.min.js', __FILE__ ), array('jquery') );
	
	wp_enqueue_style('wcfsm-dialogs',plugins_url( '/css/dialog.css', __FILE__ ));
	wp_enqueue_style('wcfsm-table-custom',plugins_url( '/css/table-custom.css', __FILE__ ));
	
	wp_enqueue_script( 'wcfsm-dialogs-js', plugins_url( '/js/dialog.js', __FILE__ ), array('jquery') );
	wp_enqueue_style('wcfsm-custom-css',plugins_url( '/css/wcfsm.custom.css', __FILE__ ));

	wp_enqueue_script( 'wcfsm-custom-js', plugins_url( '/js/wcfsm.custom.js', __FILE__ ), array('jquery') );
}


function wfesm_get_primary_color(){
	return "#7ad03a"; 
}


function wfesm_get_dark_color(){
	return "#25A353"; 
}

function wfesm_get_text_color(){
	return "#FFF";
}