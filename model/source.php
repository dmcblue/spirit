<?php
	class SourceModel extends BaseModel{
		static public $table = 'source';
		static public $property_list =
			array(
				array('name' => 'id', 'type' => Type::INT),
				array('name' => 'name', 'type' => Type::STR),
				array('name' => 'description', 'type' => Type::STR),
				array('name' => 'version', 'type' => Type::STR),
				array('name' => 'display', 'type' => Type::JSN),
				array('name' => 'link', 'type' => Type::STR),
			);
		
		static public function loadAll(){
			$query =
				"SELECT ".implode(' ,', array_map('DB::quoteName',array_map(function($var){return $var['name'];}, self::$property_list)))." 
				FROM ".DB::quoteName(self::$table)." "
			;
			$params = array();
			
			$all = array();
			$results = DB::query($query, $params);
			foreach($results as $row){
				$all[] = new SourceModel($row, false);
			}
			return $all;
		}
		
		public function __construct($data = array(), $load_children = false){
			parent::__construct(self::$property_list, $data, self::$table);
			$this->sections = array();
			if($load_children){
				$this->loadChildren($load_children);
			}
		}
		
		public function loadChildren($recursive){
			$query =
				"SELECT ".implode(' ,', array_map('DB::quoteName',array_map(function($var){return $var['name'];}, SectionModel::$property_list)))." 
				FROM ".DB::quoteName(SectionModel::$table)." 
				WHERE ".DB::quoteName('sid')." = :sid 
				ORDER BY ".DB::quoteName('priority')." ASC "
			;
			$params = array(DB::param(':sid', $this->id, Type::INT));
			$results = DB::query($query, $params);
			foreach($results as $props){
				$this->sections[] = new SectionModel($props, $recursive, $this);
			}
			return $this;
		}
	}