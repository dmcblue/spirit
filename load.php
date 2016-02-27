<?php
	define('DS', DIRECTORY_SEPARATOR);
	$lib = 'lib';
	$base = realpath(dirname(__FILE__));
	require_once($base.DS.$lib.DS.'Config.php');
	require_once($base.DS.$lib.DS.'db.php');
	require_once($base.DS.$lib.DS.'type.php');
	
	function Spirit_autoload ($class){
		//$class = strtolower($class);
		if(strpos($class, 'Model') !== false){
			$path = realpath(dirname(__FILE__).DS.'model'.DS.str_replace('model','',strtolower($class)).'.php');
			if($path){
				return include $path;
			}
		}
		return false;
	}
	
	spl_autoload_register('Spirit_autoload');