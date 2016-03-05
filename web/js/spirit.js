var spirit = spirit || (function(){
	var CLASS_SELECTED = 'selected';
	
	var init = false;
	
	//var source = 0;
	//var section = 0;
	//var chapter = 0;
	//var chapters = [];
	//var verses = [];
	//var texts = {};
	var quote = 
		{
			source : 0,
			section : 0,
			chapter : 0,
			chapters : [],
			verses : [],
			texts : {}
		};
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
				+ '&source=' + quote.source
				+ '&section=' + quote.section
				+ '&chapter=' + quote.chapter
				+ '&chapters=' + quote.chapters.join(',')
				+ '&verses=' + quote.verses.join(',')
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
			var quotetext = [];
			for(var i = 0, ilen = quote.verses.length; i < ilen; i++){
				var needsEllipse = !(i === 0 || ((quote.verses[i] - quote.verses[i - 1]) < 2));
				if(needsEllipse){
					quotetext.push('...');
				}
				quotetext.push(quote.texts[quote.verses[i]]);
			}
			return quotetext.join('\n');
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
				for(var i = 0, ilen = quote.verses.length; i < ilen; i++){
					items.push(citation.verses[quote.verses[i]]);
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
										.attr('rows','10')
										.attr('cols','20')
										.css('resize', 'none')
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
								.append(
									jQuery('<label>').text('Facebook:')
								)
								.append(
									//https://developers.facebook.com/docs/sharing/web
									jQuery('<span>')
										.text('Facebook')
										.click(function(){
											FB.ui({
												method: 'share',
												href: link('embed', true),
												caption : makeText() + '\n - ' + makeCitation()
											}, function(response){});
										})
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
					var index = inArray(chapter, quote.chapters);
					if(index !== -1){
						quote.chapters.splice(index, 1);
						var jndex = inArray(chapter, citation.chapters);
						if(jndex !== -1){
							citation.chapters.splice(jndex, 1);
						}
					}
					var index = inArray(id, quote.verses);
					if(index !== -1){
						quote.verses.splice(index, 1);
						quote.texts[id] = null;
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
					var index = inArray(chapter, quote.chapters);
					//console.log('index', index, chapter, quote.chapters);
					if(index < 0){
						quote.chapters.push(parseInt(chapter));
						citation.chapters.push(jthis.attr('data-citation-chapter'));
					}
					quote.verses.push(parseInt(id));
					quote.texts[id] = jthis.find('.verse_text').text();
					citation.verses[id] = jthis.attr('data-citation-verse');

					if(jQuery('.'+CLASS_SELECTED).length === 1){
						jQuery('#toolbox').animate({
							width: 'toggle'
						}, 650);
					}
				}

				quote.chapters.sort();
				quote.verses.sort();
				console.log(quote);
				console.log(makeCitation(), citation);
				var query = link('quote');

				console.log(link('quote'));
				console.log(link('embed', true));

				jQuery('#open').children('a').attr('href',link('quote', true));
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
				quote.source = parseInt(jQuery('#source_id').val());
				citation.source = jQuery('#source_name').val();
				quote.section = parseInt(jQuery('#section_id').val());
				citation.section = jQuery('#section_name').val();
				if(citation.section === ''){citation.section = null;}
				quote.chapter = parseInt(jQuery('#chapter_id').val());
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