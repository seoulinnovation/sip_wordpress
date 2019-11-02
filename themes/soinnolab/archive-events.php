<?php get_header(); ?>
<?php
$current_user = get_current_user_id();
if ($current_user) $write_link = "href='/events-write'";
else $write_link = "onclick='javascript:jQuery.alert({title:\"회원만 글쓰기가 가능합니다.\", content:false, confirm:function(){location.replace(\"/login\");}});'";
/*** 연구 포스트를 불러온다. ***/
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$sticky = get_option('sticky_posts');
$args = array(
    'post_type' => 'events',
    'posts_per_page' => 9,
    'orderby' => 'date',
    'order' => 'DESC',
    'paged' => $paged
);
$wp_query = new WP_Query($args);
?>
    <div class="fc02 event" id="main-content"><!-- 카테고리 코드 : study,event,lab,user -->
        <div class="th01">
            <dl class="inner">
                <dt>행사</dt>
                <dd class="tags"><?php soinnolab_taxonomies_links('research_cat'); ?></dd>
            </dl>
        </div>
        <div class="inner tc01">
            <!-- 컨텐츠 영역 -->
            <div class="fc03">
                <a class="btn03 leftSide" <?php echo $write_link; ?>>행사 글쓰기</a>
                <form class="cf04 rightSide" action="<?php echo esc_url(home_url('/archives/events')); ?>" method="get">
                    <label for="board_search_query">검색어</label>
                    <input type="text" id="board_search_query" placeholder="검색"
                           value="<?php echo get_search_query(); ?>" name="s"/>
                    <button><i class="xi-search"></i></button>
                </form>
            </div>
            <ul class="lc01 event">
                <?php
                if ($wp_query->have_posts()) {
                    global $list_class;
                    $list_class = 1;
                    while ($wp_query->have_posts()) {
                        $wp_query->the_post();
                        get_template_part('template-parts/content', 'teaser');
                    }
                }
                ?>
            </ul>
            <?php soinnolab_numeric_posts_nav(); ?>
            <!-- //컨텐츠 영역 -->
        </div>
    </div>
<?php get_footer(); ?>