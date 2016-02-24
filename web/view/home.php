<?php
	//$sources = SourceModel::loadAll();
	
	foreach($sources as $source): ?>
		<div class="source_button">
			<a href="index.php?page=source&source=<?php echo $source->id; 
				?>" class="button blue"><?php echo $source->name; ?></a>
			<div class="info"><a href="index.php?page=description&source=<?php echo $source->id; 
				?>" target="_blank"><span class="info"></span></a></div>
		</div>
	<?php endforeach;