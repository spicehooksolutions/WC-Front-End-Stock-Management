<?php 
if ( ! class_exists( 'WFSEDialog' ) ) :

class WFSEDialog{


	const SYSTEM_NAME = 'WooCom';

	private $_id = '';
	private $_title = "";
	private $_footer = "";
	private $_dismissable = "";

	public function __construct($id, $title = '', $footer = '', $dismissable = true ){
		$this->_id = $id; 
		$this->_title = $title; 
		$this->_footer = $footer;
		$this->_dismissable = $dismissable;
	}

	public function start(){
		$dialog = '
			<div id="'.$this->_id.'" data-show="dialog" ';

			if($this->_dismissable){
			 
				$dialog .= ' data-zoom-out="dialog" data-close="#'.$this->_id.'">';
			} else {
				$dialog .= ' >';
			}

				$dialog .= '<div class="dialog-content the-main-dialog-content">
					<div class="dialog-content-nicer"> 
						<div class="dialog-header">
							<div class="dialog-header-nicer" style="position: relative;">';

							if($this->_id == "chat-room"):
								$dialog .= '<div class="pull-left chat-back" style="cursor: pointer; font-weight: 800;  padding: 2px 20px;"><span class="ri ri-arrow-left"></span> Back</div>';
							endif;

							$dialog .= '

								
								<h3>'.$this->_title.'</h3> 
								<div class="close" data-zoom-out="dialog" data-close="#'.$this->_id.'">&times;</div>
							</div>
						</div>

						<div class="dialog-body">
							<div class="dialog-body-nicer">
		';

		return ($dialog);
	}

	public function close(){
		$dialog = '
							</div>
							</div>

							<div class="dialog-footer">
								<div class="dialog-footer-nicer">
									'.$this->_footer.'
								</div>
							</div>
						</div>
					</div>
				</div>
		';

		return ($dialog); 
	}

	static function create_link( array $options ){
		
		if( !array_key_exists('text', $options) ){

			
			throw new Exception("Fatal Eerror, <strong>text</strong> is required as a key in the options array");
			return; 
		}


		if( !array_key_exists('target', $options) ){
			throw new Exception("Fatal Eerror, <strong>target</strong> is required as a key in the options array");
			return; 
		}

		if( !array_key_exists('tag', $options) ){
			throw new Exception("Fatal Eerror, <strong>tag</strong> is required as a key in the options array");
			return; 
		}


		
		$data = "<".$options['tag']." data-zoom-in='dialog' q-target='".$options['target']."'  ";

		// class
		$class = ""; 

		if( array_key_exists( 'class', $options ) ){
			$class = " class='"; 
			if( is_array( $options['class'] ) ){
				foreach ($options['class'] as $key => $value) {
					# code...
					$class .= $value."  ";
				}
			}	else {
				$class .= $options['class'];
			}

			$class .="'";
		}

		


		foreach ($options as $key => $value) {
			if( $key != 'tag' && $key != 'text' && $key != 'class' && $key !='target' )
				$data .=" $key='$value' "; 
		}

		


		$data .= " $class  >"; 

		$data .= $options['text'];


		$data .= "</".$options['tag'].">"; 

		return $data;

	}
}

endif;