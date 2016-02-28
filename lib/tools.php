<?php
	class Tools{
		static public function protocol(){
			return 
				(!empty($_SERVER['HTTPS']) 
					&& $_SERVER['HTTPS'] !== 'off' 
					|| $_SERVER['SERVER_PORT'] == 443) 
				? "https" 
				: "http";
		}
		
		static public function thisAddress($protocol = false, $query = false){
			$url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
			$escaped_url =  
				($protocol ? self::protocol().':' : '' ) 
				. htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
			return $query
				? $escaped_url
				: str_replace('index.php','', explode('?', $escaped_url)[0]);
		}
	}