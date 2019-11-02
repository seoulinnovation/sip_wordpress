<?php
/**
 * The template part for displaying content
 */
$category = get_the_category(get_the_id());
?>
<!-- 컨텐츠 영역 -->
<div class="th02">
    <em><?php echo $category[0]->name; ?></em>
    <h1><?php the_title(); ?></h1>
    <?php require get_template_directory() . '/template-parts/social.php'; ?>
</div>
<div class="postArea">
    <!-- 포스트 영역 -->
    <!--p><?php the_post_thumbnail('full'); ?></p-->
    <?php
    /* translators: %s: Name of current post */
    the_content(sprintf(
        __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'soinnolab'),
        get_the_title()
    ));
    ?>
    <!-- //포스트 영역 -->
</div>
<?php if (is_tag()) { ?>
    <div class="ng02">
        <i class="xi-tags"></i>
        <?php the_tags('<span>', '', '</span>'); ?>
    </div>
<?php } ?>
<?php $related_posts = soinnolab_related_posts(get_the_id()); ?>
<?php if ($related_posts && $related_posts->found_posts): ?>
    <div class="lc02">
        <h3>연관 행사</h3>
        <ul>
            <?php
            while ($related_posts->have_posts()): $related_posts->the_post();
                get_template_part('template-parts/content', 'related');
            endwhile;
            wp_reset_postdata();
            ?>
        </ul>
        <?php echo soinnolab_taxonomies_terms_links(get_the_id(), '더보기'); ?>
    </div>
<?php endif; ?>
<div class="fc04">
    <?php if ($post_type == '') { ?>
        <a class="btn02" href="/lab">목록으로</a>
    <?php } else { ?><a class="btn02" href="/archives/<?php echo $post_type; ?>">목록으로</a><?php } ?>
</div>
<?php
edit_post_link(
    sprintf(
    /* translators: %s: Name of current post */
        __('<span class="screen-reader-text"> "%s"</span> 수정하기', 'soinnolab'),
        get_the_title()
    ),
    '<span class="edit-link">',
    '</span>'
);
?>
<!-- //컨텐츠 영역 -->