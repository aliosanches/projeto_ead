<?php 

class Controller {

	public static function ReadPost($attributo, $deafult = null){

		if(isset($_POST[$attributo])){
			return $_POST[$attributo];
		}

		return $deafult;
	}

	public static function ReadGet($attributo, $deafult = null){
		
		if(isset($_GET[$attributo])){
			return $_GET[$attributo];
		}

		return $deafult;
	}

	public static function ReadRequest($attributo, $deafult = null){
		
		if(isset($_REQUEST[$attributo])){
			return $_REQUEST[$attributo];
		}

		return $deafult;
	}

	public static function ReturnResponse($retorno){
		print_r(json_encode($retorno));exit();
	}
}



