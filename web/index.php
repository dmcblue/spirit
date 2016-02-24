<?php
	define('ELLIPSE', '. . .');
	define('SPIRIT',1);
	define('NAME', 'spirit.com');
	require_once('../load.php');
	
	$get = $_GET;
	if(count($_GET) == 1){
		$pairs = explode('&', base64_decode(key($_GET)));
		foreach($pairs as $pair){
			$item = explode('=', $pair);
			$get[$item[0]] = $item[1];
		}
	}
	
	function get($name, $default){
		GLOBAL $get;
		return isset($get[$name]) ? $get[$name] : $default;
	}
	
	$page = get('page', 'home');
	$title = null;
	
	$sources = SourceModel::loadAll();
	$source = new SourceModel(array('id' => get('source', $sources[0]->id)));
	$source->load();
	if($page == 'source' || $page == 'description'){$title = $source->name;}
	else{$title = 'Please select a text';}
	
	$section_id = get('section', 0);
	if(!empty($section_id)){
		$section = new SectionModel(array('id' => $section_id), false, $source);
		$section->load();
		$title = $section->name;
	}
	
	$chapter_id = get('chapter', 0);
	if(!empty($chapter_id)){
		$chapter = new ChapterModel(array('id' => $chapter_id), false, $section);
		$chapter->load();
		$title = $chapter->name;
	}
	
	$verse_id = get('verse', 0);
	if(!empty($verse_id)){
		$verse = new VerseModel(array('id' => $verse_id), false, $chapter);
		$verse->load();
		$title = $verse->name;
	}
	
	if($page == 'quote'){$title = '';}
	
	$menu = array(array('label' => 'Home', 'link' => 'index.php'));
	
	$standalone = array('embed');
	if(!in_array($page, $standalone)):
?><!DOCTYPE html><html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="css/main.css" rel="stylesheet"></link>
		<link href="css/jquery-ui.min.css" rel="stylesheet"></link>
		<link href='https://fonts.googleapis.com/css?family=EB+Garamond&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
		<style>
			
		</style>
		<script src="js/jquery-2.1.1.min.js"></script>
		<script src="js/jquery-ui.min.js"></script>
		<script src="js/spirit.js"></script>
		<script>
			
		</script>
	</head>
	<body class="center">
		
		<div id="menu"></div>
		<div id="buffer<?php echo empty($title) ? '2' : '2'; ?>"></div>
		<div class="page_header">
			<?php echo $title; ?>
		</div>
		<div id="toolbox" style="display:none;">
			<div id="embed" class="toolbox_button" title="Get embed code">&lt;/&gt;</div>
			<div id="open" class="toolbox_button" title="Open in new tab">
				<a target="_blank"><svg data-name="open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><polygon points="95 95 5 95 5 5 50 5 50 0 0 0 0 100 100 100 100 50 95 50 95 95" /><polygon points="70 0 100 30 100 0 70 0"/><rect x="22.5" y="35" width="75" height="10" transform="translate(-10.71 54.14) rotate(-45)"/></svg></a>
			</div>
		</div>
		
		
		<?php require_once('view/'.$page.'.php'); ?>
		
		<!-- End content -->
		<input id="source_id" value="<?php echo $source->id; ?>" type="hidden" />
		<input id="section_id" value="<?php echo $section_id; ?>" type="hidden" />
		<input id="chapter_id" value="<?php echo $chapter_id; ?>" type="hidden" />
		<script>
			jQuery(document).ready(function(){
				var menus = JSON.parse('<?php echo json_encode($menu); ?>');
				var menu = jQuery('#menu');
				for(var i = 0, ilen = menus.length; i < ilen; i++){
					var item = menus[i];
					if(i !== 0){
						menu.append(' &gt; ');
					}
					menu.append(
						jQuery('<a>')
							.attr('href', item.link)
							.text(item.label)
							.addClass('light_color')
					);
				}
			});
		</script>
	</body>
</html><?php else: require_once('view/'.$page.'.php'); endif;
