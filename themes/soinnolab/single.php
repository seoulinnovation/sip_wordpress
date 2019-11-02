<?php
/**
 * The template for displaying all single posts and attachments
 */
if (isset($_COOKIE['lang']) && $_COOKIE['lang'] == 'eng') get_header('eng');
else get_header();
$post_type = soinnolab_get_post_type_css($post_type);
?>
<!-- 서브 영역 -->
<div class="fc02 <?php echo $post_type; ?>" id="main-content"><!-- 카테고리 코드 : study,event,lab,user -->
    <div class="th01">
        <dl class="inner">
            <dt><?php if (isset($_COOKIE['lang']) && $_COOKIE['lang'] == 'eng') echo 'Lab Board'; else echo soinnolab_get_title_by(); ?></dt>
        </dl>
    </div>
    <div class="inner tc01">
        <?php
        // Start the loop.
        while (have_posts()) : the_post();

            // Include the single post content template.
            $research = array('research', 'events');
            $current_post_type = get_post_type();
            if (in_array($current_post_type, $research)) {
                get_template_part('template-parts/content', $current_post_type);
            } else {
                if ($_COOKIE['lang'] == 'eng') {
                    get_template_part('template-parts/content', 'eng');
                } else {
                    get_template_part('template-parts/content', 'single');
                }
            }

            if (is_singular('attachment')) {
                // Parent post navigation.
                the_post_navigation(array(
                    'prev_text' => _x('<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'soinnolab'),
                ));
            }
            // End of the loop.
        endwhile;
        ?>

        <?php
        // If comments are open or we have at least one comment, load up the comment template.
        if (comments_open() || get_comments_number()) {
            comments_template();
        }
        ?>


    </div>
</div>
<!-- //서브 컨텐츠 영역 -->
<?php if (isset($_COOKIE['lang']) && $_COOKIE['lang'] == 'eng') get_footer('eng'); else get_footer(); ?>
