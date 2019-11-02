<?php
/***
 * Template Name: 마이페이지 - 내가 쓴 글 확인
 */
get_header() ?>
    <!-- 서브 영역 -->
    <div class="fc02 user" id="main-content"><!-- 카테고리 코드 : study,event,lab,user -->
        <div class="th01">
            <dl class="inner">
                <dt><?php the_title(); ?></dt>
                <dd class="tabs">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'mypage',
                        'menu_class' => 'tab-menu',
                    ));
                    ?>
                </dd>
            </dl>
        </div>
        <div class="inner tc01">
            <!-- 컨텐츠 영역 -->
            <?php
            global $lab_class;
            $lab_class = 1;
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $post_author = get_current_user_id();
            $args = array(
                'post_type' => array('research', 'events'),
                'author' => $post_author,
                'posts_per_page' => 10,
                'orderby' => 'date',
                'order' => 'DESC',
                'paged' => $paged
            );
            $write = new WP_Query($args);
            ?>
            <h1 class="th05 th05_01">내가 쓴 글 (<?php echo $write->post_count; ?>)</h1>
            <ul class="lc03">
                <?php
                if ($write->have_posts()):
                    while ($write->have_posts()): $write->the_post();
                        get_template_part('template-parts/content', 'write');
                    endwhile;
                    soinnolab_numeric_posts_nav($args);
                    wp_reset_postdata();
                else:
                    echo '<li>글이 존재하지 않습니다.</li>';
                endif;
                ?>
            </ul>
            <!-- //컨텐츠 영역 -->
        </div>
    </div>
<?php get_footer(); ?>