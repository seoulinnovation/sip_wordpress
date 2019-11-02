<?php
/**
 * KBoard Comments Controller
 * @link www.cosmosfarm.com
 * @copyright Copyright 2013 Cosmosfarm. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl.html
 */
class KBCommentController {
	
	// 스킨에서 사용 할 사용자 정의 옵션 input, textarea, select 이름의 prefix를 정의한다.
	var $skin_option_prefix = 'comment_option_';
	
	public function __construct(){
		$action = isset($_GET['action'])?$_GET['action']:'';
		switch($action){
			case 'kboard_comment_insert': add_action('wp_loaded', array($this, 'insert')); break;
			case 'kboard_comment_delete': add_action('wp_loaded', array($this, 'delete')); break;
			case 'kboard_comment_update': add_action('wp_loaded', array($this, 'update')); break;
		}

		add_action('wp_ajax_kboard_comment_like', array($this, 'commentLike'));
		add_action('wp_ajax_nopriv_kboard_comment_like', array($this, 'commentLike'));
		add_action('wp_ajax_kboard_comment_unlike', array($this, 'commentUnlike'));
		add_action('wp_ajax_nopriv_kboard_comment_unlike', array($this, 'commentUnlike'));
	}

	/**
	 * 댓글 입력
	 */
	public function insert(){
		if(isset($_POST['kboard-comments-execute-nonce']) && wp_verify_nonce($_POST['kboard-comments-execute-nonce'], 'kboard-comments-execute')){
			header("Content-Type: text/html; charset=UTF-8");
			
			$_POST = stripslashes_deep($_POST);
			
			$referer = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
			$host = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'';
			if($referer){
				$url = parse_url($referer);
				$referer_host = $url['host'] . (isset($url['port'])&&$url['port']?':'.$url['port']:'');
			}
			else{
				wp_die(__('This page is restricted from external access.', 'kboard-comments'));
			}
			if(!in_array($referer_host, array($host))) wp_die(__('This page is restricted from external access.', 'kboard-comments'));
			
			$content = isset($_POST['content'])?$_POST['content']:'';
			$comment_content = isset($_POST['comment_content'])?$_POST['comment_content']:'';
			$content = $content?$content:$comment_content;
			
			$content_uid = isset($_POST['content_uid'])?intval($_POST['content_uid']):'';
			$parent_uid = isset($_POST['parent_uid'])?intval($_POST['parent_uid']):'';
			$member_uid = isset($_POST['member_uid'])?intval($_POST['member_uid']):'';
			$member_display = isset($_POST['member_display'])?$_POST['member_display']:'';
			$password = isset($_POST['password'])?$_POST['password']:'';
			
			if(is_user_logged_in()){
				$current_user = wp_get_current_user();
				$member_uid = $current_user->ID;
				$member_display = $member_display?$member_display:$current_user->display_name;
			}
			
			$option = new stdClass();
			foreach($_POST as $key=>$value){
				if(strpos($key, $this->skin_option_prefix) !== false){
					$key = sanitize_key(str_replace($this->skin_option_prefix, '', $key));
					$value = kboard_safeiframe(kboard_xssfilter($value));
					$option->{$key} = $value;
				}
			}
			
			$document = new KBContent();
			$document->initWithUID($content_uid);
			$board = new KBoard($document->board_id);
			
			// 임시저장
			$temporary = new stdClass();
			$temporary->member_display = $member_display;
			$temporary->content = $content;
			$temporary->option = $option;
			setcookie('kboard_temporary_comments', base64_encode(serialize($temporary)), 0, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);
			
			if(!$board->id){
				die("<script>alert('".__('You do not have permission.', 'kboard-comments')."');history.go(-1);</script>");
			}
			else if(!$document->uid){
				die("<script>alert('".__('You do not have permission.', 'kboard-comments')."');history.go(-1);</script>");
			}
			else if(!is_user_logged_in() && $board->meta->permission_comment_write){
				die('<script>alert("'.__('You do not have permission.', 'kboard-comments').'");history.go(-1);</script>');
			}
			else if(!is_user_logged_in() && !$member_display){
				die("<script>alert('".__('Please enter the author.', 'kboard-comments')."');history.go(-1);</script>");
			}
			else if(!is_user_logged_in() && !$password){
				die("<script>alert('".__('Please enter the password.', 'kboard-comments')."');history.go(-1);</script>");
			}
			else if(!$content){
				die("<script>alert('".__('Please enter the content.', 'kboard-comments')."');history.go(-1);</script>");
			}
			else if(!$content_uid){
				die("<script>alert('".__('content_uid is required.', 'kboard-comments')."');history.go(-1);</script>");
			}
			
			// 금지단어 체크
			if(!$board->isAdmin()){
				
				// 작성자 금지단어 체크
				$name_filter = kboard_name_filter(true);
				if($name_filter){
					foreach($name_filter as $filter){
						if($filter && strpos($member_display, $filter) !== false){
							die("<script>alert('".sprintf(__('"%s" is not available.', 'kboard-comments'), $filter)."');history.go(-1);</script>");
						}
					}
				}
				
				// 본문/제목/댓글 금지단어 체크
				$content_filter = kboard_content_filter(true);
				if($content_filter){
					foreach($content_filter as $filter){
						if($filter && strpos($content, $filter) !== false){
							die("<script>alert('".sprintf(__('"%s" is not available.', 'kboard-comments'), $filter)."');history.go(-1);</script>");
						}
					}
				}
			}
			
			// Captcha 검증
			if($board->useCAPTCHA()){
				if(!class_exists('KBCaptcha')){
					include_once KBOARD_DIR_PATH.'/class/KBCaptcha.class.php';
				}
				$captcha = new KBCaptcha();
			
				if(!$captcha->validate()){
					die("<script>alert('".__('CAPTCHA is invalid.', 'kboard-comments')."');history.go(-1);</script>");
				}
			}
			
			$commentList = new KBCommentList($content_uid);
			$insert_id = $commentList->add($parent_uid, $member_uid, $member_display, $content, $password);
			
			$comment_option = new KBCommentOption($insert_id);
			foreach($option as $key=>$value){
				$comment_option->{$key} = $value;
			}
			
			if($insert_id){
				setcookie('kboard_temporary_comments', '', time()-(60*60), COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);
			}
			
			wp_redirect("{$referer}#kboard-comments-{$content_uid}");
			exit;
		}
		wp_die(__('You do not have permission.', 'kboard-comments'));
	}

