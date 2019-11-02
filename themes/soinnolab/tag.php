<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */

get_header(); ?>
<!-- 서브 영역 -->
<div class="fc02" id="main-content"><!-- 카테고리 코드 : study,event,lab,user -->
    <div class="th01">
        <dl class="inner">
            <dd class="searchResult">
                <em><?php echo single_term_title('', false); ?></em><?php if (isset($_GET['search'])) echo '<em>' . $_GET['search'] . '</em>'; ?>
                에 대한 검색결과입니다.
            </dd>
        </dl>
    </div>
    <div class="inner tc01">
        <!-- 컨텐츠 영역 -->
        <?php
        global $list_class;
        if (isset($_GET['ptype'])) {
            $ptype = $_GET['ptype'];
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $term = get_term_by('name', single_tag_title('', false), 'post_tag');
            $args = array(
                'post_type' => $ptype,
                'taxonomy' => 'post_tag',
                'term' => $term->slug,
                'posts_per_page' => 9,
                'orderby' => 'date',
                'order' => 'DESC',
                'paged' => $paged
            );
            if (isset($_GET['search'])) {
                $args['s'] = $_GET['search'];
            }
            $result = new WP_Query($args);
            if ($ptype == 'research') {
                $title = '연구';
                $class = 'study';
            } elseif ($ptype == 'events') {
                $title = '행사';
                $class = 'event';
            }
            ?>
            <!-- 컨텐츠 영역 -->
            <dl class="th03 <?php echo $class; ?>">
                <dt><?php echo $title; ?></dt>
                <dd>(<?php echo $result->found_posts; ?>)</dd>
            </dl>
            <ul class="lc01">
                <?php
                $list_class = 1;
                while ($result->have_posts()): $result->the_post();
                    get_template_part('template-parts/content', 'teaser');
                endwhile;
                wp_reset_postdata();
                ?>
            </ul>
            <?php soinnolab_numeric_posts_nav($args); ?>
            <div class="fc04">
                <form class="cf04" action="<?php echo esc_url(home_url($_SERVER['REQUEST_URI'])); ?>" method="get">
                    <label for="board_search_query">검색어</label>
                    <input type="text" name="ps" id="board_search_query" placeholder="검색"
                           value="<?php echo @ $_GET['search']; ?>"/>
                    <button><i class="xi-search"></i></button>
                </form>
            </div>
            <!-- //컨텐츠 영역 -->
            <?php
        } else {
            $term = get_term_by('name', single_term_title('', false), 'post_tag');
            $research = new WP_Query(array(
                'post_type' => 'research',
                'taxonomy' => 'post_tag',
                'term' => $term->slug,
                'posts_per_page' => 3,
            ));
            if ($research->found_posts) {
                ?>
                <div class="fc05">
                    <dl class="th03 study">
                        <dt>연구</dt>
                        <dd>(<?php echo $research->found_posts; ?>)</dd>
                    </dl>
                    <ul class="lc01">
                        <?php
                        $list_class = 1;
                        while ($research->have_posts()) : $research->the_post();
                            get_template_part('template-parts/content', 'teaser');
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </ul>
                    <a class="more" href="<?php echo $_SERVER['REQUEST_URI'] . '?ptype=research'; ?>">더보기</a>
                </div>
                <?php
            }
            $events = new WP_Query(array(
                'post_type' => 'events',
                'taxonomy' => 'post_tag',
                'term' => $term->slug,
                'posts_per_page' => 3,
            ));
            if ($events->found_posts) {
                ?>
                <div class="fc05">
                    <dl class="th03 event">
                        <dt>행사</dt>
                        <dd>(<?php echo $events->found_posts; ?>)</dd>
                    </dl>
                    <ul class="lc01">
                        <?php
                        $list_class = 1;
                        while ($events->have_posts()) : $events->the_post();
                            get_template_part('template-parts/content', 'teaser');
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </ul>
                    <a class="more" href="#">더보기</a>
                </div>
            <?php }
        } ?>
        <!-- //컨텐츠 영역 -->
    </div>
</div>
<!-- //서브 컨텐츠 영역 -->
<?php get_footer(); ?>
