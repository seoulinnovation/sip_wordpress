<?php
/*
Plugin Name: Seoul Innovation Research Lab Google Login
Description: 서울혁신리서치랩 구글 로그인을 지원합니다.
Author: Slowalk
Version: 1.0
Author URI: http://www.slowalk.co.kr/
*/
session_start();
add_action( 'wp_ajax_soinnolab_google_login', 'soinnolab_google_login');
add_action( 'wp_ajax_nopriv_soinnolab_google_login', 'soinnolab_google_login');
function soinnolab_google_login(){
	global $wpdb;
	$id = $_SESSION['id'];
	$name = $_SESSION['name'];
	$email = $_SESSION['email'];
	if(empty($email)) {
		$return = $_SESSION;
		echo json_encode($return);
		wp_die();
	}
	$exists = email_exists( $email );	
	if ( !$exists ){ //존재하지않는 메일이면 회원가입 + 로그인처리				
		
		$name_check = explode( '@', $email );
		$user_login = $name_check[0];

		$check = $wpdb->get_var("SELECT ID FROM wp_users WHERE user_login like '$user_login'");
		if( $check ){
			$user_login = 'user_' . $user_login;
		}

		$new_pw = $id;
		$hashedPassword = wp_hash_password($new_pw); //암호화
		//회원 등록
		$userdata = array(
		    'user_login'  =>  $user_login,
		    'user_email'    =>  $email,
		    'user_pass'   =>  $new_pw,  // When creating an user, `user_pass` is expected.
		    'show_admin_bar_front' => 'false',		    
		);
		$user_id = wp_insert_user( $userdata ) ;		

		//로그인처리
		$user_info = get_user_by( 'email', $email );
		$user_login = $user_info->date->user_login;	
		wp_set_current_user( $user_id, $user_login );
		wp_set_auth_cookie( $user_id );
	    do_action( 'wp_login', $user_login );
	    $return = 'ok';
	}else{ //기존에 있는 메일이면 로그인처리
		$user_info = get_user_by( 'email', $email );
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
function soinnolab_google_login_script(){
	wp_enqueue_script('google-login', plugins_url('/google-login.js', __FILE__), array('jquery'), '1.0.0');
}
add_action('wp_enqueue_scripts', 'soinnolab_google_login_script');

// Generate Shortcode for google Login Button
function soinnolab_google_login_button(){	
	require_once realpath(dirname(__FILE__) . '/google-api-php-client/src/Google/autoload.php');
	require_once realpath(dirname(__FILE__) . '/google-api-php-client/src/Google/Client.php');
	require_once realpath(dirname(__FILE__) . '/google-api-php-client/src/Google/Service/Oauth2.php');
	
	$client_id = get_option('_soinnolab_google_client_id');
	$client_secret = get_option('_soinnolab_google_client_secret');
	$redirect_uri = get_option('_soinnolab_google_redirect_url');	
	
	$client = new Google_Client();
	$client->setClientId($client_id);
	$client->setClientSecret($client_secret);
	$client->setRedirectUri($redirect_uri);
	$client->addScope("https://www.googleapis.com/auth/userinfo.email");	
	
	if($_GET['code']){
		if (isset($_SESSION['token'])) {
			$client->setAccessToken($_SESSION['token']);
		}
		//Send Client Request
		$Oauth2 = new Google_Service_Oauth2($client);
		
		$client->authenticate($_GET['code']);
		$_SESSION['token'] = $client->getAccessToken();
	
		$user = $Oauth2->userinfo->get();
		$_SESSION['id']            = $user['id'];
		$_SESSION['name']          = $user['name'];
		$_SESSION['email']         = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
		
		$id = 'complete';
	} else {
		unset($_SESSION['token']);
		$client->revokeToken();	
		$id = '';
	}	

	$authUrl = $client->createAuthUrl();
	echo '<a href="'.$authUrl.'" class="soinnolab-google-login-btn" id="'.$id.'"><i class="xi-google"></i><span>구글<br/>아이디로 로그인</span></a>';	
}
add_shortcode('soinnolab-google-login-btn', 'soinnolab_google_login_button');

function register_soinnolab_glogin_menu_page() {
	add_submenu_page('options-general.php','구글 로그인','구글 로그인','manage_options','soinnolab-google-login-menu-page','soinnolab_google_login_menu_page');
}

function soinnolab_google_login_menu_page() {
	$update = false;
	if(isset($_POST['action']) && $_POST['action'] == 'update')
	{
		$update = update_option('_soinnolab_google_client_id', $_POST['client_id']);
		$update = update_option('_soinnolab_google_client_secret', $_POST['client_secret']);
		$update = update_option('_soinnolab_google_redirect_url', $_POST['redirect_url']);
	}
	
	$client_id = get_option('_soinnolab_google_client_id');
	$client_secret = get_option('_soinnolab_google_client_secret');
	$redirect_url = get_option('_soinnolab_google_redirect_url');
?>
	<div class="wrap">		
		<h1>구글 로그인 설정</h1>
		<?php if(isset($_POST['action'])){ ?>
		<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
			<p><strong>설정을 저장했습니다.</strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text">이 알림 무시하기.</span>
			</button>
		</div>
		<?php } ?>
		<?php if(!$client_id) { ?>
		<em>클라이언트 아이디를 생성하지 않으셨다면 <a href="https://console.developers.google.com/apis/credentials/" target="_blank">구글 개발자사이트</a>에서 생성하세요.</em>
		<?php } else { ?>
		<p><em>앱아이디가 생성되었습니다. 이제 Shortcode를 이용해서 로그인 버튼을 사용하실 수 있습니다.</em></p>
		<p><code>[soinnolab-google-login-btn]</code>를 에디터에 입력하시거나</p>
		<p>php 파일에서 <code>do_shortcode('[soinnolab-google-login-btn]');</code>를 입력하세요.</em></p>
		<?php } ?>
		<form method="post" action="<?php echo '//'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];?>" novalidate="novalidate">
			<input type="hidden" name="option_page" value="soinnolab_google_login">
			<input type="hidden" name="action" value="update">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="GOOGLE CLIENT ID">CLIENT ID</label></th>
						<td><input type="text" name="client_id" value="<?php if($client_id) echo $client_id;?>" class="regular-text"></td>
					</tr>
					<tr>
						<th scope="row"><label for="GOOGLE CLIENT SECRET">CLIENT SECRET</label></th>
						<td><input type="text" name="client_secret" value="<?php if($client_secret) echo $client_secret;?>" class="regular-text"></td>
					</tr>
					<tr>
						<th scope="row"><label for="GOOGLE REDIRECT URL">REDIRECT URL</label></th>
						<td><input type="text" name="redirect_url" value="<?php if($redirect_url) echo $redirect_url;?>" class="regular-text"></td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button button-primary" value="변경 사항 저장">
			</p>			
		</form>
	</div>
<?php }
add_action('admin_menu', 'register_soinnolab_glogin_menu_page');