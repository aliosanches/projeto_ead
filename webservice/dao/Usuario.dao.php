<?php

class Usuario_Dao extends Dao{

	function __construct(){

		System::LoadModel('Usuario');
	}

	function salvar($usuario_model){

		try{

			if($this->existe($usuario_model->id)){
				$usuario_model = $this->update($usuario_model);
			}else{
				$usuario_model = $this->insert($usuario_model);
			}

			return $usuario_model;

		}catch(Exception $e){
			$obj_error = (object)array();
			$obj_error->mensagem = $e->getMessage();
			$obj_error->err_code = 1;
			Controller::ReturnResponse($obj_error);
		}
	}

	function existe($usuario_id){

		$sql = "SELECT id FROM usuario WHERE id = :usuario_id ";
		$params = array();
		$params[':usuario_id'] = $usuario_id;
		$query = DB::query($sql, $params);
		if(count($query) > 0 && $query[0]['id'] > 0){
			return true;
		}

		return false;
	}

	function insert($usuario_model){

		$sql = "INSERT INTO usuario
				(
					nome,
					email,
					senha
				)
				VALUES
				(
					:nome,
					:email,
					:senha
				) ";

		$params = array();
		$params[':nome'] = $usuario_model->nome;
		$params[':email'] = $usuario_model->email;
		$params[':senha'] = $usuario_model->senha;

		DB::exec($sql, $params);
		$usuario_model->id = DB::last_insert_id();
		$usuario_model = $this->popular($usuario_model->id);

		return $usuario_model;
	}

	function update($usuario_model){

		if(!$this->existe($usuario_model->id)){
			throw new Exception("Usuario nao encontrado", 1);
		}

		$sql = "UPDATE usuario SET 
					nome = :nome, 
					email = :email, 
					senha = :senha 
				WHERE id = :usuario_id ";

		$params = array();
		$params[':usuario_id'] = $usuario_model->id;
		DB::exec($sql, $params);
		
		$usuario_model = $this->popular($usuario_model->id);

		return $usuario_model;
	}

	function delete($usuario_model){

		if(!$this->existe($usuario_model->id)){
			throw new Exception("Usuario nao encontrado", 1);
		}

		$sql = "UPDATE usuario SET 
					excluido = 'S'
				WHERE id = :usuario_id ";

		$params = array();
		$params[':usuario_id'] = $usuario_model->id;
		DB::exec($sql, $params);
		
		$usuario_model = $this->popular($usuario_model->id);

		return $usuario_model;
	}

	function popular($usuario_id){

		if(!$this->existe($usuario_id)){
			throw new Exception("Usuario nao encontrado", 1);
		}

		$sql = "SELECT 
					id AS usuario_id,
					excluido,
					nome,
					email,
					senha
				FROM usuario
				WHERE id = :usuario_id ";

		$params = array();
		$params[':usuario_id'] = $usuario_id;
		$query = DB::query($sql, $params);

		return $this->preencher($query[0]);
	}

	function preencher($row_query){

		$usuario_model = System::LoadModel('Usuario', true);

		if(isset($row_query['usuario_id']) && $row_query['usuario_id'] > 0){

			$usuario_model->id = $row_query['usuario_id'];
			$usuario_model->excluido = $row_query['excluido'];
			$usuario_model->nome = $row_query['nome'];
			$usuario_model->email = $row_query['email'];
			$usuario_model->senha = $row_query['senha'];
		}

		return $usuario_model;
	}

	function ValidarLogin($usuario, $senha){
		
		$sql = "SELECT id FROM usuario WHERE nome = :usuario AND senha = :senha ";
		
		$params = array();
		$params[':usuario'] = $usuario;
		$params[':senha'] = $senha;
		$query = DB::query($sql, $params);

		if(count($query) > 0 && $query[0]['id'] > 0){
			//carregos os objetos da sessao
			$sessao_dao = System::LoadDao('Sessao', true);
			$sessao_model = System::LoadModel('Sessao', true);

			$sessao_model->usuario_id = $query[0]['id'];
			$sessao_model->md5 = md5($query[0]['id'].microtime());
			$sessao_model->insert_hora = $this->DateNow();
			$sessao_model->ultimo_acesso = $this->DateNow();
			$sessao_model = $sessao_dao->salvar($sessao_model);

			return $sessao_model;
		}

		throw new Exception("Login invalido", 1);
		
	}

}