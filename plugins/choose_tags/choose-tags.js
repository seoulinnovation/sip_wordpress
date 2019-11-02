(function($) {
	$(document).ready(function(){
		$('#new-tag-post_tag').suggest(ajaxurl+"?action=ajax-tag-search&tax=post_tag", {multiple:true, multipleSep: ","});
		$('.button.tagadd').click(function(){
			var tagchecklist = $('.tagchecklist');			
			var tags = $('#new-tag-post_tag').val().split(',');
			var existtags = $('.tagchecklist span').clone();
			var pushtags = [];
			var startidnum;
			if(existtags){
				$.each(existtags, function(k, v){					
					$(v).find('a').remove();
					var text = $(v).text().trim();
					pushtags.push(text);
				});
				startidnum = existtags.length;
			} else {
				startidnum = 0;
			}
			$.each(tags, function(k, v){
				if(v != ' ' && v!= '') {
					v = v.trim();
					var inarray = $.inArray(v, pushtags);
					if(inarray >= 0) return true;
					var span = $('<span />');
					var a = $('<a />').attr('id', 'post_tag-check-num-'+startidnum).addClass('ntdelbutton').attr('tabindex', startidnum).append('X');					
					span.append(a).append('&nbsp;'+v);
					tagchecklist.append(span);
					if($('textarea.the-tags').text().length > 0){
						$('textarea.the-tags').text($('textarea.the-tags').val().trim()+','+v);
					} else {
						$('textarea.the-tags').text(v);
					}
					startidnum++;
				}
			});
			$('#new-tag-post_tag').val('').focus();		
		});
		
		$('#new-tag-post_tag').keydown(function(e){
			if(e.which == 13){
				$('.button.tagadd').click();
				event.preventDefault();
				return false;				
			}
		});
		
		$('a.ntdelbutton').live('click', function(){
			$(this).parent().remove();			
			var existtags = $('.tagchecklist span a').get();
			$('textarea.the-tags').text('');
			$.each(existtags, function(k,v){
				$(v).attr('id', 'post_tag-check-num-'+k).attr('tabindex', k);
				var val = $('.tagchecklist span:eq('+k+')').clone().children().remove().end().text().trim();
				if($('textarea.the-tags').text().length > 0){
					$('textarea.the-tags').text($('textarea.the-tags').val()+','+val);
				} else {
					$('textarea.the-tags').text(val);
				}
			});
		});
	});
}(jQuery));