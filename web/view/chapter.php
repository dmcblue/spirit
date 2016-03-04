<?php
	defined('SPIRIT') || die();
	if($page == 'chapter'){
		$menu[] = array('label' => $source->name, 'link' => 'index.php?page=source&source='.$source->id);
		if($source->display['page']['section']){
			$menu[] = array('label' => $section->name, 'link' => 'index.php?page=source&source='.$source->id.'&section='.$section->id);
		}
		
	}
	
	$chapter->loadChildren(false);
?>
<?php if($page == 'chapter'): ?>
<table class="select">
	<tbody>
	<?php endif; ?>
	<?php foreach($chapter->verses as $verse): ?>
		<tr class="verse" data-id="<?php echo $verse->id; ?>" 
			data-chapter="<?php echo $chapter->id; ?>" 
			data-citation-chapter="<?php echo $chapter->priority; ?>" 
			data-citation-verse="<?php echo $verse->name; ?>">
			<td class="verse_header"><?php echo $verse->name; ?></td>
			<td class="verse_text"><?php echo $verse->text; ?></td>
		</tr>
	<?php endforeach; ?>
	<?php if($page == 'chapter'): ?>
	</tbody>
</table>
<?php endif; ?>