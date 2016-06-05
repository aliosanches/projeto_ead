<?php

class Usuario_Controller extends Controller{

	function login(){

		try{

			if(strlen(System::$sessao_model->id) == 0){

				$usuario_dao = System::LoadDao('Usuario', true);
				$usuario = $this->ReadPost('usuario');
				$senha = $this->ReadPost('senha');
				System::$sessao_model = $usuario_dao->ValidarLogin($usuario, $senha);
			}
			
			$this->ReturnResponse(System::$sessao_model->md5);

		}catch(Exception $e){

			$obj_error = (object)array();
			$obj_error->mensagem = $e->getMessage();
			$obj_error->err_code = 1;
			$this->ReturnResponse($obj_error);
		}
	}

	function logout(){

		try{

			$sessao_dao = System::LoadDao('Sessao', true);
			System::$sessao_model = $sessao_dao->delete(System::$sessao_model);
			$this->ReturnResponse(true);

		}catch(Exception $e){
			$obj_error = (object)array();
			$obj_error->mensagem = $e->getMessage();
			$obj_error->err_code = 1;
			$this->ReturnResponse($obj_error);
		}
	}
}