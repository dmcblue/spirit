<?php
	
	class BaseModel{
		protected $__props = array();
		protected $__table = '';
		
		public function __construct($property_list, $data = array(), $table){
			$this->__table = $table;
			foreach($property_list as $property){
				$this->addProperty(
					$data, 
					$property['name'], 
					$property['type'], 
					array_key_exists('default', $property) ? $property['default'] : null
				);
				$this->__props[] = $property;
			}
		}
		
		public function addProperty($data, $name, $type, $default = null){
			if(is_null($default)){
				$default = Type::getDefault($type);
			}
			
			$val = array_key_exists($name, $data) ? $data[$name] : $default;
			
			if($type === Type::INT){
				$val = (int)$val;
			}
			
			if($type === TYPE::JSN){
				if(is_string($val)){
					$val = json_decode($val, true);
				}
			}
			
			$this->$name = $val;
			
			return $this;
		}
		
		public function load(){
			$query =
				"SELECT ".implode(' ,', array_map('DB::quoteName',array_map(function($var){return $var['name'];}, $this->__props)))." 
				FROM ".DB::quoteName($this->__table)." 
				WHERE ".DB::quoteName('id')." = :id "
			;
			$params = array(DB::param(':id', $this->id, Type::INT));
			$props = DB::query($query, $params);
			foreach($this->__props as $property){
				$this->addProperty(
					$props[0], 
					$property['name'], 
					$property['type'], 
					array_key_exists('default', $property) ? $property['default'] : null
				);
			}
			return $this;
		}
		
		public function save(){
			$params = array();
			foreach($this->__props as $prop){
				if($prop['type'] === Type::JSN){
					$params[] = DB::param(':'.$prop['name'], json_encode($this->{$prop['name']}), Type::STR);
				}else{
					$params[] = DB::param(':'.$prop['name'], $this->{$prop['name']}, $prop['type']);
				}
			}
			if($this->id === 0){
				$query = 
					"INSERT INTO ".DB::quoteName($this->__table)." 
					(".implode(' ,', array_map('DB::quoteName',array_map(function($var){return $var['name'];}, $this->__props)))." )
					VALUES (".implode(' ,', array_map(function($var){return ':'.$var['name'];}, $this->__props))." ) "
				;
				
				$this->id = DB::insert($query, $params);
			}else{
				$sets = array();
				foreach($this->__props as $prop){
					if($prop['name'] != 'id'){
						$sets[] = DB::quoteName($prop['name'])." = :".$prop['name'];
					}
				}
				
				$query =
					"UPDATE ".DB::quoteName($this->__table)." 
					SET ".implode(' ,', $sets)."
					WHERE ".DB::quoteName('id')." = :id "
				;
				
				$this->id = DB::update($query, $params);
			}
			
		}
		
		public function delete($recursive = null){
			if(is_array($recursive)){
				$name = array_shift($recursive);
				//unset($recursive[0]);
				//$recursive = array_values($recursive);
				if(property_exists($this, $name)){
					foreach($this->$name as $child){
						$child->delete($recursive);
					}
				}
			}
			
			$params = array();
			$params[] = DB::param(':id', $this->id, Type::INT);

			$query =
				"DELETE FROM ".DB::quoteName($this->__table)." 
				WHERE ".DB::quoteName('id')." = :id "
			;
			//echo $query."\n";
			DB::update($query, $params);
		}
	}