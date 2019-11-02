/*
Theme Name: sirl
Theme URI: http://www.sirl.com
Author: css3studio
Version:1.0
*/
var device_status = "";
$(window).resize(function() {
	resize_script();
});

function resize_script(){
	var dw = $(window).width();
	if(dw < 1024 && device_status == "pc"){	//PC에서 모바일로 변경시
		$("body").removeClass('pc');
		$("body").addClass('mobile');
		init_mobile();
		device_status = "mobile";
	}else if(dw >= 1024 && device_status == "mobile"){	//모바일에서 PC로 변경시
		$("body").removeClass('mobile');
		$("body").addClass('pc');
		init_pc();
		device_status = "pc";
	}
}

$(document).ready(function() {
	if($('body').hasClass('home')){
		$(window).scroll(function(){
			var isVisible = isScrolledIntoView('.fc01 .mc02 .leftA');
			if(isVisible){
				if(!($('.fc01 .mc02 .leftA').hasClass('done'))){
					$('.timer').countTo();
					$('.fc01 .mc02 .leftA').addClass('done');
				}
			}
		});
	}

	function isScrolledIntoView(elem)
	{
	    var docViewTop = $(window).scrollTop();
	    var docViewBottom = docViewTop + $(window).height();

	    var elemTop = $(elem).offset().top;
	    var elemBottom = elemTop + $(elem).height();

	    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
	}

	//모바일 pc 분기
	var dw = $(window).width();
	if(dw < 1024){	//모바일
		$("body").removeClass('pc');
		$("body").addClass('mobile');
		init_mobile();
		device_status = "mobile";
	}else{	//PC
		$("body").removeClass('mobile');
		$("body").addClass('pc');
		init_pc();
		device_status = "pc";
	}
	
	//메인슬라이더
	var ib01_length = $('.ib01 .slide li').length;
	var	ib01 = $('.ib01 .slide').bxSlider({
		auto: (ib01_length > 1) ? true : false,
		autoHover:true,
		speed:1000,
		pager:true,
//		pause:4000,
		autoControls:true,
		preloadImages:'all',
		controls:false,
		onSliderLoad: function(){
			$('.ib01 .slide li').each(function(){
				$(this).css({
					'background-image':'url('+$('img',$(this)).attr('src')+')'
				});
				$('img',$(this)).remove();
			});
			if(ib01_length == 1){
				$('.ib01 .bx-controls').hide();
			}			
		}
	});
	
	//상단 검색창
	$("a.openSearch").bind("click",function(event){
		event.stopPropagation();
		if($("header nav").hasClass('searchMode')){			
			//$(".cf01").submit();
			$('.cf01 input').focus();
		}else{
			$('header nav').addClass('searchMode');
			$('.cf01 input').focus();
		}
	});
	$(".cf01 input").bind("click",function(event){
		event.stopPropagation();
	});
	$(document).bind("click",function(){
		$("header nav").removeClass('searchMode');
	});
	$(".cf01").bind('submit', function() {		
		var input = $("input",$(this));
		if(input.val().trim() == ""){			
			$.alert("검색어를 입력해 주세요");
			input.focus();
			return false;
		}
		else 
			return true;
	});	
	//메인 배너 검색창
	$(".cf02").bind('submit', function() {
		var input = $("input",$(this));
		if(input.val().trim() == ""){
			$.alert("검색어를 입력해 주세요");
			input.focus();
			return false;
		}
		else
			return true;
	});	
	//푸터 뉴스레터 신청
	var re_mail = /^([\w\.-]+)@([a-z\d\.-]+)\.([a-z\.]{2,6})$/; // 이메일 검사식
	
	$(".cf03").bind('submit', function() {
		var input = $("input",$(this));
		if(!re_mail.test(input.val())){
			$.alert("올바른 이메일 주소를 입력해 주세요");
			input.focus();
			return false;
		}
		else
			return true;
	});	
	//메인 배너 검색창
	$(".cf04").bind('submit', function() {
		var input = $("input",$(this));
		if(input.val().trim() == ""){
			$.alert("검색어를 입력해 주세요");
			input.focus();
			return false;
		}
		else
			return true;
	});	
	
	//혁신랩 리스트 옵션 선택
	$(".ng03 dt a").bind("click",function(){
		var ele = $(this).parents(".ng03");
		if(ele.hasClass('on'))
			ele.removeClass('on')
		else
			ele.addClass('on')
		return false;
	});
	$(".ng03 dd ul li a").bind("click",function(){
		var ele = $(this).parents(".ng03");
		var sync_id = ele.attr('data-sync-id');
		ele.attr('data-current-value',$(this).attr('data-value'));
		$('dt a',ele).text($(this).text());
		$('#'+sync_id).val($(this).attr('data-value'));
		ele.removeClass('on')
		return false;
	});
	$(document).bind("click",function(){
		$('.ng03').removeClass('on')
	});
	//이미지 미리보기
	$("#user_pic").change(function(){
	    readURL(this);
	});
	function readURL(input) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();

	        reader.onload = function (e) {
	            $('#preview_pic').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
	
	//마이페이지
    $(".cf05.userInfoForm").validate({
        rules: {
            user_name: "required"
        },
        messages: {
            user_name: "이름을 입력하세요"
        }
    });
	//연구 글쓰기
    $(".cf05.writeStudyForm").validate({
        rules: {
            write_title: "required",
            write_content: "required"
        },
        messages: {
            write_title: "제목을 입력하세요",
            write_content: "연구와 관련된 글을 작성해 주세요"
        }
    });
	//행사 글쓰기
    $(".cf05.writeEventForm").validate({
        rules: {
            write_title: "required",
            write_content: "required"
        },
        messages: {
            write_title: "제목을 입력하세요",
            write_content: "연구와 관련된 글을 작성해 주세요"
        }
    });
	//댓글
    $(".regReplyForm").validate({
        rules: {
            reply_content: "required",
            reply_agree: "required",
            reply_email: {
                required: true,
                email: true
            },
            reply_pw: "required"
        },
        messages: {
            reply_content: "댓글내용을 입력하세요",
            reply_agree: "동의가 필요합니다.",
            reply_email: "올바른 이메일 주소를 입력하세요",
            reply_pw: "비밀번호를 입력하세요"
        }
    });
	//회원가입
    $(".cf06.registerForm form").validate({
        rules: {
            user_name: "required",
            user_email: {
                required: true,
                email: true
            },
            user_pw: "required"
        },
        messages: {
            user_name: "댓글내용을 입력하세요",
            user_email: "올바른 이메일 주소를 입력하세요",
            user_pw: "비밀번호를 입력하세요"
        }
    });
	//회원가입
    $(".cf06.loginForm form").validate({
        rules: {
            user_email: {
                required: true,
                email: true
            },
            user_pw: "required"
        },
        messages: {
            user_email: "올바른 이메일 주소를 입력하세요",
            user_pw: "비밀번호를 입력하세요"
        }
    });
	//비밀번호 찾기
    $(".cf06.passwordForm form").validate({
        rules: {
            user_email: {
                required: true,
                email: true
            }
        },
        messages: {
            user_email: "올바른 이메일 주소를 입력하세요"
        }
    });    
    //내 정보 수정 - 비밀번호 변경
	$('#pw-btn').click(function(){		
		if($('.userInfoForm').hasClass('changing-pw')){
			$('.userInfoForm').removeClass('changing-pw');
			$('#user_password').val('');
			$('#user_password_again').val('');
			$(this).html('비밀번호 수정하기');
		}else{
			$('.userInfoForm').addClass('changing-pw');
			$(this).html('비밀번호 수정취소');
		}
		
		return false;
	});
	//내 정보 수정 - 비밀번호 밸리데이션 추가
	$('.userInfoForm').validate({
        errorPlacement: function(error, element) {
            error.appendTo( element.parent() );
            // console.log(error);
        },
        focusInvalid: false,
        invalidHandler: function(form, validator) {
        if (!validator.numberOfInvalids())
            return;
            $('html, body').animate({
                scrollTop: $(validator.errorList[0].element).offset().top-30
            }, 'fast');
        },            
       	rules: {
            user_password:{minlength:6, required:true},
            user_password_again:{minlength:6, required:true, equalTo:"#user_password"},
        },
        messages: {
            user_password:{minlength:"6자 이상 입력해주세요.",required: "비밀번호를 입력해주세요"},
            user_password_again: {minlength:"6자 이상 입력해주세요.", required: "비밀번호를 입력해주세요", equalTo:"비밀번호가 일치하지 않습니다."},
        },    
    }); 
    //언어변경
    $("#lang-eng i").click(function(){
		location.replace('//soinnolab.net/about/eng');
    });
     $("#lang-ko i").click(function(){
		location.replace('//soinnolab.net/');
    });
    //프린트
    $("i.xi-print").click(function(){
	   window.print(); 
    });
});
$(window).load(function(){
	$('.lc01').isotope({
	  itemSelector: 'li',
	  // percentPosition: true,
	  masonry: {
	    // use outer width of grid-sizer for columnWidth
	    // columnWidth: '.grid-sizer'
	  }
	})
})

//PC버젼 초기화
function init_pc(){
	$("header nav").show();
	$("body header .btn01").unbind();
	//헤더 GNB 메뉴(PC)
	$("nav .ng01 li.mypage").bind("mouseenter",function(){
		$('ul',$(this)).fadeIn( "fast", function(){});
	});
	$("nav .ng01 li.mypage").bind("mouseleave",function(){
		$('ul',$(this)).fadeOut( "fast", function(){});
	});


	//공유 버튼

	// $(".th02 ul>li").bind("mouseenter",function(){
	// 	$('ul',$(this)).fadeIn( "fast", function(){});
	// });
	// $(".th02 ul>li").bind("mouseleave",function(){
	// 	$('ul',$(this)).fadeOut( "fast", function(){});
	// });	

	$('.th02 ul>li:first-child').hoverIntent(function() {
		$(this).find('ul').fadeIn('fast');
        // $('#masthead .inner').animate({
        //     height: '380px'
        // }, 200);
    }, function() {   
    	$(this).find('ul').fadeOut('fast');  
        // $('#masthead .inner').animate({
        //     height: '145px'
        // },200);
    });

    
	
}
//모바일 버젼 초기화
function init_mobile(){

	//모바일 메뉴 생성
	$("header nav").hide();
	//헤더 LNB 메뉴(mobile)
	$("body.mobile header .btn01").bind("click",function(){
		$(".ng07").show();
		setTimeout(function(){
			$(".ng07").addClass("on");
		},100);
	});
	$(".ng07,.ng07 .btnClose").bind("click",function(){
		$(".ng07").removeClass("on");
		setTimeout(function(){
			$(".ng07").hide();
		},500);
	});
	$(".ng07 .mArea").bind("click",function(event){
		event.stopPropagation();
	});
	$('.th02 ul>li:first-child').on('click',function(){
		if($(this).hasClass('on')){
	    	$(this).find('ul').fadeOut('fast'); 
	    	$(this).removeClass('on'); 
		}else{
	    	$(this).find('ul').fadeIn('fast');
	    	$(this).addClass('on'); 
		}
    });
/*
	//인기연구 모아보기
    $('.ib02 .slide').bxSlider({
	    slideWidth: 160,
	    minSlides: 2,
	    maxSlides: 3,
		moveSlides: 1,
		infiniteLoop:false,
		slideMargin: 15,
		pager:false,
		controls:false
    });
*/	
}
