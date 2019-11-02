<?php
/***
 * Template Name: 개발페이지
 */
get_header() ?>
    <!-- 서브 영역 -->
    <div class="fc02 user" id="main-content"><!-- 카테고리 코드 : study,event,lab,user -->
        <div class="th01">
            <dl class="inner">
                <dt><?php the_title(); ?></dt>
            </dl>
        </div>
        <div class="inner tc01">
            <ul>
                <?php
                $today = date('Ymd');
                echo $today;
                $args = array(
                    'post_type' => 'events',
                    'meta_query' => array(
                        array(
                            'key' => 'start_date',
                            'compare' => '<=',
                            'value' => $today,
                        ),
                        array(
                            'key' => 'finish_date',
                            'compare' => '>=',
                            'value' => $today,
                        )
                    ),
                );
                $events = new WP_Query($args);
                //print_r($events);

                soinnolab_get_event_count('event');
                ?>
            </ul>
        </div>
    </div>
<?php get_footer(); ?>