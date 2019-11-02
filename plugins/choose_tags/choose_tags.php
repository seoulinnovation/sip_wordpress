<?php
/*
Plugin Name: Seoul Innovation Research Lab Choose Tags
Description: 서울혁신리서치랩 선택 태그 노출을 지원합니다.
Author: Slowalk
Version: 1.0
Author URI: http://www.slowalk.co.kr/
*/
C:\Users\taecheonin\Desktop\plugins\choose_tags\choose_tags.php
function register_soinnolab_tags_setting_menu_page() {
	add_submenu_page('options-general.php','노출 태그 설정','노출 태그 설정','manage_options','soinnolab-tags-setting-menu_page','soinnolab_tags_setting_menu_page');	
}

function soinnolab_tags_setting_menu_page() {
	$update = false;
	$over = false;
	$notags = '';
	if(isset($_POST['option_page']) && $_POST['option_page'] == 'soinnolab_choose_tags'){
		if(isset($_POST['action']) && $_POST['action'] == 'update'){
			$tags_array = explode(',',$_POST['tags']);
			$tags = '';
			$count = 1;
			foreach($tags_array as $tag){				
				$tag = strip_tags($tag);
				$term = get_term_by('name', $tag, 'post_tag');
				if($term){
					if($count > 5){
						$over = true;
						break;
					}
					if(!empty($tags)) $tags .= ',';
					$tags .= $term->name;					
					$count++;
				} else {
					if(!empty($notags)) $notags .= ',';
					$notags = $tag;
				}
			}
			$tags = serialize($tags);
			$update = update_option('_soinnolab_choose_tags', $tags);			
		}
	}
?>
	<div class="wrap">		
		<h1>노출 태그 설정</h1>
		<?php if($update){ ?>
		<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
			<p><strong>설정을 저장했습니다. 에디터에서 <code>[soinnolab-choose-tags]</code> 라고 입력하거나, php파일에서 <code>do_shortcode('[soinnolab-choose-tags]');</code>라고 입력하세요.</strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text">이 알림 무시하기.</span>
			</button>
		</div>
		<?php } ?>
		<?php if($notags){ ?>
		<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
			<p><strong><code><?php echo $notags;?></code> 존재하지 않는 태그입니다.</strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text">이 알림 무시하기.</span>
			</button>
		</div>
		<?php } ?>
		<?php if($over){ ?>
		<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
			<p><strong>사용자 정의 태그는 최대 5개까지입니다. 다른 태그를 선택하시려면 기존 태그를 삭제하고 선택해 주세요.</p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text">이 알림 무시하기.</span>
			</button>
		</div>
		<?php } ?>
		<em>선택하신 태그들이 shortcode를 통해서 입력한 영역에 노출됩니다.</em>
		<form method="post" action="<?php echo '//'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];?>" novalidate="novalidate">
			<input type="hidden" name="option_page" value="soinnolab_choose_tags">
			<input type="hidden" name="action" value="update">
			<?php				
				$comma = _x( ',', 'tag delimiter' );
				$terms_to_edit = unserialize(get_option('_soinnolab_choose_tags'));
				if ( ! is_string( $terms_to_edit ) ) {
					$terms_to_edit = '';
				}
			?>
			<div class="tagsdiv" id="post_tag">
				<div class="jaxtag">
					<div class="nojs-tags hide-if-js">
						<label for="tax-input-post_tag">새로운 태그 추가/제거</label>
						<p><textarea name="tags" rows="3" cols="20" class="the-tags" id="tax-input-post_tag" aria-describedby="new-tag-post_tag-desc"><?php echo str_replace( ',', $comma, $terms_to_edit ); // textarea_escaped by esc_attr() ?></textarea></p>
					</div>
					<div class="ajaxtag hide-if-no-js">
						<label class="screen-reader-text" for="new-tag-post_tag">새 태그 추가</label>
						<p>
							<input type="text" id="new-tag-post_tag" name="newtag[post_tag]" class="newtag form-input-tip" size="16" autocomplete="off" aria-describedby="new-tag-post_tag-desc" value="" />
							<input type="button" class="button tagadd" value="<?php esc_attr_e('Add'); ?>" />
						</p>
					</div>
					<p class="howto" id="new-tag-post_tag-desc">각 태그를 쉼표로 분리하세요.</p>
				</div>
				<div class="tagchecklist">
					<?php if($terms_to_edit){
						$term_array = explode(',', $terms_to_edit);
						foreach($term_array as $key => $term){
					?>
					<span><a id="post_tag-check-num-<?php echo $key;?>" class="ntdelbutton" tabindex="<?php echo $key;?>">X</a>&nbsp;<?php echo $term;?></span>
					<?php
						}
					}
					?>
				</div>
			</div>
			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button button-primary" value="변경 사항 저장">
			</p>			
		</form>
	</div>
<?php	
}
add_action('admin_menu', 'register_soinnolab_tags_setting_menu_page');

// Script Add
function soinnolab_choose_tags_script(){
	wp_enqueue_script('suggest');
	wp_enqueue_script('soinnolab_choose_tags',  plugins_url('/choose-tags.js', __FILE__), array('suggest', 'tags-box'));
}
add_action('admin_enqueue_scripts', 'soinnolab_choose_tags_script');

// Generate Shortcode for Tags
function soinnolab_choose_tags(){
	$tags = unserialize(get_option('_soinnolab_choose_tags'));
	if($tags){
		$tags_array = explode(',', $tags);
		foreach($tags_array as $tag){
			$term = get_term_by('name', $tag, 'post_tag');
			// The $term is an object, so we don't need to specify the $taxonomy.
		    $term_link = get_term_link( $term );		    
		    // If there was an error, continue to the next term.
		    if ( is_wp_error( $term_link ) ) {
		        continue;
		    }		 
		    // We successfully got a link. Print it out.
		    echo '<a href="' . esc_url( $term_link ) . '">#' . $term->name . '</a>';
		}
	}
}
add_shortcode('soinnolab-choose-tags', 'soinnolab_choose_tags');