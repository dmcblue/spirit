<?php
	class Type{
		const INT = PDO::PARAM_INT;	//1
		const STR = PDO::PARAM_STR;	//2
		const BOL = PDO::PARAM_BOOL;//5
		const JSN = 6;
		
		protected static $defaults =
			array(
				self::INT => 0,
				self::STR => '',
				self::BOL => false,
				self::JSN => array(),
			);
		
		static public function getDefault($type){
			if(array_key_exists($type, self::$defaults)){
				return self::$defaults[$type];
			}
			throw new Exception('Not a known Type. No default.');
		}
	}