	/**
	 * 댓글 삭제
	 */
	public function delete(){
		header("Content-Type: text/html; charset=UTF-8");
		
		$referer = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
		$host = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'';
		if($referer){
			$url = parse_url($referer);
			$referer_host = $url['host'] . (isset($url['port'])&&$url['port']?':'.$url['port']:'');
		}
		else{
			wp_die(__('This page is restricted from external access.', 'kboard-comments'));
		}
		if(!in_array($referer_host, array($host))) wp_die(__('This page is restricted from external access.', 'kboard-comments'));

		$uid = isset($_GET['uid'])?intval($_GET['uid']):'';
		$password = isset($_POST['password'])?sanitize_text_field($_POST['password']):'';

		if(!$uid){
			die("<script>alert('".__('uid is required.', 'kboard-comments')."');history.go(-1);</script>");
		}
		else if(!is_user_logged_in() && !$password){
			die("<script>alert('".__('Please log in to continue.', 'kboard-comments')."');history.go(-1);</script>");
		}

		$comment = new KBComment();
		$comment->initWithUID($uid);

		if(!$comment->isEditor() && $comment->password != $password){
			die("<script>alert('".__('You do not have permission.', 'kboard-comments')."');history.go(-1);</script>");
		}

		$comment->delete();

		if($comment->password && $comment->password == $password){
			// 팝업창으로 비밀번호 확인 후 opener 윈도우를 새로고침 한다.
			echo '<script>';
			echo 'opener.window.location.reload();';
			echo 'window.close();';
			echo '</script>';
		}
		else{
			// 삭제권한이 있는 사용자일 경우 팝업창은 없기 때문에 페이지 이동한다.
			wp_redirect($referer);
		}
		exit;
	}

