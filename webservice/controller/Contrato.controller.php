<?php

class Contrato_Controller extends Controller{

	function listar(){

		try{

			$contrato_dao = System::LoadDao('Contrato', true);
			$ret = $contrato_dao->Listar();
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

			$contrato_dao = System::LoadDao('Contrato', true);
			$contrato_model = System::LoadModel('Contrato', true);

			$contrato_id = $this->ReadPost('contrato_id');
			if($contrato_id > 0){
				$contrato_model = $contrato_dao->popular($contrato_id);
			}

			$contrato_model->cliente_id = $this->ReadPost('cliente_id');
			$contrato_model->servico_id = $this->ReadPost('servico_id');
			$contrato_model->data_inicio = $this->ReadPost('data_inicio');
			$contrato_model->data_fim = $this->ReadPost('data_fim');

			$contrato_model = $contrato_dao->salvar($contrato_model);

			$this->ReturnResponse($contrato_model);

		}catch(Exception $e){

			$obj_error = (object)array();
			$obj_error->mensagem = $e->getMessage();
			$obj_error->err_code = 1;
			$this->ReturnResponse($obj_error);
		}
	}

	function dados(){

		try{

			$contrato_dao = System::LoadDao('Contrato', true);
			$contrato_model = System::LoadModel('Contrato', true);
			$contrato_id = $this->ReadGet('contrato_id');
			$contrato_model = $contrato_dao->popular($contrato_id);

			$this->ReturnResponse($contrato_model);

		}catch(Exception $e){
			$obj_error = (object)array();
			$obj_error->mensagem = $e->getMessage();
			$obj_error->err_code = 1;
			$this->ReturnResponse($obj_error);
		}
	}

	function excluir(){

		try{

			$contrato_dao = System::LoadDao('Contrato', true);
			$contrato_model = System::LoadModel('Contrato', true);

			$contrato_id = $this->ReadPost('contrato_id');
			$contrato_model = $contrato_dao->popular($contrato_id);
			$contrato_model = $contrato_dao->delete($contrato_model);

			$this->ReturnResponse($contrato_model);

		}catch(Exception $e){
			$obj_error = (object)array();
			$obj_error->mensagem = $e->getMessage();
			$obj_error->err_code = 1;
			$this->ReturnResponse($obj_error);
		}
	}
}