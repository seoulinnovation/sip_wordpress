jQuery( document ).ready( function( $ ) {	
	var editor = CKEDITOR.replace( "content" , {filebrowserImageUploadUrl : '/wp-content/plugins/ckeditor-for-wordpress/filemanager/connectors/php/upload.php?command=QuickUpload&type=Images'} );
    editor.on( 'change', function( evt ) {
	    // getData() returns CKEditor's HTML content.
	    //console.log( 'Total bytes: ' + evt.editor.getData().length );
	    $("textarea[name=write_content]").text(evt.editor.getData());
	});
	
	$(".cf05.writeStudyForm").ajaxForm({
		beforeSend:function(){
            $('.body-loading').removeClass('display-none');
        },
        complete:function(){
            $('.body-loading').addClass('display-none');	     
        },
		success: function(data){
			var result = $.parseJSON(data);			
			$.confirm({
				confirmButtonClass: 'btn01',
				confirmButton:'확인',
				cancelButton: false,
				title:'글이 정상적으로 등록되었습니다.',
				content:false,
				confirm:function(){
					console.log(result);
					location.replace('/?p='+result);
				}
			});
		}
	});
	
	$(".cf05.writeEventForm").ajaxForm({
		beforeSend:function(){
            $('.body-loading').removeClass('display-none');
        },
        complete:function(){
            $('.body-loading').addClass('display-none');	     
        },
		success: function(data){
			var result = $.parseJSON(data);
			$.confirm({
				confirmButtonClass: 'btn01',
				confirmButton:'확인',
				cancelButton: false,
				title:'글이 정상적으로 등록되었습니다.',
				content:false,
				confirm:function(){					
					location.replace('/?p='+result);
				}
			});
		}
	})
	
	$(".cf05.writeLabForm").ajaxForm({
		beforeSend:function(){
            $('.body-loading').removeClass('display-none');
        },
        complete:function(){
            $('.body-loading').addClass('display-none');	     
        },
		success: function(data){
			var result = $.parseJSON(data);			
			$.confirm({
				confirmButtonClass: 'btn01',
				confirmButton:'확인',
				cancelButton: false,
				title:'글이 정상적으로 등록되었습니다.',
				content:false,
				confirm:function(){
					console.log(result);
					//location.replace('/?p='+result);
				}
			});
		}
	});
	
	$(".btn03.thumbnail.delete").click(function(){
		var post_thumbnail_id = $("[name=post_thumbnail_id]").val();
		$.ajax({
			url: '/wp-admin/admin-ajax.php',
			type: 'post',
			data: {action: 'soinnolab_write', section: 'thumbnail_delete', attachid: post_thumbnail_id},
			beforeSend:function(){
            $('.body-loading').removeClass('display-none');
	        },
	        complete:function(){
	            $('.body-loading').addClass('display-none');	     
	        },
			success: function(response){
				$.confirm({
					confirmButtonClass: 'btn01',
					confirmButton:'확인',
					cancelButton: false,
					title:'정상적으로 삭제되었습니다.',
					content:false,
					confirm: function(){
						location.reload();
					}
				});				
			}
		})
	});
	
	
});