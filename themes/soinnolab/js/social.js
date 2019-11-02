jQuery( document ).ready( function( $ ) {
	// single 뷰페이지 social 공유 스크립트 -- 여기에 작성하시면 됩니다.
	var url = window.location.href;
	var title = $(document).find("title").text();
	Kakao.init('660b9cffa0e6e389c99d149f75b4e3b3');   //발급받은 Javascript 키를 입력합니다.
	Kakao.Link.createTalkLinkButton({
		container: '#kakao-link-btn',
		label: '사회혁신리서치랩',
		image: {
			src: 'http://soinnolab.net/wp-content/themes/soinnolab/images/logo01.png',
			width: '300',
			height: '200'
		},	
		webButton: {
			text: title,
			url: url // 앱 설정의 웹 플랫폼에 등록한 도메인의 URL이어야 합니다.
		}
	});
	
	$(".btn02.write.delete").click(function(){
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
								var href = $('.btn02.right').attr('href');
								location.replace(href);
							}
						});
                    }else{
                        $.alert(re);
                    }   
                }
			});return false;
        });
});

function sendSns(sns, url, txt)
{
    var o;
    var _url = encodeURIComponent(url);
    var _txt = encodeURIComponent(txt);
    var _br  = encodeURIComponent('\r\n');
 
    switch(sns)
    {
        case 'facebook':
            o = {
                method:'popup',
                url:'http://www.facebook.com/sharer/sharer.php?u=' + _url
            };
            break;
 
        case 'twitter':
            o = {
                method:'popup',
                url:'http://twitter.com/intent/tweet?text=' + _txt + '&url=' + _url
            };
            break;
 
        case 'me2day':
            o = {
                method:'popup',
                url:'http://me2day.net/posts/new?new_post[body]=' + _txt + _br + _url + '&new_post[tags]=epiloum'
            };
            break;
 
        case 'kakaotalk':
            o = {
                method:'web2app',
                param:'sendurl?msg=' + _txt + '&url=' + _url + '&type=link&apiver=2.0.1&appver=2.0&appid=dev.epiloum.net&appname=' + encodeURIComponent('Epiloum 개발노트'),
                a_store:'itms-apps://itunes.apple.com/app/id362057947?mt=8',
                g_store:'market://details?id=com.kakao.talk',
                a_proto:'kakaolink://',
                g_proto:'scheme=kakaolink;package=com.kakao.talk'
            };
            break;
 
        case 'kakaostory':
            o = {
                method:'web2app',
                param:'posting?post=' + _txt + _br + _url + '&apiver=1.0&appver=2.0&appid=dev.epiloum.net&appname=' + encodeURIComponent('Epiloum 개발노트'),
                a_store:'itms-apps://itunes.apple.com/app/id486244601?mt=8',
                g_store:'market://details?id=com.kakao.story',
                a_proto:'storylink://',
                g_proto:'scheme=kakaolink;package=com.kakao.story'
            };
            break;
 
        case 'band':
            o = {
                method:'web2app',
                param:'create/post?text=' + _txt + _br + _url,
                a_store:'itms-apps://itunes.apple.com/app/id542613198?mt=8',
                g_store:'market://details?id=com.nhn.android.band',
                a_proto:'bandapp://',
                g_proto:'scheme=bandapp;package=com.nhn.android.band'
            };
            break;
 
        default:
            alert('지원하지 않는 SNS입니다.');
            return false;
    }
 
    switch(o.method)
    {
        case 'popup':
            window.open(o.url);
            break;
 
        case 'web2app':
            if(navigator.userAgent.match(/android/i))
            {
                // Android
                setTimeout(function(){ location.href = 'intent://' + o.param + '#Intent;' + o.g_proto + ';end'}, 100);
            }
            else if(navigator.userAgent.match(/(iphone)|(ipod)|(ipad)/i))
            {
                // Apple
                setTimeout(function(){ location.href = o.a_store; }, 200);          
                setTimeout(function(){ location.href = o.a_proto + o.param }, 100);
            }
            else
            {
                alert('이 기능은 모바일에서만 사용할 수 있습니다.');
            }
            break;
    }
}