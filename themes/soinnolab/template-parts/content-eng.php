<?php
/**
 * The template part for displaying content
 */
?>
<!-- 컨텐츠 영역 -->
<div class="th02">
    <em><?php echo soinnolab_taxonomies_terms_links(get_the_id(), '', false, 'category-eng'); ?></em>
    <h1><?php the_title(); ?></h1>
    <ul>
        <li><a href="#" title="공유하기"><i class="xi-share-alt"></i></a>
            <ul>
                <li><a href="#">Facebook</a></li>
                <li><a href="#">Twitter</a></li>
                <li><a href="#">Kakao</a></li>
            </ul>
        </li>
        <li><a href="#" title="인쇄하기"><i class="xi-print"></i></a></li>
    </ul>
</div>
<div class="postArea">
    <!-- 포스트 영역 -->
    <p><?php the_post_thumbnail('full'); ?></p>
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

<div class="fc04"><a class="btn02" href="/lab/eng">To List</a></div>
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