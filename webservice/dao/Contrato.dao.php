<?php

class Contrato_Dao extends Dao{

	function __construct(){

		System::LoadModel('Contrato');
	}

	function salvar($contrato_model){

		try{

			if($this->existe($contrato_model->id)){
				$contrato_model = $this->update($contrato_model);
			}else{
				$contrato_model = $this->insert($contrato_model);
			}

			return $contrato_model;

		}catch(Exception $e){

			$obj_error = (object)array();
			$obj_error->mensagem = $e->getMessage();
			$obj_error->err_code = 1;
			Controller::ReturnResponse($obj_error);
		}
	}

	function existe($contrato_id){

		$sql = "SELECT id FROM contrato WHERE id = :contrato_id ";
		$params = array();
		$params[':contrato_id'] = $contrato_id;
		$query = DB::query($sql, $params);
		if(count($query) > 0 && $query[0]['id'] > 0){
			return true;
		}

		return false;
	}

	function validar ($contrato_model){

		$validar = array();
		if(strlen($contrato_model->cliente_id) == 0){
			$validar[] = 'Informe o Cliente';
		}

		if(strlen($contrato_model->servico_id) == 0){
			$validar[] = 'Informe o Servico';
		}

		if(strlen($contrato_model->data_inicio) == 0){
			$validar[] = 'Informe a data de inico';
		}

		if(strlen($contrato_model->data_fim) == 0){
			$validar[] = 'Informe data de fim';
		}

		if(
			strlen($contrato_model->data_fim) > 0 
			&& strlen($contrato_model->data_inicio) > 0
		)
		{
			
			if($contrato_model->data_fim < $contrato_model->data_inicio){
				$validar[] = 'A data de fim nao pode ser menor que a de inicio';
			}
		}

		return $validar;
		
	}

	function insert($contrato_model){

		if(count($this->validar($contrato_model)) > 0){
			throw new Exception(json_encode($this->validar($contrato_model)), 1);
		}
		

		$sql = "INSERT INTO contrato
				(
					cliente_id,
					servico_id,
					data_inicio,
					data_fim,
					sessao_id
				)
				VALUES
				(
					:cliente_id,
					:servico_id,
					:data_inicio,
					:data_fim,
					:sessao_id
				) ";

		$params = array();
		$params[':cliente_id'] = $contrato_model->cliente_id;
		$params[':servico_id'] = $contrato_model->servico_id;
		$params[':data_inicio'] = $contrato_model->data_inicio;
		$params[':data_fim'] = $contrato_model->data_fim;
		$params[':sessao_id'] = System::$sessao_model->id;

		DB::exec($sql, $params);
		$contrato_model->id = DB::last_insert_id();
		$contrato_model = $this->popular($contrato_model->id);

		return $contrato_model;
	}

	function update($contrato_model){

		if(!$this->existe($contrato_model->id)){
			throw new Exception("Contrato nao encontrado", 1);
		}

		if(count($this->validar($contrato_model)) > 0){
			throw new Exception(json_encode($this->validar($contrato_model)), 1);
		}

		$sql = "UPDATE contrato SET 
					cliente_id = :cliente_id,
					servico_id = :servico_id,
					data_inicio = :data_inicio,
					data_fim = :data_fim,
					sessao_id = :sessao_id
				WHERE id = :contrato_id ";

		$params = array();
		
		$params[':cliente_id'] = $contrato_model->cliente_id;
		$params[':servico_id'] = $contrato_model->servico_id;
		$params[':data_inicio'] = $contrato_model->data_inicio;
		$params[':data_fim'] = $contrato_model->data_fim;
		$params[':sessao_id'] = System::$sessao_model->id;
		$params[':contrato_id'] = $contrato_model->id;
		DB::exec($sql, $params);
		
		$contrato_model = $this->popular($contrato_model->id);

		return $contrato_model;
	}

	function delete($contrato_model){

		if(!$this->existe($contrato_model->id)){
			throw new Exception("Contrato nao encontrado", 1);
		}

		$sql = "UPDATE contrato SET 
					excluido = 'S'
				WHERE id = :contrato_id ";

		$params = array();
		$params[':contrato_id'] = $contrato_model->id;
		DB::exec($sql, $params);
		
		$contrato_model = $this->popular($contrato_model->id);

		return $contrato_model;
	}

	function popular($contrato_id){

		if(!$this->existe($contrato_id)){
			throw new Exception("Contrato nao encontrado", 1);
		}

		$sql = "SELECT 
					id AS contrato_id,
					excluido,
					cliente_id,
					servico_id,
					data_inicio,
					data_fim
				FROM contrato
				WHERE id = :contrato_id ";

		$params = array();
		$params[':contrato_id'] = $contrato_id;
		$query = DB::query($sql, $params);

		return $this->preencher($query[0]);
	}

	function preencher($row_query){

		$contrato_model = System::LoadModel('Contrato', true);

		if(isset($row_query['contrato_id']) && $row_query['contrato_id'] > 0){

			$contrato_model->id = $row_query['contrato_id'];
			$contrato_model->excluido = $row_query['excluido'];
			$contrato_model->cliente_id = $row_query['cliente_id'];
			$contrato_model->servico_id = $row_query['servico_id'];
			$contrato_model->data_inicio = $row_query['data_inicio'];
			$contrato_model->data_fim = $row_query['data_fim'];
		}

		return $contrato_model;
	}

	function Listar(){
		
		$sql = "SELECT 
					contrato.id,
					contrato.cliente_id,
					contrato.servico_id,
					DATEDIFF(contrato.data_fim, contrato.data_inicio)+1 AS dias,
					cliente.nome AS cliente_nome,
					servico.nome AS servico_nome 
				FROM contrato
				INNER JOIN cliente ON (cliente.id = contrato.cliente_id) 
				INNER JOIN servico ON (servico.id = contrato.servico_id)
				WHERE contrato.excluido = 'N' ";
		
		
		$query = DB::query($sql);

		return $query;
		
	}

}