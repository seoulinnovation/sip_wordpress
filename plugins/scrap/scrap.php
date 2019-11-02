<?php
/*
Plugin Name: Scrap Posts and Pages
Description: This plugin help you can scrap each posts and pages.
Author: Slowalk
Version: 1.0
Author URI: http://www.slowalk.co.kr/
*/


global $scrap_db_version;
$scrap_db_version = '1.0';

class Scrap {
	
	static function install(){
		global $wpdb;
		global $scrap_db_version;
	
		$table_name = $wpdb->prefix . 'scrap';
		
		$charset_collate = $wpdb->get_charset_collate();	
		
		$sql = "DROP TABLE IF EXISTS $table_name;
				CREATE TABLE $table_name (
				  scrap_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				  post_id bigint(20) unsigned NOT NULL,
				  post_author bigint(20) unsigned NOT NULL DEFAULT '0',
				  scrap_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				  scrap_date_gmt datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				  guid varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
				  post_type varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'post',
				  scrap_status varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'publish',
				  PRIMARY KEY  (scrap_id)
				) $charset_collate";
	
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		add_option( 'scrap_db_version', $scrap_db_version );
	}
	
	static function bookmark() {
		global $post;
		$nonce = wp_create_nonce("bookmark_nonce");
		$link = admin_url('admin-ajax.php');		
		echo '<a class="bookmark" data-nonce="' . $nonce . '" data-post_id="' . $post->ID . '" href="' . $link . '" title="스크랩"><i class="xi-attachment"></i></a>';
	}
	
	static function ajax() {		 
		global $wpdb; // 데이터베이스에 접근할 수 있는 방법을 제공

		$post_id = $_POST['post_id'];
		$post = '';
		$nonce = $_POST['nonce'];
		$user_id = get_current_user_id();
		$table_name = $wpdb->prefix . 'scrap';
		$data['button'] = '';
		
		if ( !wp_verify_nonce( $nonce, "bookmark_nonce")) {
			exit("No naughty business please");
		}
		
		// 이미 스크랩한 포스트인지 확인한다.
		$result = $wpdb->get_row("select count(*) as cnt from $table_name where post_id = $post_id and post_author = $user_id");
		if($result->cnt > 0) {
			$data['title'] = '이미 스크랩하셨습니다.';
			$data['content'] = '이미 스크랩하신 포스트입니다. <br /> 스크랩하신 내용은 마이페이지에서 확인하실 수 있습니다.';
			$data['button'] = '<a class="btn btn03" href="/mypage/scrap">마이페이지</a>';
		} else {
			$scrap_date = current_time( 'mysql' );
			$scrap_date_gmt = get_date_from_gmt( $scrap_date );
			
			$postarr = array(
				'post_id' => $post_id,
		        'post_author' => $user_id,
		        'scrap_date' => $scrap_date,
		        'scrap_date_gmt' => $scrap_date_gmt
		    );
		    
		    if ( ! empty( $postarr['post_id'] ) ) {	 
		        // Get the post ID and GUID.
		        $post_ID = $postarr['post_id'];
		        $post_before = get_post( $post_ID );
		        if ( is_null( $post_before ) ) {
		            if ( $wp_error ) {
			            $data['title'] = '에러!';
		                $data['content'] = new WP_Error( 'invalid_post', __( 'Invalid post ID.' ) );
		            }
		        } else {
			        $guid = get_post_field( 'guid', $post_ID );	        
					$post_type = $post_before->post_type;
					
					$postarr['guid'] = $guid;
					$postarr['post_type'] = $post_type;
						
					if( false === $wpdb->insert($table_name, $postarr)){
						$data['title'] = '에러!';
						$data['content'] = new WP_Error('db_insert_error', __('Could not insert scrap into the database'), $wpdb->last_error);
					} else {
						$scrap_ID = (int) $wpdb->insert_id;
						
						$data['title'] = '스크랩이 완료되었습니다!';
						$data['content'] = '스크랩하신 내용(#'.$scrap_ID.')은 마이페이지에서 확인하실 수 있습니다.';		
						$data['button'] = '<a class="btn btn03" href="/mypage/scrap">마이페이지</a>';
					}		        
		        }
		    } else {
				$data['title'] = '에러!';
				$data['content'] = '스크랩하려는 포스트가 존재하지 않습니다.';
		    }			
		}
		
		echo json_encode($data);		
		wp_die(); // 적당한 response 를 반환하고 즉시 종료.
	}
	
	static function ajax_login() {
		$data['title'] = '로그인하세요.';
		$data['content'] = '로그인 사용자만 스크랩이 가능합니다. <br />로그인 해주세요.';
		$data['button'] = '<a class="btn btn-info" href="/login">로그인</a>';
		echo json_encode($data);
		wp_die(); // 적당한 response 를 반환하고 즉시 종료.
	}
	
	static function scrap_delete(){
		global $wpdb; // 데이터베이스에 접근할 수 있는 방법을 제공
		$scrap_ID = $_POST['scrap_id'];
		$table_name = $wpdb->prefix . 'scrap';
		$wpdb->delete( $table_name, array( 'scrap_id' => $scrap_ID ) );
		$data['title'] = '내 스크랩';
		$data['content'] = '스크랩(#'.$scrap_ID.')이 정상적으로 삭제되었습니다.';
		echo json_encode($data);
		wp_die();
	}
	
	static function scrap_total_count(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'scrap';
		$user_id = get_current_user_id();
		$results = $wpdb->get_results("select post_id, scrap_date from $table_name where post_author = $user_id");
		print count($results);
	}
	
