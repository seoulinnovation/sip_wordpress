<?php
$current_cat = soinnalab_fanal_url($_SERVER['REQUEST_URI']);
$cat = get_category_by_slug($current_cat);
$cat_name = $cat->name;
$categories = get_categories();
if (isset($_GET['search'])) {
    global $wp_query;
    $s = array('s' => $_GET['search']);
    $args = array_merge($wp_query->query, $s);
    query_posts($args);
}
if (isset($_COOKIE['lang']) && $_COOKIE['lang'] == 'eng') {
    get_header('eng');
    $lang = 'eng';
    $menu = 'tab-eng';
} else {
    get_header();
    $lang = '';
    $menu = 'tab';
} ?>
    <!-- 서브 영역 -->
    <div class="fc02 lab" id="main-content"><!-- 카테고리 코드 : study,event,lab,user -->
        <div class="th01">
            <dl class="inner">
                <dt><?php if ($lang == 'eng') { ?>Lab Board<?php } else { ?>사회혁신랩<?php } ?></dt>
                <dd class="tabs two-childs">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => $menu,
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
                    <dt><a href="#select1"><?php echo $cat_name; ?></a></dt>
                    <dd>
                        <ul class="lab-category">
                            <li><a href="lab">전체</a></li>
                            <?php foreach ($categories as $cat) { ?>
                                <li><a href="<?php echo $cat->slug ?>"><?php echo $cat->name; ?></a></li>
                            <?php } ?>
                        </ul>
                    </dd>
                </dl>
                <form class="cf04 rightSide" action="<?php echo esc_url(home_url('/lab')); ?>" method="get">
                    <label for="board_search_query">검색어</label>
                    <input type="search" class="search-field" name="ls" id="board_search_query" placeholder="검색"
                           value="<?php echo $s; ?>"/>
                    <button><i class="xi-search"></i></button>
                </form>
            </div>
            <ul class="lc01 lc01_01">
                <?php
                global $lab_class;
                $lab_class = 1;
                while (have_posts()): the_post();
                    get_template_part('template-parts/content', 'list');
                endwhile;
                wp_reset_postdata();
                ?>
            </ul>
            <?php soinnolab_numeric_posts_nav(); ?>
            <!-- //컨텐츠 영역 -->
        </div>
    </div>
    <script>
        jQuery(document).ready(function ($) {
            $(".lab-category a").click(function () {
                var val = $(this).attr('href');
                var hostname = $(location).attr('hostname');
                if (val == 'lab') window.location.href = 'http://' + hostname + '/' + val;
                else window.location.href = 'http://' + hostname + '/archives/category/' + val;
            });
            $("li#menu-item-82.menu-item.menu-item-type-post_type.menu-item-object-page.menu-item-82").addClass('on');
        });
    </script>
<?php get_footer(); ?>