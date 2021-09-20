<?php 
class Table{
	
	public static function start($theme = ""){
		echo "<div class='table-responsive wfesm_msc_table'>"; 
		echo "<table class='table table-bordered table-striped table-primary table-hover' >"; 
	}
	
	public static function header(array $heading){
		$headingData = "<tr style='background: ".wfesm_get_primary_color().";'>";
		for($i = 0; $i < count($heading); $i++ ){
			$headingData .= "<td><strong style='color: ".wfesm_get_text_color().";'>".$heading[$i]."</strong></td>";
		}
		$headingData .= "</tr>"; 
		
		echo esc_html($headingData);
	}
	
	public static function body(array $body){
		$bodyData = "<tr>";
		for($j = 0; $j < count($body); $j++ ){
			$bodyData .= "<td>".$body[$j]."</td>";
		}
		$bodyData .= "</tr>";
		
		echo esc_html($bodyData);
	}
	
	public static function create(array $heading, array $body){
		$headingData = "<tr>";
		for($i = 0; $i < count($heading); $i++ ){
			$headingData .= "<td><strong>".$heading[$i]."</strong></td>";
		}
		
		$headingData .= "</tr>"; 
		
		echo esc_html($headingData);
		
		$bodyData = "<tr>";
		for($j = 0; $j < count($body); $j++ ){
			$bodyData .= "<td>".$body[$j]."</td>";
		}
		$bodyData .= "</tr>";
		
		echo esc_html($bodyData);
	}
	
	public static function close(){
		echo "</table>"; 
		echo "</div>"; 
	}
}