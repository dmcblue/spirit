<?php
	defined('SPIRIT') || die();
	$source->loadChildren(false);
	
	if($source->display['page']['section']){
		foreach($source->sections as $section): ?>
			<a href="index.php?page=section&source=<?php 
				echo $source->id; ?>&section=<?php echo $section->id; ?>" class="button blue"><?php 
				echo $section->name; ?></a><br/>
		<?php endforeach; ?>
		<br/><br/>
		<div class="info"><a href="index.php?page=description&source=<?php echo $source->id; 
				?>" target="_blank"><span class="info"></span> Information about this text</a></div>	
				
		<?php
	}else{
		foreach($source->sections as $section){
			$_GET['id'] = $section->id;
			include('section.php');
		}
	}
	