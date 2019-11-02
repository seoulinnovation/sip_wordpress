jQuery( document ).ready( function( $ ) {
	var googlebtnid = $(".soinnolab-google-login-btn").attr('id');
	if(googlebtnid == 'complete'){
		$.post({
			url:'/wp-admin/admin-ajax.php',
			data:{action:'soinnolab_google_login'},
			beforeSend:function(){
	            $('.body-loading').removeClass('display-none');
	        },
	        complete:function(){
	            $('.body-loading').addClass('display-none');	     
	        },
	        success:function(response){
				var res = $.parseJSON(response);
		        if(res == 'ok'){
			        location.replace('/');
		        } else {
			        $.alert('로그인이 실패하였습니다. 재로그인해주세요.');
			        console.log(res);
		        }
	        },
	        error:function(err){
		        console.log(err);
	        }
		})
	}
});