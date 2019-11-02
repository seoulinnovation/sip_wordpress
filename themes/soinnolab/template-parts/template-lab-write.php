<?php
/***
 * Template Name: LAB 글 쓰기
 */
if (!is_super_admin(get_current_user_id())) {
    wp_redirect(home_url());
    exit();
}
$post_id = '';
$title = '';
$content = '';
$tagstr = '';
$post_thumbnail = '';
$post_thumbnail_id = '';
$cat_name = '선택하세요';
$cat_slug = '';
if (isset($_GET['post_id'])) {

    $post_id = (int)$_GET['post_id'];
    $user_id = get_current_user_id();
    $post = get_post($post_id);
    if ($post->post_author == $user_id || is_super_admin()) {
        $title = $post->post_title;
        $content = $post->post_content;
        $tags = wp_get_post_tags($post_id, 'array');
        $tagstr = '';
        foreach ($tags as $key => $tag) {
            if ($key != 0) $tagstr .= ',';
            $tagstr .= $tag->name;
        }
        $post_thumbnail = get_the_post_thumbnail($post_id);
        $post_thumbnail_id = get_post_thumbnail_id($post_id);
        $category = wp_get_post_terms($post_id, 'research_cat');
        if ($category) {
            $cat_slug = $category[0]->slug;
            $cat_name = $category[0]->name;
        }
    }
}
get_header() ?>
    <!-- 서브 영역 -->
    <div class="fc02 lab" id="main-content"><!-- 카테고리 코드 : study,event,lab,user -->
        <div class="th01">
            <dl class="inner">
                <dt>사회혁신랩</dt>
                <dd class="tabs two-childs">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'tab',
                        'menu_class' => 'tab-menu',
                    ));
                    ?>
                </dd>
            </dl>
        </div>
        <div class="inner tc01">
            <!-- 컨텐츠 영역 -->
            <h1 class="th05">글쓰기</h1>
            <form class="cf05 writeLabForm" method="post" action="/wp-admin/admin-ajax.php"
                  enctype="multipart/form-data">
                <input type="hidden" name="action" value="soinnolab_write"/>
                <input type="hidden" name="section" value="lab"/>
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>"/>
                <input type="hidden" id="lab_language" name="lab_language" value="ko"/>
                <table>
                    <tbody>
                    <tr class="tit">
                        <th><label for="write_title">제목</label><i>*</i></th>
                        <td><input type="text" id="write_title" name="write_title" value="<?php echo $title; ?>"/></td>
                    </tr>
                    <!--tr class="cat">
                        <th><label for="write_category">언어선택</label><i>*</i></th>
                        <td>
                            <input type="hidden" id="lab_language" name="lab_language" value=""/>
                            <dl class="ng03" data-sync-id="lab_language" data-current-value="">
                                <dt><a href="#select1">선택하세요</a></dt>
                                <dd>
                                    <ul>
                                        <li><a href="#" data-value="ko">한국어</a></li>
                                        <li><a href="#" data-value="en">영어</a></li>
                                    </ul>
                                </dd>
                            </dl>
                        </td>
                    </tr-->
                    <tr class="cat">
                        <th><label for="write_category">연구분야</label><i>*</i></th>
                        <td>
                            <input type="hidden" id="lab_category" name="lab_category"
                                   value="<?php echo $cat_slug; ?>"/>
                            <dl class="ng03" data-sync-id="lab_category" data-current-value="<?php echo $cat_slug; ?>">
                                <!-- 값을 선택하면 'board_search_option'의 id값을 가진 input에 값을 전송함 -->
                                <dt><a href="#select1"><?php echo $cat_name; ?></a></dt>
                                <dd>
                                    <ul>
                                        <?php soinnolab_taxonomies_links('category', '<li>', '</li>', 'data-value'); ?>
                                    </ul>
                                </dd>
                            </dl>
                        </td>
                    </tr>
                    <tr class="thumb">
                        <th><label for="write_thumb">썸네일 이미지 첨부</label></th>
                        <td>
                            <?php if ($post_thumbnail_id) { ?>
                                <p><em><?php echo $post_thumbnail; ?></em></p><br/>
                                <input type="hidden" name="post_thumbnail_id" value="<?php echo $post_thumbnail_id; ?>">
                            <?php } ?>
                            <input type="file" id="write_thumb" name="write_thumb"/>
                            <?php if ($post_thumbnail_id) { ?>
                                <button class="btn03 thumbnail delete" type="button">삭제하기</button>
                            <?php } ?>
                            <i>이미지를 업로드 하지 않을 경우, 기본 썸네일로 노출됩니다</i>
                        </td>
                    </tr>
                    <tr class="content">
                        <th><label for="write_content">연구와 관련된 글 작성</label><i>*</i></th>
                        <td><?php wp_editor($content, 'content', array('textarea_name' => 'write_content', 'drag_drop_upload' => true, 'media_buttons' => true)); ?></td>
                    </tr>
                    </tbody>
                </table>
                <div class="fc04">
                    <button class="btn02" type="submit">등록하기</button>
                </div>
            </form>
            <!-- //컨텐츠 영역 -->
        </div>
    </div>
    <!-- //서브 컨텐츠 영역 -->
<?php get_footer(); ?>