	/**
	 * 댓글 수정
	 */
	public function update(){
		header("Content-Type: text/html; charset=UTF-8");
		
		$_POST = stripslashes_deep($_POST);
		
		$referer = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
		$host = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'';
		if($referer){
			$url = parse_url($referer);
			$referer_host = $url['host'] . (isset($url['port'])&&$url['port']?':'.$url['port']:'');
		}
		else{
			wp_die(__('This page is restricted from external access.', 'kboard-comments'));
		}
		if(!in_array($referer_host, array($host))) wp_die(__('This page is restricted from external access.', 'kboard-comments'));

		$content = isset($_POST['content'])?$_POST['content']:'';
		$comment_content = isset($_POST['comment_content'])?$_POST['comment_content']:'';
		$content = $content?$content:$comment_content;
		
		$uid = isset($_GET['uid'])?intval($_GET['uid']):'';
		$password = isset($_POST['password'])?sanitize_text_field($_POST['password']):'';

		if(!$uid){
			die("<script>alert('".__('uid is required.', 'kboard-comments')."');history.go(-1);</script>");
		}
		else if(!$content){
			die("<script>alert('".__('Please enter the content.', 'kboard-comments')."');history.go(-1);</script>");
		}
		else if(!is_user_logged_in() && !$password){
			die("<script>alert('".__('Please log in to continue.', 'kboard-comments')."');history.go(-1);</script>");
		}

		$comment = new KBComment();
		$comment->initWithUID($uid);

		if(!$comment->isEditor() && $comment->password != $password){
			die("<script>alert('".__('You do not have permission.', 'kboard-comments')."');history.go(-1);</script>");
		}
		
		$comment->content = $content;
		$comment->update();
		
		$option = new stdClass();
		foreach($_POST as $key=>$value){
			if(strpos($key, $this->skin_option_prefix) !== false){
				$key = sanitize_key(str_replace($this->skin_option_prefix, '', $key));
				$value = kboard_safeiframe(kboard_xssfilter($value));
				$comment->option->{$key} = $value;
			}
		}
		
		echo '<script>';
		echo 'opener.window.location.reload();';
		echo 'window.close();';
		echo '</script>';
		exit;
	}

	/**
	 * 댓글 좋아요
	 */
	public function commentLike(){
		if(isset($_POST['comment_uid']) && intval($_POST['comment_uid'])){
			if(!@in_array($_POST['comment_uid'], $_SESSION['comment_vote'])){
				$_SESSION['comment_vote'][] = $_POST['comment_uid'];

				$comment = new KBComment();
				$comment->initWithUID($_POST['comment_uid']);

				if($comment->uid){
					$comment->like+=1;
					$comment->vote = $comment->like - $comment->unlike;
					$comment->update();
					echo intval($comment->like);
					exit;
				}
			}
		}
		exit;
	}

	/**
	 * 댓글 싫어요
	 */
	public function commentUnlike(){
		if(isset($_POST['comment_uid']) && intval($_POST['comment_uid'])){
			if(!@in_array($_POST['comment_uid'], $_SESSION['comment_vote'])){
				$_SESSION['comment_vote'][] = $_POST['comment_uid'];

				$comment = new KBComment();
				$comment->initWithUID($_POST['comment_uid']);

				if($comment->uid){
					$comment->unlike+=1;
					$comment->vote = $comment->like - $comment->unlike;
					$comment->update();
					echo intval($comment->unlike);
					exit;
				}
			}
		}
		exit;
	}
}
?>