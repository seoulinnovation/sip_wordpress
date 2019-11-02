<?php
/**
 * The template part for displaying content
 */
?>
<?php
global $list_class;
$excerpt = get_the_excerpt();
?>
    <li class="l<?php echo $list_class; ?>">
        <em><?php echo soinnolab_taxonomies_terms_links(get_the_id()); ?></em>
        <a class="thumb" href="<?php echo esc_url(get_permalink()); ?>">
            <div class="post-thumbnail">
                <?php if (has_post_thumbnail()) {
                    the_post_thumbnail('medium');
                } else { ?>
                    <img src="<?php echo get_template_directory_uri(); ?>/images/default.png" alt="이미지가 없습니다">
                    <?php
                } ?>
            </div>
        </a>
        <dl>
            <dt><?php the_title(sprintf('<a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a>'); ?></dt>
            <dd><?php echo strip_tags($excerpt); ?></dd>
        </dl>
        <?php the_tags('<span>', '', '</span>'); ?>
    </li>
<?php $list_class++; ?>