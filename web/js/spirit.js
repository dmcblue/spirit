var spirit = spirit || (function(){
	var CLASS_SELECTED = 'selected';
	
	var init = false;
	
	var source = 0;
	var section = 0;
	var chapter = 0;
	var chapters = [];
	var verses = [];
	
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
	return {
		CLASS_SELECTED : CLASS_SELECTED,
		
		init :
			function(){
				if(init){return;}
				init = true;
				source = parseInt(jQuery('#source_id').val());
				section = parseInt(jQuery('#section_id').val());
				chapter = parseInt(jQuery('#chapter_id').val());
				jQuery('.select').find('.verse')
					.click(function(){
						var jthis = jQuery(this);
						var id = jthis.attr('data-id');
						var chapter = jthis.attr('data-chapter');
						if(jthis.hasClass(CLASS_SELECTED)){
							jthis.removeClass(CLASS_SELECTED);
							var index = inArray(chapter, chapters);
							if(index !== -1){
								chapters.splice(index, 1);
							}
							var index = inArray(id, verses);
							if(index !== -1){
								verses.splice(index, 1);
							}
							console.log(jQuery('.'+CLASS_SELECTED));
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
							}
							verses.push(parseInt(id));
							
							if(jQuery('.'+CLASS_SELECTED).length === 1){
								jQuery('#toolbox').animate({
									width: 'toggle'
								}, 650);
							}
						}
						
						chapters.sort();
						verses.sort();
						console.log(source, section, chapters, chapter, verses);
						var query = link('quote');
						
						console.log(link('quote'));
						console.log(link('embed', true));
						
						jQuery('#open').children('a').attr('href',link('quote', true))
					});
				jQuery('#embed')
					.click(function(){
						console.log('Embed!');
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
												//http://stackoverflow.com/a/5797700
												.focus(function() {
													var $this = jQuery(this);
													$this.select();

													// Work around Chrome's little problem
													$this.mouseup(function() {
														// Prevent further mouseup intervention
														$this.unbind("mouseup");
														return false;
													});
												})
										)
										.append(
											jQuery('<label>').text('Link:')
										)
										.append(
											jQuery('<input>')
												.attr('type','text')
												.val(link('quote', true))
												//http://stackoverflow.com/a/5797700
												.focus(function() {
													var $this = jQuery(this);
													$this.select();

													// Work around Chrome's little problem
													$this.mouseup(function() {
														// Prevent further mouseup intervention
														$this.unbind("mouseup");
														return false;
													});
												})
										)
									)
							);
						jQuery('#dialog')
							.dialog({
								modal	: true,
								width	: window.innerWidth > 300 ? Math.round(window.innerWidth*0.6) : window.innerWidth - 32,
								open	: 
									function(){
										jQuery('.ui-widget-overlay')
											.click(function(){
												jQuery('#dialog').dialog('close');
											});
									},
								close	: function(){jQuery(this).remove();}
							});
					});
			}
	};
})();
jQuery(document).ready(function(){
	spirit.init();
});