<?php 

if ( ! class_exists( 'WFSEForm' ) ) :

class WFSEForm{
	
	public $_labelDistance; 
	private $_method;
	private $_options;  
	
	// $form = new WFSEForm(labelDistance, method, theme) -> this one is currently not supported
	public function __construct($labelDistance = 2, $method = "post", $options = null){
		// the default method will be post, you can change this when calling your class
		// $form = new WFSEForm(3, "GET", array('onsubmit="return false"', 'another option here'));
		$this->_labelDistance = $labelDistance;
		$this->_method = $method; 
		$this->_options = $options;

	}
	
	public function init(){
			if($this->_options == "" || $this->_options == null){
				$form =  "<form class='wfsmp-form-horizontal' method='".$this->_method."' > ";
			} else {
				$form =  "<form class='wfsmp-form-horizontal' method='".$this->_method."'  ";
				foreach ($this->_options as $value) {
					$form .= $value; 
				}
				$form .= " >";
			}

			echo $form; 

	}
	
	public function getDivDistance(){
		$divDistance = 12 - (int)$this->_labelDistance;
		return $divDistance;
	}

	public function info($info, $form_group_options = '' ){
		// this can be used to show important information on the form elements
		if( $form_group_options == '' ){
				$html =  "
				<div class='wfsmp-form-group wfsmp-grid-row'>
					<div class='col-box-12'>
						<div class='form-info'>
							$info
						</div>
					</div>  
				</div> 
			";  
		} else {
			$html =  "
			<div class='wfsmp-form-group wfsmp-grid-row'";

			foreach ($form_group_options as $key => $value) {
				$html .= " $key='$value' ";
			}

			$html .= ">
				<div class='col-box-12'>
					<div class='form-info'>
						$info
					</div>
				</div>  
			</div> 
		";  
		}

		echo $html; 
	}

	function textBox_( $label, array $options, $form_group_options = null,  $itShouldShowThePassword = true ){
		/*an empty string or an array */
		
		if($itShouldShowThePassword){

			if( !array_key_exists( 'type', $options ) ){
				$passwordManagerClass = ""; 
				$passwordRedable = ""; 
				$passwordHidden = ""; 
			} else {



				if( $options['type']  == "password"){
					$passwordManagerClass = "password-redable"; 
					$passwordRedable = "<div class='hide-the-password' ><img src='q-software/q-icons/hide-the-password.png' alt='hide the password' title='Hide the password' /></div>"; 
					$passwordHidden = "<div class='show-the-password'><img src='q-software/q-icons/show-the-password.png' alt='show the password' title='Show the password'  /></div>"; 
				} else {
					$passwordManagerClass = ""; 
					$passwordRedable = ""; 
					$passwordHidden = ""; 
				}
			}


		} else {
			$passwordManagerClass = ""; 
			$passwordRedable = ""; 
			$passwordHidden = ""; 
		}

		$input = ''; 

		if( !array_key_exists( 'type' ,  $options ) ){
			// we can call the input with default input type as text
			//name='$name' class='form-control'
			$input .= "<input type='text' ";

			if( !array_key_exists( 'class', $options ) ){
				$input .= " class='form-control' ";
			} else {
				$input .= " class='form-control ".$options['class']."' ";
			}

			// now we need to loop for remaining keys

			foreach ($options as $key => $value) {
				if( $key == 'class' || $key == 'type' ){

					// do nothing
				} else {
					$input .= " $key='$value' "; 
				}
			}


			$input .= " />";
		} else {
			$input .= "<input ";
			if( !array_key_exists( 'class', $options ) ){
				$input .= " class='form-control' ";
			} else {
				$input .= " class='form-control ".$options['class']."' ";
			}

			// now we need to loop for remaining keys

			foreach ($options as $key => $value) {
				if( $key != 'class' )
					$input .= " $key='$value' "; 

				
			}


			$input .= " />";
		}

		if( $form_group_options == null ){

			$form_data = "
				<div class='wfsmp-form-group wfsmp-grid-row'>
					<label class='col-box-".$this->_labelDistance."'>$label</label>
					<div class='col-box-".$this->getDivDistance()." $passwordManagerClass' > 
						$passwordRedable
						$passwordHidden
						$input
					</div> 
				</div> 
			";
		} else {
			$form_data = "
				<div class='wfsmp-form-group wfsmp-grid-row'
			";
			foreach ($form_group_options as $key => $value) {
				# code...
				$form_data .= " $key='$value' ";
			}


			$form_data .=">
					<label class='col-box-".$this->_labelDistance."'>$label</label>
					<div class='col-box-".$this->getDivDistance()." $passwordManagerClass' > 
						$passwordRedable
						$passwordHidden
						$input
					</div> 
				</div> ";
		}

		echo $form_data; 
		
	}
	
