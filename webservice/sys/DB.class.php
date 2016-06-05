<?php

class DB {

	function __construct(){

	}

	private static $instance;

	public static function getInstance(){

		if(!isset(self::$instance)){

			try{
				self::$instance =  new PDO('mysql:host='. DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
				self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				self::$instance->exec("set names utf8");
			}catch(PDOException $e){

			}
		}

		return self::$instance;
	}

	public static function query($sql, $params = null){
		
		try{

			$conn = self::getInstance();
			$stmt = $conn->prepare($sql);
			$stmt->execute($params);
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			return $result;
		
		} catch (PDOException $e) {
			return $e->getMessage();
		}

	}

	public static function exec($sql, $params = null){

		try{

			$conn = self::getInstance();
			$query = $conn->prepare($sql);
			$result = $query->execute($params);
			return $result;
			
		} catch (PDOException $e) {
			return $e->getMessage();
		}
		
	}

	public static function last_insert_id(){

		$conn = self::getInstance();
		return $conn->lastInsertId();
	}
}