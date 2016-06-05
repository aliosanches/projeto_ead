<?php

class Servico_Dao extends Dao{

	function __construct(){

		System::LoadModel('Servico');
	}

	function salvar($servico_model){

		try{

			if($this->existe($servico_model->id)){
				$servico_model = $this->update($servico_model);
			}else{
				$servico_model = $this->insert($servico_model);
			}

			return $servico_model;

		}catch(Exception $e){
			$obj_error = (object)array();
			$obj_error->mensagem = $e->getMessage();
			$obj_error->err_code = 1;
			Controller::ReturnResponse($obj_error);
		}
	}

	function existe($servico_id){

		$sql = "SELECT id FROM servico WHERE id = :servico_id ";
		$params = array();
		$params[':servico_id'] = $servico_id;
		$query = DB::query($sql, $params);


		if(count($query) > 0 && $query[0]['id'] > 0){
			return true;
		}

		return false;
	}

	function insert($servico_model){

		if(strlen($servico_model->nome) == 0){
			throw new Exception("Informe o nome", 1);
		}

		$sql = "INSERT INTO servico
				(
					nome,
					sessao_id
				)
				VALUES
				(
					:nome,
					:sessao_id
				) ";

		$params = array();
		$params[':nome'] = $servico_model->nome;
		$params[':sessao_id'] = System::$sessao_model->id;

		DB::exec($sql, $params);
		$servico_model->id = DB::last_insert_id();
		$servico_model = $this->popular($servico_model->id);

		return $servico_model;
	}

	function update($servico_model){

		if(strlen($servico_model->nome) == 0){
			throw new Exception("Informe o nome", 1);
		}

		if(!$this->existe($servico_model->id)){
			throw new Exception("Servico nao encontrado", 1);
		}

		$sql = "UPDATE servico SET 
					nome = :nome,
					sessao_id = :sessao_id
				WHERE id = :servico_id ";

		$params = array();
		
		$params[':nome'] = $servico_model->nome;
		$params[':sessao_id'] = System::$sessao_model->id;
		$params[':servico_id'] = $servico_model->id;
		DB::exec($sql, $params);
		
		$servico_model = $this->popular($servico_model->id);

		return $servico_model;
	}

	function delete($servico_model){

		if(!$this->existe($servico_model->id)){
			throw new Exception("Servico nao encontrado", 1);
		}

		$sql = "UPDATE servico SET 
					excluido = 'S'
				WHERE id = :servico_id ";

		$params = array();
		$params[':servico_id'] = $servico_model->id;
		DB::exec($sql, $params);
		
		$servico_model = $this->popular($servico_model->id);

		return $servico_model;
	}

	function popular($servico_id){

		if(!$this->existe($servico_id)){
			throw new Exception("Servico nao encontrado", 1);
		}

		$sql = "SELECT 
					id AS servico_id,
					excluido,
					nome
				FROM servico
				WHERE id = :servico_id ";

		$params = array();
		$params[':servico_id'] = $servico_id;
		$query = DB::query($sql, $params);

		return $this->preencher($query[0]);
	}

	function preencher($row_query){

		$servico_model = System::LoadModel('Servico', true);

		if(isset($row_query['servico_id']) && $row_query['servico_id'] > 0){

			$servico_model->id = $row_query['servico_id'];
			$servico_model->excluido = $row_query['excluido'];
			$servico_model->nome = $row_query['nome'];
		}

		return $servico_model;
	}

	function Listar(){
		
		$sql = "SELECT 
					id,
					nome 
				FROM servico 
				WHERE excluido = 'N' ";
		
		
		$query = DB::query($sql);

		return $query;
		
	}

}