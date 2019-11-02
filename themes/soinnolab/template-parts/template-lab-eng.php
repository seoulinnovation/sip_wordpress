<?php
/***
 * Template Name: 사회혁신랩소식 영문 아카이브
 */
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    'post_type' => 'post',
    'posts_per_page' => 10,
    'meta_key' => 'lang',
    'meta_value' => 'en',
    'orderby' => 'date',
    'order' => 'DESC',
    'paged' => $paged
);
$posts = new WP_Query($args);
get_header('eng'); ?>
    <!-- 서브 영역 -->
    <div class="fc02 lab" id="main-content"><!-- 카테고리 코드 : study,event,lab,user -->
        <div class="th01">
            <dl class="inner">
                <dt>Lab Board</dt>
                <dd class="tabs two-childs">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'tab-eng',
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
                    <dt><a href="#select1">All</a></dt>
                    <dd>
                        <ul class="lab-category">
                            <?php soinnolab_taxonomies_links('category-eng', '<li>', '</li>'); ?>
                        </ul>
                    </dd>
                </dl>
            </div>
            <ul class="lc01 lc01_01 lab">
                <?php
                global $lab_class;
                $lab_class = 1;
                while ($posts->have_posts()): $posts->the_post();
                    get_template_part('template-parts/content', 'list-eng');
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
                window.location.href = val;
            });
        });
    </script>
<?php get_footer('eng'); ?>