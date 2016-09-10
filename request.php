<?php 
class Request
{
	public function __construct()
    {
	}
	public function __get($name){
		if($name == "_all"){	// キケン!!!
			$ret = array_merge($_POST, $_GET);
			return $ret;
		}
		$post = filter_input(INPUT_POST, $name);
		$get = filter_input(INPUT_GET, $name);
		return $post ? $post : $get;
	}

	public function get_json(){
		$json_string = file_get_contents('php://input');
		$obj = json_decode($json_string);
		return $obj;
	}	
}

?>