<?php
	define('ELLIPSE', '. . .');
	define('SPIRIT',1);
	
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
		<title><?php echo $CONFIG->site_name; ?></title>
		<meta property="og:title" content="<?php echo $CONFIG->site_name; ?>" />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="<?php echo Tools::thisAddress(true, false); ?>" />
		<meta property="og:description" content="A site for sharing uplifting quotes." />
		<meta property="og:site_name" content="<?php echo $CONFIG->site_name; ?>" />
		<meta property="og:locale" content="en_GB" />
		<meta property="og:image" content="<?php echo Tools::thisAddress(true, false); ?>css/images/opengraph.jpg" />
			<meta property="og:image:type" content="image/jpeg" />
			<meta property="og:image:width" content="365" />
			<meta property="og:image:height" content="480" />
		
		<link href="css/main.css" rel="stylesheet"></link>
		<link href="css/jquery-ui.min.css" rel="stylesheet"></link>
		<link href='https://fonts.googleapis.com/css?family=EB+Garamond&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
		<style>
			
		</style>
		<script src="js/jquery-2.1.1.min.js" type="text/javascript"></script>
		<script src="js/jquery-ui.min.js" type="text/javascript"></script>
		<script src="//platform.tumblr.com/v1/share.js" type="text/javascript"></script>
		<script>
			window.fbAsyncInit = function() {
				FB.init({
					appId      : '1584665628420826',
					xfbml      : true,
					version    : 'v2.5'
				});
			};

			(function(d, s, id){
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) {return;}
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_US/sdk.js";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
		</script>
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
			<div id="embed" class="toolbox_button" title="Get embed code">
				<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
					<circle cx="17.5" cy="50" r="17.5"/>
					<circle cx="82.5" cy="82.5" r="17.5"/>
					<circle cx="82.5" cy="17.5" r="17.5"/>
					<rect x="14.6" y="31.6" transform="matrix(0.8949 -0.4462 0.4462 0.8949 -9.6513 25.9896)" width="71.6" height="3.8"/>
					<rect x="14.2" y="64.6" transform="matrix(0.8949 0.4462 -0.4462 0.8949 34.9203 -15.3242)" width="71.6" height="3.8"/>
				</svg>
			</div>
			<div id="open" class="toolbox_button" title="Open in new tab">
				<a target="_blank"><svg data-name="open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><polygon points="95 95 5 95 5 5 50 5 50 0 0 0 0 100 100 100 100 50 95 50 95 95" /><polygon points="70 0 100 30 100 0 70 0"/><rect x="22.5" y="35" width="75" height="10" transform="translate(-10.71 54.14) rotate(-45)"/></svg></a>
			</div>
		</div>
		
		
		<?php require_once('view/'.$page.'.php'); ?>
		
		<!-- End content -->
		<input id="app_name" value="<?php echo $CONFIG->site_name; ?>" type="hidden" />
		<input id="source_id" value="<?php echo $source->id; ?>" type="hidden" />
		<input id="source_name" value="<?php echo $source->name; ?>" type="hidden" />
		<input id="section_id" value="<?php echo $section_id; ?>" type="hidden" />
		<input id="section_name" value="<?php echo isset($section) ? $section->name : ''; ?>" type="hidden" />
		<input id="chapter_id" value="<?php echo $chapter_id; ?>" type="hidden" />
		<input id="display" value="<?php echo htmlspecialchars(json_encode($source->display['page'])); ?>" type="hidden" />
		<input id="menu_info" value="<?php echo htmlspecialchars(json_encode($menu)); ?>" type="hidden" />
		<script src="js/spirit.js" type="text/javascript"></script>
		<script>
			jQuery(document).ready(function(){
				
			});
		</script>
	</body>
</html><?php else: require_once('view/'.$page.'.php'); endif;
