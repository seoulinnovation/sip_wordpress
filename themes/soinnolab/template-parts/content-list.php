<?php
/**
 * The template part for displaying content
 */
global $lab_class;
$category = get_the_category(get_the_id());
$excerpt = get_the_excerpt(); ?>
    <li class="l<?php echo $lab_class; ?>">
        <em><a href='/archives/category/<?php echo $category[0]->slug; ?>'><?php echo $category[0]->name; ?></a></em>
        <a class="thumb" href="<?php echo esc_url(get_permalink()); ?>">
            <?php if (has_post_thumbnail()) {
                the_post_thumbnail('full');
            } else { ?>
                <img src="<?php echo get_template_directory_uri(); ?>/images/default.png" alt="이미지가 없습니다">
                <?php
            } ?>
        </a>
        <dl>
            <dt><a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a></dt>
            <dd><?php echo strip_tags($excerpt); ?></dd>
        </dl>
        <i><?php the_date('Y.m.d'); ?></i>
    </li>
<?php $lab_class++; ?>