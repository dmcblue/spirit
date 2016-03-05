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
		
		static public function buildQuote($source, $section, 
				$chapter_ids, $verse_ids){
			$chapter_name = '';
			$citation = $source->name . ($source->display['page']['section'] ? ' - '.$section->name : '');
			$title = 
				'<span class="bold">'.$source->name.'</span>'
				. ($source->display['page']['section'] ? ' '.$section->name : '');
			$section->loadChildren(false);

			$verse_id = null;
			$verse_name = null;

			
			$text = array();
			$text_numbered = array();
			$number_by_priority = $source->display['page']['chapter'];

			foreach($section->chapters as $s_chapter){//all chapters
				if(in_array($s_chapter->id, $chapter_ids)){//pertinent chapters

					$s_chapter->loadChildren(false);

					foreach($s_chapter->verses as $c_verse){

						if(count($chapter_ids)){
							$loc_name = '';
							if($chapter_name != $s_chapter->name){
								$chapter_name = $s_chapter->name;
								$loc_name = $chapter_name;

								$title .= 
									($number_by_priority ? ' |' : ',')
									.' '
									.($s_chapter->priority + 1)
									.($number_by_priority ? ' : ' : '');
								
								$citation .= 
									($number_by_priority ? ' |' : ',')
									.' '
									.($s_chapter->priority + 1)
									.($number_by_priority ? ' : ' : '');
								
								$start_sub_quote = false;
							}
						}

						if(in_array($c_verse->id, $verse_ids)){
							if(!$start_sub_quote){
								if($number_by_priority){
									$title .= ($c_verse->priority);
									$citation .= ($c_verse->priority);
								}
								$first_verse = $c_verse->priority;
								$start_sub_quote = true;
							}


							$v_num  = $number_by_priority ? $c_verse->priority : $c_verse->id;
							if(!is_null($verse_id) && $verse_id < ($v_num - 1)){
								$text[] = ELLIPSE;
								$text_numbered[] = ELLIPSE;
								if($number_by_priority){
									$title .= ($first_verse == ($verse_name) ? '' : ' - '.($verse_name)).', '.($c_verse->priority);
									$citation .= ($first_verse == ($verse_name) ? '' : ' - '.($verse_name)).', '.($c_verse->priority);
								}
								$first_verse = $c_verse->priority;
							}
							$verse_id = $v_num;
							$verse_name = $c_verse->priority;
							if(count($chapter_ids)){
								$loc_name = '';
								if($chapter_name != $s_chapter->name){
									$chapter_name = $s_chapter->name;
									$loc_name = $chapter_name;
								}
							}
							$text[] = $c_verse->text;
							$text_numbered[] = '['.$c_verse->name.'] '. $c_verse->text;
						}
					}
					if($number_by_priority && ($first_verse != $verse_name)){
						$title .= ' - '.$verse_name;
						$citation .= ' - '.$verse_name;
					}
				}
			}
			return array(
				'title' => $title, 
				'text' => $text, 
				'text_numbered' => $text_numbered,
				'citation' => $citation,
			);
		}
	}