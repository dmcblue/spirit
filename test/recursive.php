<?php
	require_once('../load.php');
	
	$nl = '<br/>';
	$tb = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	
	$source = new SourceModel(array('id' => 1), true);
	$source->load();
	
	echo $source->name.$nl;
	foreach($source->sections as $i => $section){
		echo $tb.($i + 1).": ".$section->name.$nl;
		
		foreach($section->chapters as $j => $chapter){
			echo $tb.$tb.($j + 1).": ".$chapter->name.$nl;
			
			foreach($chapter->verses as $k => $verse){
				echo $tb.$tb.$tb.($k + 1).": ".$verse->text.$nl;
			}
		}
	}