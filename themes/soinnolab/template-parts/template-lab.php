<?php
/***
 * Template Name: 사회혁신랩소식 아카이브
 */
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    'post_type' => 'post',
    'posts_per_page' => 10,
    'meta_key' => 'lang',
    'meta_value' => 'ko',
    'orderby' => 'date',
    'order' => 'DESC',
    'paged' => $paged
);
if (isset($_GET['search'])) $args['s'] = $_GET['search'];
$posts = new WP_Query($args);
$s = isset($_GET['search']) ? $_GET['search'] : '';
$categories = get_categories();
get_header(); ?>
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
            <div class="fc03">
                <dl class="ng03 leftSide" data-sync-id="board_search_option" data-current-value="1">
                    <!-- 값을 선택하면 'board_search_option'의 id값을 가진 input에 값을 전송함 -->
                    <dt><a href="#select1">전체</a></dt>
                    <dd>
                        <ul class="lab-category">
                            <?php foreach ($categories as $cat) { ?>
                                <li><a href="<?php echo $cat->slug ?>"><?php echo $cat->name; ?></a></li>
                            <?php } ?>
                        </ul>
                    </dd>
                </dl>
                <?php if (is_super_admin(get_current_user_id())): ?><a class="btn03 rightSide" href="/lab-write">LAB 연구
                    글쓰기</a><?php endif; ?>
                <form class="cf04 rightSide" action="<?php echo esc_url(home_url('/lab')); ?>" method="get">
                    <label for="board_search_query">검색어</label>
                    <input type="search" class="search-field" name="ls" id="board_search_query" placeholder="검색"
                           value="<?php echo $s; ?>"/>
                    <button><i class="xi-search"></i></button>
                </form>
            </div>
            <ul class="lc01 lc01_01 lab">
                <?php
                global $lab_class;
                $lab_class = 1;
                while ($posts->have_posts()): $posts->the_post();
                    get_template_part('template-parts/content', 'list');
                endwhile;
                wp_reset_postdata();
                ?>
            </ul>
            <?php soinnolab_numeric_posts_nav($args); ?>
            <!-- //컨텐츠 영역 -->
        </div>
    </div>
    <script>
        jQuery(document).ready(function ($) {
            $(".lab-category a").click(function () {
                var val = $(this).attr('href');
                var hostname = $(location).attr('hostname');
                window.location.href = 'http://' + hostname + '/archives/category/' + val;
            });
        });
    </script>
<?php get_footer(); ?>