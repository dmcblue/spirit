<?php
	class VerseModel extends BaseModel{
		static public $table = 'verse';
		static public $property_list =
			array(
				array('name' => 'id', 'type' => Type::INT),
				array('name' => 'name', 'type' => Type::STR),
				array('name' => 'text', 'type' => Type::STR),
				array('name' => 'cid', 'type' => Type::INT),
				array('name' => 'priority', 'type' => Type::INT),
			);
		
		public function __construct($data = array(), $parent = null){
			parent::__construct(self::$property_list, $data, self::$table);
			
			$this->chapter = $parent;
		}
	}