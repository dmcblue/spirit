<?php
	require_once('../../load.php');
	
	$nl = "\n";
	$tb = "\t";
	
	$source_id = 6;
	
	$source = new SourceModel(array('id' => $source_id), true);
	//echo $source->id;die;
	$source->load();
	//die;
	foreach($source->sections as $section){
		//echo 'Section Id: '.$section->id.$nl;
		$section->delete(array('chapters','verses'));
	}
	
	echo 'Delete old info'.$nl;
	//die;
	$section = new SectionModel();
	$section->sid = $source_id;
	$section->name = 'Quran';
	$section->priority = 0;
	$section->save();
	echo 'New Section '.$section->id.$nl;
	$content = file_get_contents('surahs.txt');

	$surahs = explode("\r\n\r\n", $content);

	$chapter_count = 0;
	$verse_count = 0;
	echo 'Surahs'.count($surahs).$nl;
	//$regex = '/[0-9]{3}.[0-9]{3}/';
	foreach($surahs as $index => $surah){
		$lines = explode("\r\n", $surah);
		
		foreach($lines as $jindex => $line){
			if($jindex == 0){
				//*
				$chapter = new ChapterModel();
				$chapter->name = trim($line);
				$chapter->sid = $section->id;
				$chapter->priority = $chapter_count++;
				$chapter->save();
				//*/
				$verse_count = 0;
				echo '--'.trim($line).': '.$index.': '.count($lines).$nl;
			}else{
				//*
				$parts = explode("\t", $line);
				$ps = explode(':', $parts[0]);
				$verse = new VerseModel();
				$verse->name = $ps[1];
				$verse->text = $parts[1];
				$verse->cid = $chapter->id;
				$verse->priority = $verse_count++;
				$verse->save();
				//*/
			}
		}
	}