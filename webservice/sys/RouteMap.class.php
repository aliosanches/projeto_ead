<?php 

class RouteMap {

	function __construct(){

	}

	public static function getRoute($route = null){

		$array_route = array(
			
			//Usuario.controller.php
			'usuario/login' => array('Usuario', 'login'), 
			'usuario/logout' => array('Usuario', 'logout'), 
			
			//Cliente.controller.php
			'cliente/listar' => array('Cliente', 'listar'), 
			'cliente/salvar' => array('Cliente', 'salvar'),
			'cliente/dados' => array('Cliente', 'dados'),
			'cliente/excluir' => array('Cliente', 'excluir'),

			//Servico.controller.php
			'servico/listar' => array('Servico', 'listar'), 
			'servico/salvar' => array('Servico', 'salvar'),
			'servico/dados' => array('Servico', 'dados'),
			'servico/excluir' => array('Servico', 'excluir'),

			//Contrato.controller.php
			'contrato/listar' => array('Contrato', 'listar'), 
			'contrato/salvar' => array('Contrato', 'salvar'),
			'contrato/dados' => array('Contrato', 'dados'),
			'contrato/excluir' => array('Contrato', 'excluir'),

		);

		if(!is_null($route) && isset($array_route[$route])){
			return $array_route[$route];
		}

		return false;
	}
}