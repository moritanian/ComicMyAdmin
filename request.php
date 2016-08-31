<?php 
class Request
{
	public function __get($name){
		if($name == "_all"){
			$ret = array_merge($_POST, $_GET);
			return $ret;
		}
		if(isset($_POST[$name])){
			return $_POST[$name];
		}
		if(isset($_GET[$name])){
			return $_GET[$name];
		}
		return null;
	}	
}

?>