	public function textBox($label, $name, $type = "text",  $value = "",  $additinalAttr = null, $itShouldShowThePassword = true){/* $additinalAttr: it can take 
		an empty string or an array */
		
		if($itShouldShowThePassword){
			if($type == "password"){
				$passwordManagerClass = "password-redable"; 
				$passwordRedable = "<div class='hide-the-password' ><img src='".WFESM_PLUGIN_URL."/icons/hide-the-password.png' alt='hide the password' title='Hide the password' /></div>"; 
				$passwordHidden = "<div class='show-the-password'><img src='".WFESM_PLUGIN_URL."/icons/show-the-password.png' alt='show the password' title='Show the password'  /></div>"; 
			} else {
				$passwordManagerClass = ""; 
				$passwordRedable = ""; 
				$passwordHidden = ""; 
			}
		} else {
			$passwordManagerClass = ""; 
			$passwordRedable = ""; 
			$passwordHidden = ""; 
		}
		if($additinalAttr == "" || $additinalAttr == null){
			echo "
			<div class='wfsmp-form-group wfsmp-grid-row'>
				<label class='col-box-".$this->_labelDistance."'>$label</label>
				<div class='col-box-".$this->getDivDistance()." $passwordManagerClass' > 
					$passwordRedable
					$passwordHidden
					<input type='$type' name='$name' value='$value' class='form-control' /> 
				</div> 
			</div> 
		";
		} else {
			$formData = "
			<div class='wfsmp-form-group wfsmp-grid-row'>
				<label class='col-box-".$this->_labelDistance."'>$label</label>
				<div class='col-box-".$this->getDivDistance()." $passwordManagerClass'> 
					$passwordRedable
					$passwordHidden
					<input type='$type' name='$name' value='$value' class='form-control' "; 
					$start = 0; 
					
					while($start < count($additinalAttr)){
						$formData .= $additinalAttr[$start]." ";
						$start ++; 
					}
					
			$formData .= " /></div> 
			</div> 
			
		";
		
		echo $formData; 
		}
		
	}

	public function comboBox($label, $name, $value, array $options, array $optionValues, $selectedOption = "", $selectedOptionValue = ""){

		$select =  "
			<div class='wfsmp-form-group wfsmp-grid-row'>
				<label class='col-box-".$this->_labelDistance."'>$label</label>
				<div class='col-box-".$this->getDivDistance()."'> 
					<select class='form-control' name='$name' value='$value'> 
					<option value='$selectedOptionValue'>$selectedOption</option>"; 
				
					$start = 0; 
					while($start < count($options)){
						$select .= "<option value='".$optionValues[$start]."'>".$options[$start]."</option>";
						$start ++; 
		 			}
		$select .="
					</select>
				</div> 
			</div> 
		";
		
		echo $select; 

	}

