<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 */

get_header(); ?>
    <!-- 서브 영역 -->
    <div class="fc02 user" id="main-content"><!-- 카테고리 코드 : study,event,lab,user -->
        <div class="th01">
            <dl class="inner">
                <dt><?php the_title(); ?></dt>
            </dl>
        </div>
        <div class="inner tc01">
            <!-- 컨텐츠 영역 -->
            <?php
            // Start the loop.
            while (have_posts()) : the_post();

                // Include the page content template.
                get_template_part('template-parts/content', 'page');

                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) {
                    comments_template();
                }

                // End of the loop.
            endwhile;
            ?>

            <!-- //컨텐츠 영역 -->
        </div>
    </div>
<?php get_footer(); ?>