<?php
	defined('SPIRIT') || die();
	$chapter_ids = explode(',', get('chapters', ''));
	$verse_ids = explode(',', get('verses', ''));
	if($page == 'chapter'){
		$menu[] = array('label' => $source->name, 'link' => 'index.php?page=source&source='.$source->id);
		if($source->display['page']['section']){
			$menu[] = array('label' => $section->name, 'link' => 'index.php?page=source&source='.$source->id.'&section='.$section->id);
		}
		
	}
	
	require('build_quote.php');
	
	
?>
<?php if($title): ?><div class="page_header"><?php echo $title; ?></div><?php endif; ?>
<div class="quote">
	<?php foreach($text as $line): ?>
		<div <?php echo $line == ELLIPSE ? 'class="ellipse"' : ''; 
			?>><?php echo $line; ?></div>
	<?php endforeach; ?>
</div>