<?php
/*
Plugin Name: Seoul Innovation Research Lab Newsletter
Description: 뉴스레터 관련 커스텀 포스트 타입 생성 및 엑셀 다운로드, 신청받기 등의 기능을 제공합니다.
Author: Slowalk
Version: 1.0
Author URI: http://www.slowalk.co.kr/
*/
C:\Users\taecheonin\Desktop\plugins\soinnolab_newsletter\soinnolab_newsletter.php
function cptui_register_my_cpts_newsletter() {
	$labels = array(
		"name" => __( '뉴스레터', 'soinnolab' ),
		"singular_name" => __( '뉴스레터', 'soinnolab' ),
		"all_items" => __( '모든 뉴스레터', 'soinnolab' ),
		"add_new" => __( '새 뉴스레터 작성', 'soinnolab' ),
		"add_new_item" => __( '새 뉴스레터 작성', 'soinnolab' ),
		"edit_item" => __( '뉴스레터 수정', 'soinnolab' ),
		"new_item" => __( '새 뉴스레터', 'soinnolab' ),
		"not_found" => __( '뉴스레터 신청 이메일이 존재하지 않습니다.', 'soinnolab' ),
		"not_found_in_trash" => __( '쓰레기통에 뉴스레터 신청 이메일이 존재하지 않습니다.', 'soinnolab' ),
		);

	$args = array(
		"label" => __( '뉴스레터', 'soinnolab' ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"has_archive" => false,
		"show_in_menu" => true,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "newsletter", "with_front" => true ),
		"query_var" => true,
				
		"supports" => array( "title" ),				
	);
	register_post_type( "newsletter", $args );
}
add_action( 'init', 'cptui_register_my_cpts_newsletter' );

function newsletter_form_ajax(){	
	//신청한 이메일이 이미 등록된 이메일인지 체크
	$post = get_page_by_title($_POST['email'], 'OBJECT', 'newsletter');
	if(empty($post)){
		$data['title'] = '뉴스레터신청이 완료되었습니다.';
		$data['email'] = $_POST['email'];
		$data['content'] = '입력하신 이메일 주소로 뉴스레터를 보내드리겠습니다.';
		$post_author = get_current_user_id();
		//뉴스레터 커스텀 포스트 타입에 데이터 저장
		$newsletter = $my_post = array(
			'post_title'    => wp_strip_all_tags( $_POST['email'] ),
			'post_status'   => 'publish',
			'post_author'   => $post_author,
			'post_type' => 'newsletter'
		);
		// Insert the post into the database
		wp_insert_post( $newsletter );		
	} else {
		$data['title'] = '신청하신 이메일은 이미 등록되었습니다!';
		$data['email'] = $_POST['email'];
		$data['content'] = '다른 이메일 주소를 입력해 주세요.';
	}
	echo json_encode($data);
	wp_die(); // 적당한 response 를 반환하고 즉시 종료.
}
add_action( 'wp_ajax_newsletter', 'newsletter_form_ajax');
add_action( 'wp_ajax_nopriv_newsletter', 'newsletter_form_ajax');

function newsletter_script(){	
	wp_enqueue_script('newsletter', plugins_url('/soinnolab_newsletter.js', __FILE__), false, '1.0.0');
}
add_action('wp_enqueue_scripts', 'newsletter_script');

function newsletter_form(){
	echo		
		'<p>사회혁신리서치랩의 소식을 받아보세요!</p>
		 <div class="cf03 newsletter">
			<fieldset>
				<label for="newsletter_email">이메일주소</label>
				<input type="email" name="query" id="newsletter_email" class="email" placeholder="신청 이메일"/>
				<button class="submit">뉴스레터 신청</button>
			</fieldset>
		 </div>';
}
add_shortcode('soinnolab-newsletter', 'newsletter_form');

/**
 * Adds "Import" button on module list page
 */
function excelbtn(){
    global $current_screen;
    // Not our post type, exit earlier
    // You can remove this if condition if you don't have any specific post type to restrict to. 
    if ('edit-newsletter' != $current_screen->id) {
        return;
    }

    ?>
        <script type="text/javascript">
            jQuery(document).ready( function($)
            {
                $(".wrap h1").append("<a href='<?php echo plugins_url('', __FILE__);?>/soinnolab_xls.php' class='page-title-action' target='_blank'>엑셀 출력하기</a>");
            });
        </script>
    <?php
}
add_action('admin_head-edit.php', 'excelbtn');