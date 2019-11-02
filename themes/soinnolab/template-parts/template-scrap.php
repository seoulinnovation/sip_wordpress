<?php
/***
 * Template Name: 마이페이지 - 스크랩목록
 */
get_header() ?>
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
            <h1 class="th05 th05_01">스크랩 목록(<?php do_shortcode('[scrap-total-count]'); ?>)</h1>
            <?php do_shortcode("[scrap-list]"); ?>
            <?php do_shortcode("[scrap-pagination]"); ?>
        </div>
    </div>
<?php get_footer(); ?>