	public function _select($label, $name, array $options, $form_group_options = null,  $select_attrs = ""){

		if( $form_group_options == null ){

			$select =  "
				<div class='wfsmp-form-group wfsmp-grid-row'>
					<label class='col-box-".$this->_labelDistance."'>$label</label>
					<div class='col-box-".$this->getDivDistance()."'> 
						<select class='form-control' name='$name'>";
			 			foreach ($options as $key => $value) {
			 				$select .= "<option value='".$key."'>".$value."</option>";
			 			}

						$select .="</select>
					</div> 
				</div> 
			";
		} else {

			$select =  "
				<div class='wfsmp-form-group wfsmp-grid-row'"; 

				foreach ($form_group_options as $key => $value) {
					$select .= " $key='$value' ";
				}


			$select .= ">
					<label class='col-box-".$this->_labelDistance."'>$label</label>
					<div class='col-box-".$this->getDivDistance()."'> 
						<select class='form-control' name='$name'>";
			 			foreach ($options as $key => $value) {
			 				$select .= "<option value='".$key."'>".$value."</option>";
			 			}

						$select .="</select>
					</div> 
				</div> 
			";
		
		}

		echo $select; 

	}



	
	public function select($label, $name, $value, $selectedItem, array $options, $selectedValue = ""){
		$select =  "
			<div class='wfsmp-form-group wfsmp-grid-row'>
				<label class='col-box-".$this->_labelDistance."'>$label</label>
				<div class='col-box-".$this->getDivDistance()."'> 
					<select class='form-control' name='$name' value='$value'> 
					<option value='$selectedValue'>$selectedItem</option>"; 
				
					$start = 0; 
					while($start < count($options)){
						$select .= "<option value='".$options[$start]."'>".$options[$start]."</option>";
						$start ++; 
		 			}
		$select .="
					</select>
				</div> 
			</div> 
		";
		
		echo $select; 
	}
	
	public function textarea($label, $name, $value = "", $additionalArgs = null){
		if($additionalArgs == "" || $additionalArgs == null){
			echo "
				<div class='wfsmp-form-group wfsmp-grid-row'>
					<label class='col-box-".$this->_labelDistance."'>$label</label>
					<div class='col-box-".$this->getDivDistance()."'> 
						<textarea class='form-control' name='$name' >$value</textarea>
					</div> 
				</div> 
			";
		} else {
			$textarea =  "
				<div class='wfsmp-form-group wfsmp-grid-row'>
					<label class='col-box-".$this->_labelDistance."'>$label</label>
					<div class='col-box-".$this->getDivDistance()."'> 
						<textarea class='form-control' name='$name' ";
						$start = 0; 
						while($start < count($additionalArgs)){
							$textarea .= $additionalArgs[$start]." "; 
							$start ++;
						}
		    $textarea .= ">$value</textarea>
					</div> 
				</div> ";

			echo $textarea;
		}
	}


	function formGroup($labelData, $value, $additionalArgs = null){
		if($additionalArgs == "" || $additionalArgs == null){
			$data = "
				<div class='wfsmp-form-group wfsmp-grid-row'>
					<label  class='col-box-".$this->_labelDistance."'>$labelData</label>
					<div  class='col-box-".$this->getDivDistance()."'> 
						<span>$value</span>
					</div> 
				</div>
			";
		} else {
			$data = "
				<div class='wfsmp-form-group wfsmp-grid-row'>
					<label  class='col-box-".$this->_labelDistance."'>$labelData</label>
					<div  class='col-box-".$this->getDivDistance()."'> 
						<span "; 

						$start = 0; 
						while($start < count($additionalArgs)){
							$data .= $additionalArgs[$start]; 
							$start ++; 
						}

						$data .= ">$value</span>
					</div> 
				</div>
			";
		}

		echo $data;
	}
	
