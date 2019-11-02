jQuery( document ).ready( function( $ ) {
	$(".newsletter .submit").click(function(){
		var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
		var email = $(".newsletter .email").val();
		if(testEmail.test(email)){
			$.confirm({
				title: '뉴스레터',
				cancelButton: false,
				content: function(){
					var self = this;
					var data = {
						email: email,
						action : 'newsletter'
					};
					var ajaxurl = "/wp-admin/admin-ajax.php";
								
					return $.ajax({
			            url: ajaxurl,
			            dataType: 'json',
			            method: 'post',
			            data: data
			        }).done(function (response) {
			            self.setContent('<p>이메일 주소: ' + response.email + '</p>');
			            self.setContent(self.content + '<p>' + response.content +'</p>'); // appending
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
		} else {
			$.alert({
				title: '잘못된 이메일 형식입니다!',
				content: '이메일 형식맞춰서 올바로 입력해주세요.',
				confirm: function(){
					$(".newsletter .email").focus();
				}
			});			
		}
		return false;
	});
});