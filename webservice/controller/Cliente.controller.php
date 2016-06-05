<?php

class Cliente_Controller extends Controller{

	function listar(){

		try{

			$cliente_dao = System::LoadDao('Cliente', true);
			$ret = $cliente_dao->Listar();
			$this->ReturnResponse($ret);

		}catch(Exception $e){
			$obj_error = (object)array();
			$obj_error->mensagem = $e->getMessage();
			$obj_error->err_code = 1;
			$this->ReturnResponse($obj_error);
		}
	}

	function salvar(){

		try{

			$cliente_dao = System::LoadDao('Cliente', true);
			$cliente_model = System::LoadModel('Cliente', true);

			$cliente_id = $this->ReadPost('cliente_id');
			if($cliente_id > 0){
				$cliente_model = $cliente_dao->popular($cliente_id);
			}

			$cliente_model->nome = $this->ReadPost('nome');

			$cliente_model = $cliente_dao->salvar($cliente_model);

			$this->ReturnResponse($cliente_model);

		}catch(Exception $e){
			$obj_error = (object)array();
			$obj_error->mensagem = $e->getMessage();
			$obj_error->err_code = 1;
			$this->ReturnResponse($obj_error);
		}
	}

	function dados(){

		try{

			$cliente_dao = System::LoadDao('Cliente', true);
			$cliente_model = System::LoadModel('Cliente', true);
			$cliente_id = $this->ReadGet('cliente_id');
			$cliente_model = $cliente_dao->popular($cliente_id);

			$this->ReturnResponse($cliente_model);

		}catch(Exception $e){
			$obj_error = (object)array();
			$obj_error->mensagem = $e->getMessage();
			$obj_error->err_code = 1;
			$this->ReturnResponse($obj_error);
		}
	}

	function excluir(){

		try{

			$cliente_dao = System::LoadDao('Cliente', true);
			$cliente_model = System::LoadModel('Cliente', true);

			$cliente_id = $this->ReadPost('cliente_id');
			$cliente_model = $cliente_dao->popular($cliente_id);
			$cliente_model = $cliente_dao->delete($cliente_model);

			$this->ReturnResponse($cliente_model);

		}catch(Exception $e){
			$obj_error = (object)array();
			$obj_error->mensagem = $e->getMessage();
			$obj_error->err_code = 1;
			$this->ReturnResponse($obj_error);
		}
	}
}