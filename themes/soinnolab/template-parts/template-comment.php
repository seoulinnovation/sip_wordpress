<?php
/***
 * Template Name: 마이페이지 - 내가 쓴 댓글 확인
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
            <?php
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $user_id = get_current_user_id();
            $args = array(
                //'author_email' => '',
                //'author__in' => '',
                //'author__not_in' => '',
                //'include_unapproved' => '',
                //'fields' => '',
                //'ID' => '',
                //'comment__in' => '',
                //'comment__not_in' => '',
                //'karma' => '',
                //'number' => '',
                //'offset' => '',
                //'orderby' => '',
                //'order' => 'DESC',
                //'parent' => '',
                //'post_author__in' => '',
                //'post_author__not_in' => '',
                //'post_id' => 0,
                //'post__in' => '',
                //'post__not_in' => '',
                //'post_author' => '',
                //'post_name' => '',
                //'post_parent' => '',
                //'post_status' => '',
                //'post_type' => '',
                //'status' => 'all',
                //'type' => '',
                //'type__in' => '',
                //'type__not_in' => '',
                'user_id' => $user_id,
                //'search' => '',
                //'count' => false,
                //'meta_key' => '',
                //'meta_value' => '',
                //'meta_query' => '',
                //'date_query' => null, // See WP_Date_Query
            );

            // The Query
            $comments_query = new WP_Comment_Query;
            $comments = $comments_query->query($args);
            ?>
            <h1 class="th05 th05_01">내가 쓴 댓글 (<?php echo count($comments); ?>)</h1>
            <ul class="lc04">
                <?php
                $class = 1;
                // Comment Loop
                if ($comments) {
                    foreach ($comments as $comment) {
                        $post_id = $comment->comment_post_ID;
                        $post_type = get_post_type($post_id);
                        $post_type_class = soinnolab_get_post_type_css($post_type);
                        ?>
                        <li class="l<?php echo $class . ' ' . $post_type_class; ?>">
                            <!-- 카테고리 코드 : study,event,lab,user -->
                            <dl>
                                <dt>
                                    <a href="<?php echo esc_url(get_permalink($post_id)); ?>"><?php echo $comment->comment_content; ?></a>
                                </dt>
                                <dd><?php echo date('Y.m.d', strtotime($comment->comment_date)); ?></dd>
                            </dl>
                            <p>
                                <em><?php echo soinnolab_taxonomies_terms_links($post_id, '', false); ?></em><?php echo get_the_title($post_id); ?>
                            </p>
                            <span><a class="btn03 comment delete" href="#"
                                     data-id="<?php echo $comment->comment_ID; ?>">삭제하기</a><a class="btn03"
                                                                                              href="<?php echo esc_url(get_permalink($post_id)); ?>">원문보기</a></span>
                        </li>
                        <?php
                        $class++;
                    }
                } else {
                    echo '<li>댓글이 존재하지 않습니다.</li>';
                }
                wp_reset_postdata();
                ?>
            </ul>
            <!-- //컨텐츠 영역 -->
        </div>
    </div>
<?php get_footer(); ?>