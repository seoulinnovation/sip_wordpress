jQuery( document ).ready( function( $ ) {	
	$(".bookmark").confirm({
		confirmButton:'확인',
		title: '스크랩',
		cancelButton: false,
		content: function(){
			var self = this;
			var data = {
				post_id : $(".bookmark").attr('data-post_id'),
				nonce : $(".bookmark").attr('data-nonce'),
				action : 'bookmark'
			};
			var ajaxurl = $(".bookmark").attr('href');
						
			return $.ajax({
	            url: ajaxurl,
	            dataType: 'json',
	            method: 'post',
	            data: data
	        }).done(function (response) {
	            self.setContent('<p>' + response.content + '</p>');
	            self.setContent(self.content + '<p>' + response.button + '</p>'); // appending
	            self.setTitle(response.title);
	        }).fail(function(){
	            self.setContent('Something went wrong.');
	        });
		},		
		confirm: function(){
        	this.close();
			return false; // prevent modal from closing
    	}
	});
	
	$(".btn03.scrap.delete").click(function(){		
		var data = {
			scrap_id : $(this).attr('data-id'),				
			action : 'bookmark_delete'
		};
		$.ajax({
            url: '/wp-admin/admin-ajax.php',
            dataType: 'json',
            method: 'post',
            data: data,
            success: function(response){
				$.alert({
					title: response.title,
					content: response.content,
					confirm: function(){
						location.reload();
					}
				});				
            }
        });
        return false;		
	});
});