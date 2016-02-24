<?php
	defined('SPIRIT') || die();
	if($source->display['page']['section']){
		$menu[] = array('label' => $source->name, 'link' => 'index.php?page=source&source='.$source->id);
	}
	
	$section->loadChildren(false);
	if($source->display['page']['chapter']){
		foreach($section->chapters as $chapter): ?>
			<a href="index.php?page=chapter&source=<?php 
				echo $source->id; ?>&section=<?php echo $section->id; 
				?>&chapter=<?php echo $chapter->id; ?>" class="button blue"><?php echo ($chapter->priority + 1).'. '.$chapter->name; ?></a><br/>
		<?php endforeach;
	}else{
		?>
		<table class="select">
			<tbody><?php
		foreach($section->chapters as $chapter){
			$_GET['id'] = $chapter->id;
			include('chapter.php');
		} 
		?>
			</tbody>
		</table><?php
	}
	