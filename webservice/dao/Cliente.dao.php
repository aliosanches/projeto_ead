<?php

class Cliente_Dao extends Dao{

	function __construct(){

		System::LoadModel('Cliente');
	}

	function salvar($cliente_model){

		try{

			if($this->existe($cliente_model->id)){
				$cliente_model = $this->update($cliente_model);
			}else{
				$cliente_model = $this->insert($cliente_model);
			}

			return $cliente_model;

		}catch(Exception $e){
			
			$obj_error = (object)array();
			$obj_error->mensagem = $e->getMessage();
			$obj_error->err_code = 1;
			Controller::ReturnResponse($obj_error);
		}
	}

	function existe($cliente_id){

		$sql = "SELECT id FROM cliente WHERE id = :cliente_id ";
		$params = array();
		$params[':cliente_id'] = $cliente_id;
		$query = DB::query($sql, $params);
		if(count($query) > 0 && $query[0]['id'] > 0){
			return true;
		}

		return false;
	}

	function insert($cliente_model){

		if(strlen($cliente_model->nome) == 0){
			throw new Exception("Informe o nome", 1);
		}

		$sql = "INSERT INTO cliente
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
		$params[':nome'] = $cliente_model->nome;
		$params[':sessao_id'] = System::$sessao_model->id;

		DB::exec($sql, $params);
		$cliente_model->id = DB::last_insert_id();
		$cliente_model = $this->popular($cliente_model->id);

		return $cliente_model;
	}

	function update($cliente_model){

		if(!$this->existe($cliente_model->id)){
			throw new Exception("Cliente nao encontrado", 1);
		}

		if(strlen($cliente_model->nome) == 0){
			throw new Exception("Informe o nome", 1);
		}

		$sql = "UPDATE cliente SET 
					nome = :nome,
					sessao_id = :sessao_id
				WHERE id = :cliente_id ";

		$params = array();
		
		$params[':nome'] = $cliente_model->nome;
		$params[':sessao_id'] = System::$sessao_model->id;
		$params[':cliente_id'] = $cliente_model->id;
		DB::exec($sql, $params);
		
		$cliente_model = $this->popular($cliente_model->id);

		return $cliente_model;
	}

	function delete($cliente_model){

		if(!$this->existe($cliente_model->id)){
			throw new Exception("Cliente nao encontrado", 1);
		}

		$sql = "UPDATE cliente SET 
					excluido = 'S'
				WHERE id = :cliente_id ";

		$params = array();
		$params[':cliente_id'] = $cliente_model->id;
		DB::exec($sql, $params);
		
		$cliente_model = $this->popular($cliente_model->id);

		return $cliente_model;
	}

	function popular($cliente_id){

		if(!$this->existe($cliente_id)){
			throw new Exception("Cliente nao encontrado", 1);
		}

		$sql = "SELECT 
					id AS cliente_id,
					excluido,
					nome
				FROM cliente
				WHERE id = :cliente_id ";

		$params = array();
		$params[':cliente_id'] = $cliente_id;
		$query = DB::query($sql, $params);

		return $this->preencher($query[0]);
	}

	function preencher($row_query){

		$cliente_model = System::LoadModel('Cliente', true);

		if(isset($row_query['cliente_id']) && $row_query['cliente_id'] > 0){

			$cliente_model->id = $row_query['cliente_id'];
			$cliente_model->excluido = $row_query['excluido'];
			$cliente_model->nome = $row_query['nome'];
		}

		return $cliente_model;
	}

	function Listar(){
		
		$sql = "SELECT 
					id,
					nome 
				FROM cliente 
				WHERE excluido = 'N' ";
		
		
		$query = DB::query($sql);

		return $query;
		
	}

}