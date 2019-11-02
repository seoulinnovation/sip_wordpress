<?php
global $bestwrites_class;
$posttags = get_the_tags();
$excerpt = get_the_excerpt();
$post_type_class = soinnolab_get_post_type_css($post->post_type);
$tags = '';
if ($posttags) {
    foreach ($posttags as $k => $tag) {
        $tags .= '<a href="' . get_tag_link($tag->term_id) . '" class="tag item">' . $tag->name . '</a>';
        if ($k > 3) break;
    }
}
?>
    <li class="l<?php echo $bestwrites_class . ' ' . $post_type_class; ?>">
        <em><?php echo soinnolab_taxonomies_terms_links(get_the_id()); ?></em>
        <a class="thumb" href="<?php echo esc_url(get_permalink()); ?>">
            <?php if (has_post_thumbnail()) {
                the_post_thumbnail('full');
            } else { ?>
                <img src="<?php echo get_template_directory_uri(); ?>/images/default.png" alt="이미지가 없습니다">
                <?php
            } ?>
        </a>
        <dl>
            <dt><?php the_title(sprintf('<a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a>'); ?></dt>
            <dd><?php echo strip_tags($excerpt); ?></dd>
        </dl>
        <span><?php echo $tags; ?></span>
    </li>
<?php $bestwrites_class++; ?>