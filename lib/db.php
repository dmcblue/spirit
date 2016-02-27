<?php

	class DB{
		static protected $connection = null;
		
		static public function quoteName($name){
			return '`'.$name.'`';
		}
		
		static public function connect(){
			if(is_null(self::$connection)){
				GLOBAL $CONFIG;
				self::$connection =
					new PDO(
					"mysql:host={$CONFIG->db_host};dbname={$CONFIG->db_name};charset=utf8",
					$CONFIG->db_user,
					$CONFIG->db_pass,
					array(
						PDO::ATTR_EMULATE_PREPARES => false,
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
					)
				);
			}
			return self::$connection;
		}
		
		static function sql($query, $params = array()){
			$db = self::connect();
			$stmt = $db->prepare($query);
			foreach($params as $param){
				$stmt->bindValue($param->name, $param->value, $param->type);
			}
			try{
				$stmt->execute();
			}catch(PDOException $e){
				echo $e;
			}
			return $stmt;
		}
		static function query($query, $params){
			return self::sql($query, $params)->fetchAll(PDO::FETCH_ASSOC);
		}
		static function insert($query, $params){
			$db = self::connect();
			self::sql($query, $params);
			return $db->lastInsertId();
		}
		static function update($query, $params){
			self::sql($query, $params);
		}
		static function param($name, $value, $type){
			$param = new stdClass();
			$param->name = $name;
			$param->value = $value;
			$param->type = $type;
			return $param;
		}
	}