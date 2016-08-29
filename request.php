<?php 
class Request
{
	public function __get($name){
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