jQuery( document ).ready( function( $ ) {
    'use strict';
    $(function () {
        //로그인 버튼
        $('#wp-submit').click( function(){
            var form = document.loginform;
            var data = {	            
                section: 'login',
                action: 'soinnolab_login',
                user_login: form.user_email.value,
                user_pass: form.user_pw.value               
            };
            var refer = $("input[name=refer]").val();
            $.ajax({
                url: "/wp-admin/admin-ajax.php",
                type: 'POST',
                data: data,                
                beforeSend: function(){ 
                    if( form.user_email.value == '' ) {
                        alert("이메일을 입력해주세요");
                        return false;
                    } 

                    if( form.user_pw.value == '' ) {
                        alert("비밀번호를 입력해주세요");
                        return false;
                    }
                },
                success: function(re){
	                var re = $.parseJSON(re);
	                console.log(re);
                    if (re == "ok") {                        
						location.replace(refer);
                    }else{	                   
                        $.alert(re);
                    }   
                }
            });return false;            
        });

        //회원가입
        $('.cf06.registerForm form').submit( function(){            
            var data = {
	            action: 'soinnolab_login',
                section: 'join',                
                join_user_login: this.user_name.value,
                join_user_mail: this.user_email.value,
                join_user_pass: this.user_pw.value
            };
            $.ajax({
                url: "/wp-admin/admin-ajax.php",
                type: 'POST',
                data: data,
                beforeSend: function(){ 
                    $('.body-loading').removeClass('display-none');
		        },
		        complete:function(){
		            $('.body-loading').addClass('display-none');	     
		        },
                success: function(re){
	                var re = $.parseJSON(re);
                    if (re == "ok") {
                        alert("가입되셨습니다");                               
                        location.reload(true);
                    }else{
                        alert(re);
                    }   
                }
            });return false;
        });

        //비밀번호
        $('.cf06.passwordForm form').submit( function(){
	        var user_email = this.user_email.value;
	        if(user_email){
	            var data = {
		            action: 'soinnolab_login',
	                section: 'findpw',
	                pw_user_mail: user_email
	            };
	            $.ajax({
	                url: "/wp-admin/admin-ajax.php",
	                type: 'POST',
	                data: data,
	                beforeSend:function(){
			            $('.body-loading').removeClass('display-none');
			        },
			        complete:function(){
			            $('.body-loading').addClass('display-none');	     
			        },
					success: function(re){		                
		                console.log(re);
		                var re = $.parseJSON(re);
	                    if (re == "ok") {
							$('.alert').show();
	                    }else{
	                        $.alert(re);
	                    }   
	                }                 
	            });return false;
	        }	
        });
        //댓글 삭제
        $('.btn03.comment.delete').click(function(){
	        var cid = $(this).attr('data-id');	       
	        var data = {
	            action: 'soinnolab_login',
                section: 'deletecomments',
                comment_id: cid
            };
	        $.ajax({
                url: "/wp-admin/admin-ajax.php",
                type: 'POST',
                data: data,
                beforeSend:function(){
		            $('.body-loading').removeClass('display-none');
		        },
		        complete:function(){
		            $('.body-loading').addClass('display-none');	     
		        },
				success: function(re){
	                var re = $.parseJSON(re);
	                console.log(re);
                    if (re == "ok") {
                        $.alert({
	                        title: "내가 쓴 댓글",
							content: "삭제가 완료되었습니다.",
							confirm: function(){
								location.reload();
							}
						});
                    }else{
                        $.alert(re);
                    }   
                }                 
            });
	        return false;
        });
        //내정보수정
        $('.cf05.userInfoForm').ajaxForm({
			success: function(data){
				var result = $.parseJSON(data);			
				$.confirm({
					confirmButtonClass: 'btn01',
					confirmButton:'확인',
					cancelButton: false,
					title:'수정이 완료되었습니다.',
					content:false,
					confirm:function(){
						//console.log(result);						
						location.reload();
					}
				});
			}
        });
        //내정보수정 - 이미지 추가
        $('.cf05.userInfoForm .btn03.user_pic.add').click(function(){
	        if($('#user_pic').val() == ''){
		        $.alert({title:"이미지를 선택하세요.", content:false});
		        return false;
	        }
			$('[name=section]').val('edituserpic');
			$('.cf05.userInfoForm').submit();
			return false;
        });
        //내정보수정 - 이미지 삭제
        $(".cf05.userInfoForm .btn03.user_pic.delete").click(function(){
			$('[name=section]').val('deleteuserpic');
			$('.cf05.userInfoForm').submit();
			return false;
        });
        //내가 쓴 글 확인 - 삭제
        $(".btn03.write.delete").click(function(){
	        var pid = $(this).attr('data-id');
			var data = {
	            action: 'soinnolab_login',
                section: 'deletepost',
                post_id: pid
            };
	        $.ajax({
                url: "/wp-admin/admin-ajax.php",
                type: 'POST',
                data: data,
                beforeSend:function(){
		            $('.body-loading').removeClass('display-none');
		        },
		        complete:function(){
		            $('.body-loading').addClass('display-none');	     
		        },
				success: function(re){
	                var re = $.parseJSON(re);
	                console.log(re);
                    if (re == "ok") {
                        $.alert({
	                        title: "내가 쓴 글",
							content: "삭제가 완료되었습니다.",
							confirm: function(){
								location.reload();
							}
						});
                    }else{
                        $.alert(re);
                    }   
                }
			});return false;
        });
    });        
});