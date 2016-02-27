<?php
	require_once('../../load.php');
	
	$nl = "\n";
	$tb = "\t";
	
	$source = new SourceModel();
	$source->search(array('name' => 'The Holy Quran', 'version' => 'Progressive Muslims Organization'));
	if(empty($source->id)){
		$source = 
			new SourceModel(
				array(
					'name' => 'The Holy Quran', 
					'description' => "&ldquo;During the seventh century A.D. a man by the name of 'Mohammed,' who was from the lineage of Abraham, was given the overwhelming task of being God''s messenger to deliver the words of the Almighty to mankind.\n<br/><br/>\nThe message that was given to this prophet represented a culmination of all previous teachings/laws, as well as a recording of the most accurate human history in relation to God.&rdquo;", 
					'version' => 'Progressive Muslims Organization', 
					'display' => '{"page":{"section":false,"chapter":true,"verse":false}}', 
					'link' => 'http://www.free-minds.org/quran/'
				)
			);
		$source->save();
	}
	$source->loadChildren(true);
	$source->load();
	//die;
	foreach($source->sections as $section){
		//echo 'Section Id: '.$section->id.$nl;
		$section->delete(array('chapters','verses'));
	}
	
	echo 'Delete old info'.$nl;
	//die;
	$section = new SectionModel();
	$section->sid = $source->id;
	$section->name = 'Quran';
	$section->priority = 0;
	$section->save();
	echo 'New Section '.$section->id.$nl;
	$content = file_get_contents('surahs.txt');

	$surahs = explode(PHP_EOL.PHP_EOL, $content);

	$chapter_count = 0;
	$verse_count = 0;
	echo 'Surahs'.count($surahs).$nl;
	//$regex = '/[0-9]{3}.[0-9]{3}/';
	foreach($surahs as $index => $surah){
		$lines = explode(PHP_EOL, $surah);
		
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