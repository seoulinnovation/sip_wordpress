<?php
/*
Plugin Name: Seoul Innovation Research Lab Facebook Login
Description: 서울혁신리서치랩 페이스북 로그인을 지원합니다.
Author: Slowalk
Version: 1.0
Author URI: http://www.slowalk.co.kr/
*/
C:\Users\taecheonin\Desktop\plugins\facebook-login\facebook-login.php
// Ajax 처리
add_action( 'wp_ajax_soinnolab_facebook_login', 'soinnolab_facebook_login');
add_action( 'wp_ajax_nopriv_soinnolab_facebook_login', 'soinnolab_facebook_login');
function soinnolab_facebook_login(){
	global $wpdb;
	$FBname = $_POST['FBname'];
	$FBemail = $_POST['FBemail'];
	$FBid = $_POST['FBid'];			
	$exists = email_exists( $FBemail );	
	if ( !$exists ){ //존재하지않는 메일이면 회원가입 + 로그인처리				
		if( !$FBname || $FBname == '' || preg_match("/[\xA1-\xFE]/", $FBname ) ){
			$name_check = explode( '@', $FBemail );
			$FBname = $name_check[0];
		}
		$check = $wpdb->get_var("SELECT ID FROM wp_users WHERE user_login like '$FBname'");
		if( $check ){
			$FBname = 'user_' . $FBname;
		}

		$new_pw = RandomString( 8 ); //임시비밀번호 생성 커스텀함수
		$hashedPassword = wp_hash_password($new_pw); //암호화
		//회원 등록
		$userdata = array(
		    'user_login'  =>  $FBname,
		    'user_email'    =>  $FBemail,
		    'user_pass'   =>  $new_pw,  // When creating an user, `user_pass` is expected.
		    'show_admin_bar_front' => 'false'
		);
		$user_id = wp_insert_user( $userdata ) ;

		//$wpdb->insert('wp_sns_temp_pw', array('user_id' => $user_id, 'user_email' => $FBemail, 'pw' => $hashedPassword ) );

		//로그인처리
		$user_info = get_user_by( 'email', $FBemail );
		$user_login = $user_info->date->user_login;	
		wp_set_current_user( $user_id, $user_login );
		wp_set_auth_cookie( $user_id );
	    do_action( 'wp_login', $user_login );
	   	$return = 'ok';
	}else{ //기존에 있는 메일이면 로그인처리
		$user_info = get_user_by( 'email', $FBemail );
		if($user_info){
			$user_id = $user_info->ID;
			$user_login = $user_info->date->user_login;			
			wp_set_current_user( $user_id, $user_login );
		    wp_set_auth_cookie( $user_id );
		    do_action( 'wp_login', $user_login );
		    $return = 'ok';	
		}		
	}
	echo json_encode($return);
	wp_die();
}

// Script Add
function soinnolab_facebook_login_script(){
	wp_enqueue_script('facebook-login', plugins_url('/facebook-login.js', __FILE__), array('jquery'), '1.0.0');
}
add_action('wp_enqueue_scripts', 'soinnolab_facebook_login_script');

// Generate Shortcode for Facebook Login Button
function soinnolab_facebook_login_button(){
	//echo '<input type="button" value="페이스북 로그인" class="soinnolab-facebook-login-btn" />';
	echo '<a href="#" class="soinnolab-facebook-login-btn"><i class="xi-facebook"></i><span>페이스북<br/>아이디로 로그인</span></a>';	
}
add_shortcode('soinnolab-facebook-login-btn', 'soinnolab_facebook_login_button');

function register_soinnolab_fblogin_menu_page() {
	add_submenu_page('options-general.php','페이스북 로그인','페이스북 로그인','manage_options','soinnolab-facebook-login-menu-page','soinnolab_facebook_login_menu_page');
}

function soinnolab_facebook_login_menu_page() {
	$update = false;
	if(isset($_POST['action']) && $_POST['action'] == 'update' && isset($_POST['appid']) && !empty($_POST['appid']))
	{
		$update = update_option('_soinnolab_facebook_login', $_POST['appid']);
	}
	
	$appid = get_option('_soinnolab_facebook_login');
?>
	<div class="wrap">		
		<h1>페이스북 AppId 설정</h1>
		<?php if($update){ ?>
		<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
			<p><strong>설정을 저장했습니다.</strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text">이 알림 무시하기.</span>
			</button>
		</div>
		<?php } ?>
		<?php if(!$appid) { ?>
		<em>앱아이디를 생성하지 않으셨다면 <a href="https://developers.facebook.com/apps/" target="_blank">페이스북 개발자사이트</a>에서 생성하세요.</em>
		<?php } else { ?>
		<p><em>앱아이디가 생성되었습니다. 이제 Shortcode를 이용해서 로그인 버튼을 사용하실 수 있습니다.</em></p>
		<p><code>[soinnolab-facebook-login-btn]</code>를 에디터에 입력하시거나</p>
		<p>php 파일에서 <code>do_shortcode('[soinnolab-facebook-login-btn]');</code>를 입력하세요.</em></p>
		<?php } ?>
		<form method="post" action="<?php echo '//'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];?>" novalidate="novalidate">
			<input type="hidden" name="option_page" value="soinnolab_facebook_login">
			<input type="hidden" name="action" value="update">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="페이스북 아이디">페이스북 앱아이디</label></th>
						<td><input type="text" name="appid" value="<?php if($appid) echo $appid;?>" class="regular-text"></td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button button-primary" value="변경 사항 저장">
			</p>			
		</form>
	</div>
<?php }
add_action('admin_menu', 'register_soinnolab_fblogin_menu_page');

function soinnolab_facebook_appid(){ 
	$appid = get_option('_soinnolab_facebook_login');
	if($appid){		
?>
	<script>
		jQuery( document ).ready( function( $ ) {
			window.fbAsyncInit = function() {
				FB.init({
					appId      : '<?php echo $appid;?>',
					xfbml      : true,
					version    : 'v2.6'
				});
			};			
		});				   
	</script>
<?php }
}
add_action('wp_head', 'soinnolab_facebook_appid');

//임시비밀번호 만들기
function RandomString($length) {
   $keys = array_merge(range(0,9), range('a', 'z'));

   for($i=0; $i < $length; $i++) {
      $key .= $keys[array_rand($keys)];
   }
   return $key;
}