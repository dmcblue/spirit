<?php
	class SectionModel extends BaseModel{
		static public $table = 'section';
		static public $property_list =
			array(
				array('name' => 'id', 'type' => Type::INT),
				array('name' => 'name', 'type' => Type::STR),
				array('name' => 'sid', 'type' => Type::INT),
				array('name' => 'priority', 'type' => Type::INT),
			);
		
		public function __construct($data = array(), $load_children = false, $parent = null){
			parent::__construct(self::$property_list, $data, self::$table);
			
			$this->source = $parent;
			
			$this->chapters = array();
			if($load_children){
				$this->loadChildren($load_children);
			}
		}
		
		public function loadChildren($recursive){
			$query =
				"SELECT ".implode(' ,', array_map('DB::quoteName',array_map(function($var){return $var['name'];}, ChapterModel::$property_list)))." 
				FROM ".DB::quoteName(ChapterModel::$table)." 
				WHERE ".DB::quoteName('sid')." = :sid  
				ORDER BY ".DB::quoteName('priority')." ASC "
			;
			$params = array(DB::param(':sid', $this->id, Type::INT));
			$results = DB::query($query, $params);
			foreach($results as $props){
				$this->chapters[] = new ChapterModel($props, $recursive, $this);
			}
			return $this;
		}
	}