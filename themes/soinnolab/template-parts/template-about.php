<?php
/***
 * Template Name: About 페이지
 */
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
            <div class="postArea">
                <!-- 포스트 영역 -->
                <?php
                while (have_posts()): the_post();
                    the_content();
                endwhile;
                ?>
                <!-- //포스트 영역 -->
            </div>
            <!-- //컨텐츠 영역 -->
        </div>
    </div>
    <!-- //서브 컨텐츠 영역 -->
<?php get_footer(); ?>