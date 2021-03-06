<?php
	$chapter_ids = explode(',', get('chapters', ''));
	$verse_ids = explode(',', get('verses', ''));
	if($page == 'chapter'){
		$menu[] = array('label' => $source->name, 'link' => 'index.php?page=source&source='.$source->id);
		if($source->display['page']['section']){
			$menu[] = array('label' => $section->name, 'link' => 'index.php?page=source&source='.$source->id.'&section='.$section->id);
		}
		
	}
	
	//require('build_quote.php');
	$content = Tools::buildQuote($source, $section, $chapter_ids, $verse_ids, $isNumbered = false);
?><!DOCTYPE html><html class="embed">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $CONFIG->site_name.': '.$source->name; ?></title>
		<meta property="og:title" content="<?php echo $CONFIG->site_name.' - '.$content['citation']; ?>" />
		<meta property="og:type" content="books.book" />
		<meta property="og:url" content="<?php echo Tools::thisAddress(true, true); ?>" />
		<meta property="og:description" content="<?php 
			echo str_replace(
				array('"', "\r\n","\n"), 
				array("'", '',''), 
				implode(' ', $content['text_numbered'])
			); 
			?>" />
		<meta property="og:site_name" content="<?php echo $CONFIG->site_name; ?>" />
		<meta property="og:locale" content="en_GB" />
		<meta property="og:image" content="<?php echo Tools::thisAddress(true, false); ?>css/images/opengraph.jpg" />
			<meta property="og:image:type" content="image/jpeg" />
			<meta property="og:image:width" content="365" />
			<meta property="og:image:height" content="480" />
		
		<link href="css/main.css" rel="stylesheet"></link>
		<link href='https://fonts.googleapis.com/css?family=EB+Garamond&subset=latin,latin-ext' rel='stylesheet' type='text/css' /></link>
		<style>
			
		</style>
		
		<script type="text/javascript" src="js/iframeResizer.contentWindow.min.js"></script>
	</head>
	<body class="center">
		<div class="content">
			<div class="quote">
				<?php foreach($content['text'] as $line): ?>
					<div <?php echo $line == ELLIPSE ? 'class="ellipse"' : ''; 
						?>><?php echo $line; ?></div>
				<?php endforeach; ?>
			</div>
		</div>
		<div>
			<div class="page_footer">
				<div class="home_link">
					<a target="_blank" href="index.php"><?php echo $CONFIG->site_name; ?></a>
				</div>
				<div class="citation"><?php echo $content['title']; ?></div>
			</div>
		</div>
		
		<script>
			var iFrameResizer = {
					messageCallback: function(message){
						alert(message,parentIFrame.getId());
					}
				}
		</script>
	</body>
</html>