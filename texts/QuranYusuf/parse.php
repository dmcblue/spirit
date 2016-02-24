<?php
	require_once('../../load.php');
	
	$nl = "\n";
	$tb = "\t";
	
	$source_id = 5;
	
	$source = new SourceModel(array('id' => $source_id), true);
	//echo $source->id;die;
	$source->load();
	//die;
	foreach($source->sections as $section){
		//echo 'Section Id: '.$section->id.$nl;
		$section->delete(array('chapters','verses'));
	}
	die;
	echo 'Delete old info'.$nl;
	//die;
	$section = new SectionModel();
	$section->sid = $source_id;
	$section->name = 'Quran';
	$section->priority = 0;
	$section->save();
	echo 'New Section '.$section->id.$nl;
	$content = file_get_contents('quran.txt');

	$lines = explode("\r\n\r\n", $content);

	$chapter_count = 0;
	$verse_count = 0;
	$regex = '/[0-9]{3}.[0-9]{3}/';
	foreach($lines as $index => $line){
		$parts = explode('  ', $line);
		
		if(!preg_match($regex, $parts[0])/*count($parts) > 2*/){
			///*
			$chapter = new ChapterModel();
			$chapter->name = str_replace(',', '', $parts[0]);
			$chapter->sid = $section->id;
			$chapter->priority = $chapter_count++;
			$chapter->save();
			//*/
			echo $tb.'New Chapter! '.$chapter->id.' - '.str_replace(',', '', $parts[0]).$nl;
			
			$verse_count = 0;
			continue;
		}
		///*
		$verse = new VerseModel();
		$verse->name = array_shift($parts);
		$verse->text = str_replace("\r\n", "", implode(' ', $parts));
		$verse->cid = $chapter->id;
		$verse->priority = $verse_count++;
		$verse->save();
		//*/
		//echo $tb.$tb.'New Verse! '.$verse->id.$nl;
	}