	static function scraplist() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'scrap';
		$user_id = get_current_user_id();
		$page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;		
		$page_row = 10; // 한 페이지에 보일 글 수
		$start_recode = ($page-1)*$page_row;
		$i = 1;
		$results = $wpdb->get_results("select scrap_id, post_id, scrap_date, post_type from $table_name where post_author = $user_id order by scrap_date desc limit $start_recode, $page_row");
		echo "<ul class='lc03'>";
		if($results):		
			foreach($results as $scrap){
				$post = get_post($scrap->post_id);
				$date = date('Y.m.d H:i:s', strtotime($scrap->scrap_date));
				$link = esc_url( get_permalink($post->ID));
				$obj = get_post_type_object( $post->post_type );				
				$category = get_the_category(get_the_id());
				$excerpt = get_the_excerpt();
				$post_type = $scrap->post_type;
				if($post_type == 'research') $post_type = 'study';
				elseif($post_type == 'events') $post_type = 'event';
				else $post_type = 'lab';
				$comment_count = wp_count_comments(get_the_id());
?>				
				<li class="l<?php echo $i.' '.$post_type;?>"><!-- 카테고리 코드 : study,event,lab,user -->
				<em><?php echo soinnolab_taxonomies_terms_links($post->ID, '');?></em>
				<dl>
					<dt><a href="<?php echo esc_url(get_permalink($post->ID));?>"><?php echo $post->post_title;?></a></dt>
					<dd><?php echo $date;?><i>(<?php echo $comment_count->approved;?>)</i></dd>
				</dl>
				<a class="btn03 scrap delete" href="#" data-id="<?php echo $scrap->scrap_id;?>">삭제하기</a>
			</li>
<?php
				$i++;
			}
		else:
			echo '<li>스크랩이 존재하지 않습니다.</li>';
		endif;
		echo "</ul>";		
	}
	
	static function pagination(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'scrap';
		$user_id = get_current_user_id();		
		// 페이징 처리를 위한 변수 세팅
		$page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$page_row = 10; // 한 페이지에 보일 글 수
		$page_scale = 5; // 한줄에 보여질 페이지 수
		$results = $wpdb->get_results("select post_id, scrap_date from $table_name where post_author = $user_id order by scrap_date desc");
		$total = $wpdb->num_rows; //전체 글 개수
		$page_num = ceil($total/$page_row);//전체 페이지 수
		$start_recode = ($page-1)*$page_row;
		$paging_str = array();
		if($page>1) $paging_str[] = "<a class='first' href=/mypage/scrap?page=1\"><<</a>";
		//페이징에 표시될 시작 페이지
		$start_page = ((ceil($page/$page_scale)-1)*$page_scale)+1;
		//페이징에 표시될 마지막 페이지
		$end_page = $start_page+$page_scale-1;
		if($end_page >= $page_num) $end_page = $page_num;
		//이전 페이징으로 가는 링크
		if ($start_page > 1) $paging_str[] = "<a class='prev' href='/mypage/scrap?paged=".($start_page-1)."'><</a>";
		//페이지 출력 부분 링크
		if ($page_num > 1) {
		    for ($i=$start_page;$i<=$end_page;$i++) {
		        // 현재 페이지가 아니면 링크 걸기
		        if ($page != $i){
		            $paging_str[] = "<a href='/mypage/scrap?paged=".$i."'>$i</a>";
		        // 현재 페이지면 굵게 표시하기
		        }else{
		            $paging_str[] = "<a class='on'>$i</a>";
		        }
		    }
		}
		//다음 페이징으로 가는 링크
		if ($page_num > $end_page){
		    $paging_str[] = "<a class='next' href='/mypage/scrap?paged=".($end_page+1)."'>></a>";
		}
		//마지막 페이지 링크
		if ($page < $page_num) {
		    $paging_str[] = "<a class='last' href='/mypage/scrap?paged=".$page_num."'>>></a>";
		}
		
		echo "<div class='pg01'>";
		foreach($paging_str as $str){
			echo "$str";
		}
		echo "</div>";
	}
}
register_activation_hook(__FILE__, array('Scrap', 'install'));
add_shortcode('scrap-bookmark', array('Scrap', 'bookmark'));
add_shortcode('scrap-list', array('Scrap', 'scraplist'));
add_shortcode('scrap-pagination', array('Scrap', 'pagination'));
add_shortcode('scrap-total-count', array('Scrap', 'scrap_total_count'));
add_action( 'wp_ajax_bookmark', array('Scrap', 'ajax'));
add_action( 'wp_ajax_bookmark_delete', array('Scrap', 'scrap_delete'));
add_action( 'wp_ajax_nopriv_bookmark', array('Scrap', 'ajax_login'));

function scrap_uninstall() {
    global $wpdb;
    $table = $wpdb->prefix."scrap";

	$wpdb->query("DROP TABLE IF EXISTS $table");
	delete_option('scrap_db_version');
}
register_deactivation_hook( __FILE__, 'scrap_uninstall' );

function scrap_script(){
	global $wp_query;
	if(isset($wp_query->query['pagename'])) $pagename = $wp_query->query['pagename'];
	else $pagename = '';	
	if($wp_query->is_single || $pagename = 'mypage/scrap'){
		wp_enqueue_script('scrap', plugins_url('/js/scrap.js', __FILE__), false, '1.0.0');
	}
}
add_action('wp_enqueue_scripts', 'scrap_script');