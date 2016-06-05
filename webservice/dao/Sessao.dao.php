<?php

class Sessao_Dao extends Dao{

	function __construct(){

		System::LoadModel('Sessao');
	}

	function salvar($sessao_model){

		try{

			if($this->existe($sessao_model->id)){
				$sessao_model = $this->update($sessao_model);
			}else{
				$sessao_model = $this->insert($sessao_model);
			}

			return $sessao_model;

		}catch(Exception $e){
			$obj_error = (object)array();
			$obj_error->mensagem = $e->getMessage();
			$obj_error->err_code = 1;
			Controller::ReturnResponse($obj_error);
		}
	}

	function existe($sessao_id){

		$sql = "SELECT id FROM sessao WHERE id = :sessao_id ";
		$params = array();
		$params[':sessao_id'] = $sessao_id;
		$query = DB::query($sql, $params);
		if(count($query) > 0 && $query[0]['id'] > 0){
			return true;
		}

		return false;
	}

	function insert($sessao_model){

		$sql = "INSERT INTO sessao
				(
					insert_hora,
					usuario_id,
					md5,
					ultimo_acesso
				)
				VALUES
				(
					:insert_hora,
					:usuario_id,
					:md5,
					:ultimo_acesso
				) ";

		$params = array();
		$params[':insert_hora'] = $sessao_model->insert_hora;
		$params[':usuario_id'] = $sessao_model->usuario_id;
		$params[':md5'] = $sessao_model->md5;
		$params[':ultimo_acesso'] = $sessao_model->ultimo_acesso;

		DB::exec($sql, $params);
		$sessao_model->id = DB::last_insert_id();
		$sessao_model = $this->popular($sessao_model->id);

		return $sessao_model;
	}

	function update($sessao_model){

		$sql = "UPDATE sessao SET 
					ultimo_acesso = :ultimo_acesso 
				WHERE id = :sessao_id ";

		$params = array();
		$params[':ultimo_acesso'] = $sessao_model->ultimo_acesso;
		$params[':sessao_id'] = $sessao_model->id;
		DB::exec($sql, $params);
		
		$sessao_model = $this->popular($sessao_model->id);

		return $sessao_model;
	}

	function delete($sessao_model){

		$sql = "UPDATE sessao SET 
					excluido = 'S'
				WHERE id = :sessao_id ";

		$params = array();
		$params[':sessao_id'] = $sessao_model->id;
		DB::exec($sql, $params);
		
		$sessao_model = $this->popular($sessao_model->id);

		return $sessao_model;
	}

	function popular($sessao_id){

		$sql = "SELECT 
					id AS sessao_id,
					excluido,
					usuario_id,
					md5,
					ultimo_acesso
				FROM sessao
				WHERE id = :sessao_id ";

		$params = array();
		$params[':sessao_id'] = $sessao_id;
		$query = DB::query($sql, $params);

		return $this->preencher($query[0]);
	}

	function popularMd5($md5, $error=false){

		$sql = "SELECT 
					id AS sessao_id,
					excluido,
					usuario_id,
					md5,
					ultimo_acesso
				FROM sessao
				WHERE md5 = :md5 ";

		$params = array();
		$params[':md5'] = $md5;
		$query = DB::query($sql, $params);
		
		$sessao_model = System::LoadModel('Sessao', true);
		if(count($query) > 0){
			$sessao_model = $this->preencher($query[0]);
			$sessao_model->ultimo_acesso = $this->DateNow();
			$sessao_model = $this->update($sessao_model);
		}

		if(count($query) == 0 && $error === true){
			throw new Exception("Sessao nao encontrada", 1);
		}else{
			return $sessao_model;
		}
		
	}

	function preencher($row_query){

		$sessao_model = System::LoadModel('sessao', true);

		if(isset($row_query['sessao_id']) && $row_query['sessao_id'] > 0){

			$sessao_model->id = $row_query['sessao_id'];
			$sessao_model->excluido = $row_query['excluido'];
			$sessao_model->usuario_id = $row_query['usuario_id'];
			$sessao_model->md5 = $row_query['md5'];
			$sessao_model->ultimo_acesso = $row_query['ultimo_acesso'];
		}

		return $sessao_model;
	}


}