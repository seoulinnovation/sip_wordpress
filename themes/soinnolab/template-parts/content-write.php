<?php
/**
 * The template part for displaying content
 */
global $lab_class;
$category = soinnolab_taxonomies_terms_links(get_the_id());
$comment_count = wp_count_comments(get_the_id());
$post_type = get_post_type();
$post_type_class = soinnolab_get_post_type_css($post_type);
$excerpt = get_the_excerpt(); ?>
    <li class="l<?php echo $lab_class . ' ' . $post_type_class; ?>"><!-- 카테고리 코드 : study,event,lab,user -->
        <em><?php echo $category; ?></em>
        <dl>
            <dt><a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a></dt>
            <dd><?php echo get_the_date('Y.m.d'); ?><i>(<?php echo $comment_count->approved; ?>)</i></dd>
        </dl>
        <span>
		<a class="btn03 write delete" href="#" data-id="<?php echo get_the_ID(); ?>">삭제하기</a>
		<a class="btn03" href="/<?php echo $post_type; ?>-write?post_id=<?php echo get_the_ID(); ?>">수정하기</a>
	</span>
    </li>
<?php $lab_class++; ?>