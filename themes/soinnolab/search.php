<?php
/**
 * The template for displaying search results pages
 */
global $wp_query;
$current_cat = soinnalab_fanal_url($_SERVER['REQUEST_URI']);
switch ($current_cat) {
    case 'research':
        $title = '연구';
        break;
    case 'events':
        $title = '행사';
        break;
    default:
        $title = '전체검색';
        break;
}
get_header(); ?>
<!-- 서브 영역 -->
<div class="fc02" id="main-content">
    <div class="th01">
        <dl class="inner">
            <dd class="searchResult"><em><?php echo esc_html(get_search_query()); ?></em>에 대한 검색결과입니다.</dd>
        </dl>
    </div>
    <div class="inner tc01">
        <!-- 컨텐츠 영역 -->
        <dl class="th03 study">
            <dt><?php echo $title; ?></dt>
            <dd>(<?php echo $wp_query->found_posts; ?>)</dd>
        </dl>
        <ul class="lc01">
            <?php
            global $list_class;
            $list_class = 1;
            while (have_posts()) : the_post();

                /**
                 * Run the loop for the search to output the results.
                 * If you want to overload this in a child theme then include a file
                 * called content-search.php and that will be used instead.
                 */
                get_template_part('template-parts/content', 'teaser');

                // End the loop.
            endwhile;
            ?>
        </ul>
        <?php soinnolab_numeric_posts_nav(); ?>
        <div class="fc04">
            <form class="cf04" action="<?php echo esc_url(home_url($_SERVER['REQUEST_URI'])); ?>" method="get">
                <label for="board_search_query">검색어</label>
                <input type="text" name="s" id="board_search_query" placeholder="검색"
                       value="<?php echo esc_html(get_search_query()); ?>"/>
                <button><i class="xi-search"></i></button>
            </form>
        </div>
        <!-- //컨텐츠 영역 -->
    </div>
</div>
<?php get_footer(); ?>
