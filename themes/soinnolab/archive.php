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
if (isset($_COOKIE['lang']) && $_COOKIE['lang'] == 'eng') {
    get_header('eng');
    $lang = 'eng';
    $menu = 'tab-eng';
    $args = array(
        'post_type' => 'post',
        'meta_key' => 'lang',
        'meta_value' => 'en',
        'orderby' => 'date',
        'order' => 'DESC',
    );
    $args = array_merge($wp_query->query, $args);
    query_posts($args);
    $term_slug = soinnalab_fanal_url(get_current_url());
    $term = get_term_by('slug', $term_slug, 'category-eng');
    $term_name = $term->name;
    ?>
    <!-- 서브 영역 -->
    <div class="fc02 lab" id="main-content"><!-- 카테고리 코드 : study,event,lab,user -->
        <div class="th01">
            <dl class="inner">
                <dt>Lab Board</dt>
                <dd class="tabs two-childs">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'tab-eng',
                        'menu_class' => 'tab-menu',
                    ));
                    ?>
                </dd>
            </dl>
        </div>
        <div class="inner tc01">
            <!-- 컨텐츠 영역 -->
            <div class="fc03">
                <dl class="ng03 leftSide" data-sync-id="board_search_option" data-current-value="1">
                    <!-- 값을 선택하면 'board_search_option'의 id값을 가진 input에 값을 전송함 -->
                    <dt><a href="#select1"><?php echo $term_name; ?></a></dt>
                    <dd>
                        <ul class="lab-category">
                            <?php soinnolab_taxonomies_links('category-eng', '<li>', '</li>'); ?>
                        </ul>
                    </dd>
                </dl>
            </div>
            <ul class="lc01 lc01_01 lab">
                <?php
                global $lab_class;
                $lab_class = 1;
                while (have_posts()): the_post();
                    get_template_part('template-parts/content', 'list-eng');
                endwhile;
                wp_reset_postdata();
                ?>
            </ul>
            <?php soinnolab_numeric_posts_nav($args); ?>
            <!-- //컨텐츠 영역 -->
        </div>
    </div>
    <script>
        jQuery(document).ready(function ($) {
            $(".lab-category a").click(function () {
                var val = $(this).attr('href');
                var hostname = $(location).attr('hostname');
                window.location.href = val;
            });
            $("li#menu-item-215.menu-item.menu-item-type-post_type.menu-item-object-page.menu-item-215").addClass('on');
        });
    </script>
    <!-- //서브 컨텐츠 영역 -->
    <?php get_footer('eng'); ?>
<?php } else {
    get_header();
    $lang = '';
    $menu = 'tab';
    ?>
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
                $term = get_term_by('name', single_term_title('', false), 'research_cat');
                $args = array(
                    'post_type' => $ptype,
                    'taxonomy' => 'research_cat',
                    'term' => $term->slug,
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
                $term = get_term_by('name', single_term_title('', false), 'research_cat');
                $research = new WP_Query(array(
                    'post_type' => 'research',
                    'taxonomy' => 'research_cat',
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
                    'taxonomy' => 'research_cat',
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
<?php } ?>