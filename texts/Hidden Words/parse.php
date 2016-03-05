<?php
	require_once('../../load.php');
	
	$source = new SourceModel();
	$source->search(array('name' => 'The Hidden Words', 'version' => 'Official Translation'));
	//echo print_r($source, true)."\n";
	if(empty($source->id)){
		$source = 
			new SourceModel(
				array(
					'name' => 'The Hidden Words', 
					'description' => "&&ldquo;A work consisting of short passages revealed by Bahá’u’lláh in Persian and Arabic in 1857/58 during His exile in Baghdad, translated by Shoghi Effendi.&rdquo;<br/><br/>Copyright © Bahá''í International Community", 
					'version' => 'Official Translation', 
					'display' => '{"page":{"section":true,"chapter":false,"verse":false}}', 
					'link' => 'http://www.bahai.org/library/authoritative-texts/bahaullah/hidden-words/'
				)
			);
		$source->save();
	}
	$source->loadChildren(true);
	$source->load();
	foreach($source->sections as $section){
		$section->delete(array('chapters','verses'));
	}
	//die;
	function chapter($chapter, $sid, $p){
		echo $p."\n";
		$c = new ChapterModel();
		
		$lines = explode(PHP_EOL, $chapter);
		
		$top = explode('. ', $lines[0]);
		$c->name = $top[0];
		$c->sid = $sid;
		$c->priority = $p;
		$c->save();
		
		$v = new VerseModel();
		$v->name = $top[0];
		$v->cid = $c->id;
		
		$t = array();
		foreach($lines as $index => $line){
			if($index === 0){
				$t[] = array_key_exists(1, $top) ? $top[1] : $line;
			}else{
				$t[] = $line;
			}
		}
		$v->text = implode("\n", $t);
		
		$v->save();
	}
	$sections = array();
	$sections[] = new SectionModel();
	$sections[0]->sid = $source->id;
	$sections[0]->name = 'From The Arabic';
	$sections[0]->priority = 0;
	$sections[0]->save();
	
	$sections[] = new SectionModel();
	$sections[1]->sid = $source->id;
	$sections[1]->name = 'From The Persian';
	$sections[1]->priority = 1;
	$sections[1]->save();
	
	foreach($sections as $i => $section){
		$content = file_get_contents('part'.($i + 1).'.txt');

		$chapters = explode(PHP_EOL.PHP_EOL, $content);

		//echo count($chapters);

		//$chapter = $chapters[0];
		//$lines = explode("\r\n", $chapter);
		//foreach($lines as $line){
		//	echo $line."\n";
		//}

		foreach($chapters as $index => $chapter){
			chapter($chapter, $section->id, $index + $i);
		}
	}