	// form must be closed after it's initialized
	public function close($value = "", $name = '' ){
		if($value == "" || $value == null){
			echo "
			<div class='wfsmp-form-group wfsmp-grid-row'>
				<label class='col-box-".$this->_labelDistance."'></label>
				<div class='col-box-".$this->getDivDistance()."'></div> 
			</div> 
		";
		echo "</form>"; 
		} else {
			if( $name === '' ){
				$name = '';
			} else {
				$name = 'name="'.$name.'"';
			}
			echo "
			<div class='wfsmp-form-group wfsmp-grid-row'>
				<label class='col-box-".$this->_labelDistance."'></label>
				<div class='col-box-".$this->getDivDistance()."'> 
					<input type='submit'  $name value='$value' class='wfsmp-btn wfsmp-btn-primary' /> 
				</div> 
			</div> 
		";
		echo "</form>"; 
		}
	}


	public function closeWithIcon($value, $icon, $options){
		if($value == "" || $value == null){
			echo "
			<div class='wfsmp-form-group wfsmp-grid-row'>
				<label class='col-box-".$this->_labelDistance."'></label>
				<div class='col-box-".$this->getDivDistance()."'></div> 
			</div> 
		";
		echo "</form>"; 
		} else {
			if($options == null || $options == ""){
					echo "
					<div class='wfsmp-form-group wfsmp-grid-row'>
						<label class='col-box-".$this->_labelDistance."'></label>
						<div class='col-box-".$this->getDivDistance()."'> 
							<!--<input type='submit' value='$value' class='wfsmp-btn wfsmp-btn-primary' />--> 
							<button class='wfsmp-btn wfsmp-btn-primary'><span class='$icon' ></span> $value</button>
						</div> 
					</div> 
				";
				echo "</form>"; 
			} else {
				$form = "
					<div class='wfsmp-form-group wfsmp-grid-row'>
						<label class='col-box-".$this->_labelDistance."'></label>
						<div class='col-box-".$this->getDivDistance()."'> 
							<!--<input type='submit' value='$value' class='wfsmp-btn wfsmp-btn-primary' />--> 
							<button class='wfsmp-btn wfsmp-btn-primary' "; 


							foreach ($options as $key => $data) {
								$form .= $data;
							}

							$form .= "><span class='$icon' ></span> $value</button>
						</div> 
					</div> 
				";
				$form .=  "</form>"; 

				echo $form; 
			}
		}
	}

	public function end($value, $options = null){
		if($value == "" || $value == null){
			echo "
			<div class='wfsmp-form-group wfsmp-grid-row'>
				<label class='col-box-".$this->_labelDistance."'></label>
				<div class='col-box-".$this->getDivDistance()."'></div> 
			</div> 
		";
		echo "</form>"; 
		} else {
			$form =  "
			<div class='wfsmp-form-group wfsmp-grid-row'>
				<label class='col-box-".$this->_labelDistance."'></label>
				<div class='col-box-".$this->getDivDistance()."'> 
					<input type='submit' value='$value' "; 

					foreach ($options as $value) {
						$form .= $value;
					}

					$form .=" class='wfsmp-btn wfsmp-btn-primary' /> 
				</div> 
			</div> 
		";

		$form .="</form>"; 

		echo $form; 
		}
	}

	public function check_box( $name, $checked = false, $additionalArgs = null ){
		
		$check_box = '';

		if( $checked ){
			$checked = "checked='checked'";
		} else {
			$checked = '';
		}

		if($additionalArgs == "" || $additionalArgs == null){
			$check_box = "<input type=checkbox name='$name' $checked  >"; 
		} else {

		}

		return  $check_box;
	}


	public static function submit(array $items, $callBackFunction){
		if(isset($_POST[$items[0]])){
			$arrayItems = count($items); 
			$dataToPass = ""; 
			foreach ($items as $key => $value) {
				$lastItem = $key + 1;
				if($lastItem == $arrayItems){
					$dataToPass .= ($_POST[''.$value.'']);
				} else {
					$dataToPass .= ($_POST[''.$value.'']).", "; 
				}
			}

			echo "<?php $callBackFunction($dataToPass); ?>";
		}
	} 



	
}
endif;