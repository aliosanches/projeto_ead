<?php

class Servico_Controller extends Controller{

	function listar(){

		try{

			$servico_dao = System::LoadDao('Servico', true);
			$ret = $servico_dao->Listar();
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

			$servico_dao = System::LoadDao('Servico', true);
			$servico_model = System::LoadModel('Servico', true);

			$servico_id = $this->ReadPost('servico_id');
			if($servico_id > 0){
				$servico_model = $servico_dao->popular($servico_id);
			}

			$servico_model->nome = $this->ReadPost('nome');

			$servico_model = $servico_dao->salvar($servico_model);

			$this->ReturnResponse($servico_model);

		}catch(Exception $e){
			$obj_error = (object)array();
			$obj_error->mensagem = $e->getMessage();
			$obj_error->err_code = 1;
			$this->ReturnResponse($obj_error);
		}
	}

	function dados(){

		try{

			$servico_dao = System::LoadDao('Servico', true);
			$servico_model = System::LoadModel('Servico', true);
			$servico_id = $this->ReadGet('servico_id');
			$servico_model = $servico_dao->popular($servico_id);

			$this->ReturnResponse($servico_model);

		}catch(Exception $e){
			$obj_error = (object)array();
			$obj_error->mensagem = $e->getMessage();
			$obj_error->err_code = 1;
			$this->ReturnResponse($obj_error);
		}
	}

	function excluir(){

		try{

			$servico_dao = System::LoadDao('Servico', true);
			$servico_model = System::LoadModel('Servico', true);

			$servico_id = $this->ReadPost('servico_id');
			$servico_model = $servico_dao->popular($servico_id);
			$servico_model = $servico_dao->delete($servico_model);

			$this->ReturnResponse($servico_model);

		}catch(Exception $e){
			$obj_error = (object)array();
			$obj_error->mensagem = $e->getMessage();
			$obj_error->err_code = 1;
			$this->ReturnResponse($obj_error);
		}
	}
}