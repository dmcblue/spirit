var spirit = spirit || (function(){
	var CLASS_SELECTED = 'selected';
	
	var init = false;
	
	var source = 0;
	var section = 0;
	var chapter = 0;
	var chapters = [];
	var verses = [];
	var texts = {};
	var citation = 
		{
			source : null,
			section : null,
			chapters : [],
			verses : {}
		};
	var display = null;
	
	var query =
		function(page){
			return 'page=' + page							
				+ '&source=' + source
				+ '&section=' + section
				+ '&chapter=' + chapter
				+ '&chapters=' + chapters.join(',')
				+ '&verses=' + verses.join(',')
			;
		};
	var link =
		function(page, encode){
			encode = encode || false;
			return window.location.href.split('?')[0] 
				+ '?' + (encode ? btoa(query(page)) : query(page));
		};
	var embed =
		function(){
			var url = window.location.href.replace('http:','').replace('https:','').replace('index.php','').split('?')[0];
			return '<script type="text/javascript" src="'+url+'js/spirit.embed.js" data-quote="'+ btoa(query('embed'))+'"></script>';
		};
	var makeText =
		function(){
			var quote = [];
			for(var i = 0, ilen = verses.length; i < ilen; i++){
				var needsEllipse = !(i === 0 || ((verses[i] - verses[i - 1]) < 2));
				if(needsEllipse){
					quote.push('...');
				}
				quote.push(texts[verses[i]]);
			}
			return quote.join('\n');
		};
	var arrayToConseqString =
		function(arr, inter, jump){
			inter = inter || '-';
			jump = jump || ', ';
			var str = arr[0];
			var last = parseInt(arr[0]);
			var conseq = false;
			for(var i = 1, ilen = arr.length; i < ilen; i++){
				var item = parseInt(arr[i]);
				if(item === last + 1){
					conseq = true;
				}else{
					if(conseq){
						str += inter + last + jump + item;
					}
					conseq = false;
				}
				last = item;
			}
			if(conseq){
				str += inter + last;
			}
			return str;
		};
	var makeCitation =
		function(){
			console.log(display);
			var cite =
				citation.source
				+ ' | ';
			if(display.section){
				cite += citation.section === null ? '' : citation.section + ': ';
			}
			
			if(!display.chapter){
				cite += arrayToConseqString(citation.chapters) + ' ';
			}else{
				cite += citation.chapters[0] + ': ';
				
				var items = [];
				for(var i = 0, ilen = verses.length; i < ilen; i++){
					items.push(citation.verses[verses[i]]);
				}
				
				cite += arrayToConseqString(items);
			}
			return cite;
		};
	var inArray =
		function(val, arr, exact){
			exact = exact || false;
			for(var i = 0, ilen = arr.length; i < ilen; i++){
				if(exact ? arr[i] === val : arr[i] == val){
					return i;
				}
			}
			return -1;
		};
	var clickFocus =
		//http://stackoverflow.com/a/5797700
		function(){
			var $this = jQuery(this);
			$this.select();

			// Work around Chrome's little problem
			$this.mouseup(function() {
				// Prevent further mouseup intervention
				$this.unbind("mouseup");
				return false;
			});
		};
	return {
		CLASS_SELECTED : CLASS_SELECTED,
		
		clickEmbed : 
			function(){
				jQuery('body')
					.append(
						jQuery('<div>')
							.attr('id','dialog')
							.append(
								jQuery('<div>')
								.addClass('dialogContent')
								.append(
									jQuery('<label>').text('Embed code:')
								)
								.append(
									jQuery('<input>')
										.attr('type','text')
										.val(embed())
										.focus(clickFocus)
								)
								.append(
									jQuery('<label>').text('Text:')
								)
								.append(
									jQuery('<textarea>')
										.attr('type','text')
										.val(makeText() + '\n - ' + makeCitation())
										.focus(clickFocus)
								)
								.append(
									jQuery('<label>').text('Link:')
								)
								.append(
									jQuery('<input>')
										.attr('type','text')
										.val(link('quote', true))
										.focus(clickFocus)
								)
							)
					);
				jQuery('#dialog')
					.dialog({
						modal	: true,
						width	: 
							window.innerWidth > 300 
								? Math.round(window.innerWidth*0.6) 
								: window.innerWidth - 32,
						open	: 
							function(){
								jQuery('.ui-widget-overlay')
									.click(function(){
										jQuery('#dialog').dialog('close');
									});
							},
						close	: function(){jQuery(this).remove();}
					});
			},
		clickVerse :
			function(){
				var jthis = jQuery(this);
				var id = jthis.attr('data-id');
				var chapter = jthis.attr('data-chapter');
				if(jthis.hasClass(CLASS_SELECTED)){
					jthis.removeClass(CLASS_SELECTED);
					var index = inArray(chapter, chapters);
					if(index !== -1){
						chapters.splice(index, 1);
						var jndex = inArray(chapter, citation.chapters);
						if(jndex !== -1){
							citation.chapters.splice(jndex, 1);
						}
					}
					var index = inArray(id, verses);
					if(index !== -1){
						verses.splice(index, 1);
						texts[id] = null;
						citation.verses[id] = null;
					}
					//console.log(jQuery('.'+CLASS_SELECTED));
					if(!jQuery('.'+CLASS_SELECTED).length){
						jQuery('#toolbox').animate({
							width: 'toggle'
						}, 650);
					}

				}else{
					jthis.addClass(CLASS_SELECTED);
					var index = inArray(chapter, chapters);
					console.log('index', index, chapter, chapters);
					if(index < 0){
						chapters.push(parseInt(chapter));
						citation.chapters.push(jthis.attr('data-citation-chapter'));
					}
					verses.push(parseInt(id));
					texts[id] = jthis.find('.verse_text').text();
					citation.verses[id] = jthis.attr('data-citation-verse');

					if(jQuery('.'+CLASS_SELECTED).length === 1){
						jQuery('#toolbox').animate({
							width: 'toggle'
						}, 650);
					}
				}

				chapters.sort();
				verses.sort();
				console.log(source, section, chapters, chapter, verses);
				console.log(makeCitation(), citation);
				var query = link('quote');

				console.log(link('quote'));
				console.log(link('embed', true));

				jQuery('#open').children('a').attr('href',link('quote', true))
			},
		initMenu :
			function(){
				var menus = JSON.parse(decodeURIComponent(jQuery('#menu_info').val()));
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
			},
		init :
			function(){
				if(init){return;}
				init = true;
				source = parseInt(jQuery('#source_id').val());
				citation.source = jQuery('#source_name').val();
				section = parseInt(jQuery('#section_id').val());
				citation.section = jQuery('#section_name').val();
				if(citation.section === ''){citation.section = null;}
				chapter = parseInt(jQuery('#chapter_id').val());
				jQuery('.select').find('.verse').click(this.clickVerse);
				jQuery('#embed').click(this.clickEmbed);
				display = JSON.parse(decodeURIComponent(jQuery('#display').val()));
				this.initMenu();
			}
	};
})();
jQuery(document).ready(function(){
	spirit.init();
});