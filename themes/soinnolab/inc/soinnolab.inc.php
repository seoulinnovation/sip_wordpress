<?php
/***
 * 목차
 * 1. soinnolab_setup() - 테마 세팅
 * 2. 메인 세팅
 *    - 1. soinnolab_get_slide() - 메인슬라이드
 *    - 2. soinnolab_get_bestwrites() - 인기 연구 모아보기
 *    - 3. soinnolab_get_event() - 메인 페이지 오늘의 행사
 *    - 4. soinnolab_get_research() - 메인 페이지 오늘의 연구
 *    - 5. soinnolab_get_event_count() - 메인 페이지 오늘의 행사 카운트 가져오기
 * 3. 페이지 뷰 카운트
 *    - 1. soinnolab_set_post_views() - 페이지 뷰 카운트 증가 함수
 *    - 2. soinnolab_track_post_views() - 실제 뷰 카운트 증가 wp_head 에 설정
 *    - 3. soinnolab_get_post_views() - 뷰 카운트 가져오기
 * 4. 요약글
 *    - 1. soinnolab_excerpt_length() - 요약글 수 20으로 설정
 *    - 2. soinnolab_excerpt_more() - 요약글 줄임말 ... 으로 설정
 * 5. 템플릿 파일
 *    - 1. 공통
 *        - 1. soinnolab_numeric_posts_nav() - 숫자 페이지네이션
 *        - 2. soinnolab_taxonomies_terms_links() - 포스트 카테고리를 불러온다.
 *        - 3. soinnolab_cpt_tags() - 커스텀 포스트 타입에 태그 아카이브를 위해 세팅한다.
 *        - 4. soinnolab_get_title_by() - Single페이지 제목을 포스트 타입으로 가져온다.
 *        - 5. soinnolab_related_posts() - 연관글을 가져온다.
 *        - 6. soinnolab_menu() - 메뉴 트레일 워커
 *        - 7. soinnolab_get_post_type_css() - css에 맞춰 포스트 타입 네이밍을 변경한다.
 * 6. 어드민 글 label 변경
 * 7. Url path 가져오기
 * 8. Add Class to Active Menu Item
 * 9. Get User Picture
 * 10. Get Current URL
 * 11. blockusers_init() - 일반 사용자 dashboard(wp-admin)접근 금지
 * 12. modify_post_thumbnail_html() - 이미지 URL에 공백 발생시 사파리에서 깨짐 현상 해결
 */

/***
 * Featured image setup.
 */
function soinnolab_setup()
{
    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support('title-tag');

    /*
     * Enable support for custom logo.
     *
     *  @since Twenty Sixteen 1.2
     */
    add_theme_support('custom-logo', array(
        'height' => 240,
        'width' => 240,
        'flex-height' => true,
    ));

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
     */
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(1200, 9999);

    // This theme uses wp_nav_menu() in two locations.
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'soinnolab'),
        'mypage' => __('Mypage Menu', 'soinnolab'),
        'tab' => __('Tab Menu', 'soinnolab'),
        'tab-eng' => __('Tab English Menu', 'soinnolab'),
    ));

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    /*
     * Enable support for Post Formats.
     *
     * See: https://codex.wordpress.org/Post_Formats
     *

    add_theme_support( 'post-formats', array(
        'aside',
        'image',
        'video',
        'quote',
        'link',
        'gallery',
        'status',
        'audio',
        'chat',
    ) );
    */
    // Indicate widget sidebars can use selective refresh in the Customizer.
    add_theme_support('customize-selective-refresh-widgets');
}

add_action('after_setup_theme', 'soinnolab_setup');

/***
 * 메인 슬라이드
 */
function soinnolab_get_slide()
{
    global $slide_url;
    echo '<ul class="slide">';
    $args = array(
        'posts_per_page' => -1,
        'offset' => 0,
        'post_type' => 'mainslide',
        'post_status' => 'publish',
        'suppress_filters' => true
    );
    $wp_query = new WP_Query($args);
    while ($wp_query->have_posts()) {
        $wp_query->the_post();
        $pid = get_the_id();
        $slide = get_field("slide_img");
        $slide_url = $slide['url'];
        echo '<li class="data-item">';
        get_template_part('template-parts/main', 'slide');
        echo '</li>';
    }
    echo '</ul>';
}

/***
 * 인기 연구 모아보기
 */
function soinnolab_get_bestwrites()
{
    $args = array(
        'post_type' => 'research',
        'posts_per_page' => 3,
        'meta_key' => 'soinnolab_post_views_count',
        'orderby' => 'meta_value_num',
        'order' => 'DESC'
    );
    $popularpost = new WP_Query($args);
    echo '<ul class="lc01 slide">';
    global $bestwrites_class;
    $bestwrites_class = 1;
    while ($popularpost->have_posts()) : $popularpost->the_post();
        get_template_part('template-parts/main', 'bestwrites');
    endwhile;
    echo '</ul>';
}

/***
 * 메인 페이지 오늘의 행사
 */
function soinnolab_get_event()
{
    get_template_part('template-parts/main', 'event');
}

/***
 * 메인 페이지 오늘의 연구
 */
function soinnolab_get_research()
{
    get_template_part('template-parts/main', 'research');
}

/***
 * 메인 페이지 오늘의 행사 카운트 가져오기
 */
function soinnolab_get_event_count($type = '')
{
    switch ($type) {
        case 'today':
            $year = date('Y');
            $month = date('m');
            $day = date('d');
            $args = array(
                'post_type' => 'events',
                'date_query' => array(
                    array(
                        'year' => $year,
                        'month' => $month,
                        'day' => $day,
                    ),
                ),
            );
            break;
        case 'event':
            $today = date('Ymd');
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
            break;
        default:
            // total
            break;
    }
    if (empty($type) || $type == 'total') {
        $event = wp_count_posts('events');
        echo '<span class="timer" data-to="' . $event->publish . '" data-speed="500">' . $event->publish . '</span>';
    } else {
        $event = new WP_Query($args);
        echo '<span class="timer" data-to="' . $event->post_count . '" data-speed="500">' . $event->post_count . '</span>';
    }
}

/***
 * 조회수 설정
 */
function soinnolab_set_post_views($postID)
{
    $count_key = 'soinnolab_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

//To keep the count accurate, lets get rid of prefetching
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

function soinnolab_track_post_views($post_id)
{
    if (!is_single()) return;
    if (empty ($post_id)) {
        global $post;
        $post_id = $post->ID;
    }
    soinnolab_set_post_views($post_id);
}

add_action('wp_head', 'soinnolab_track_post_views');

function soinnolab_get_post_views($postID)
{
    $count_key = 'soinnolab_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
    return $count;
}

/**
 * Filter the except length to 20 characters.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function soinnolab_excerpt_length($length)
{
    return 20;
}

add_filter('excerpt_length', 'soinnolab_excerpt_length', 999);

/**
 * Filter the excerpt "read more" string.
 *
 * @param string $more "Read more" excerpt string.
 * @return string (Maybe) modified "read more" excerpt string.
 */
function soinnolab_excerpt_more($more)
{
    return '...';
}

add_filter('excerpt_more', 'soinnolab_excerpt_more');

/**
 * Numeric Pagination
 */
function soinnolab_numeric_posts_nav($array = false, $pages = '', $range = 2)
{
    if (is_singular() && $array === false)
        return;

    global $wp_query;
    if ($array) {
        query_posts($array);
    }
    $showitems = ($range * 2) + 1;
    $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
    if ($pages == '') {
        $pages = $wp_query->max_num_pages;
        if (!$pages) {
            $pages = 1;
        }
    }
    if (1 != $pages) {
        echo "<div class=\"pg01\">";
        if ($paged > 2 && $paged > $range + 1 && $showitems < $pages) echo "<a class=\"first\" href='" . get_pagenum_link(1) . "'><<</a>";
        if ($paged > 1 && $showitems < $pages) echo "<a class=\"prev\" href='" . get_pagenum_link($paged - 1) . "'><</a>";
        for ($i = 1; $i <= $pages; $i++) {
            if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
                echo ($paged == $i) ? "<a class=\"on\">" . $i . "</a>" : "<a href='" . get_pagenum_link($i) . "'>" . $i . "</a>";
            }
        }
        if ($paged < $pages && $showitems < $pages) echo "<a class=\"next\" href=\"" . get_pagenum_link($paged + 1) . "\">></a>";
        if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages) echo "<a class=\"last\" href='" . get_pagenum_link($pages) . "'>>></a>";
        echo "</div>\n";
    }
}

/**
 * Get taxonomies terms links.
 *
 * @see get_object_taxonomies()
 */
function soinnolab_taxonomies_terms_links($pid, $name = '', $link = true, $taxonomy = 'research_cat')
{
    // Get post by post ID.
    $post = get_post($pid);
    $out = array();
    // Get the terms related to post.
    $terms = get_the_terms($post->ID, $taxonomy);
    if ($name == '더보기') $class = ' class="more"';
    else $class = '';
    if (!empty($terms) && !isset($terms->errors)) {
        foreach ($terms as $term) {
            if (empty($name)) $name = $term->name;
            if ($link) {
                $out[] = sprintf('<a href="%1$s"' . $class . '>%2$s</a>',
                    esc_url(get_term_link($term->slug, $taxonomy)),
                    esc_html($name)
                );
            } else {
                $out[] = $name;
            }
        }
    } else {
        if ($link) {
            $out[] = "<a href='#'>미분류</a>";
        } else {
            $out[] = "미분류";
        }
    }
    return implode('', $out);
}

/***
 * Get taxonomies links.
 */
function soinnolab_taxonomies_links($slug, $prev = '', $next = '', $link = '')
{
    $terms = get_terms(array(
        'taxonomy' => $slug,
        'hide_empty' => false,
    ));
    $out = array();
    if (!empty($terms)) {
        foreach ($terms as $term) {
            if (is_object($term)) {
                if ($link) {
                    $out[] = sprintf($prev . '<a href="#" ' . $link . '="%1$s">%2$s</a>' . $next, esc_html($term->slug), esc_html($term->name));
                } else {
                    $out[] = sprintf($prev . '<a href="%1$s">%2$s</a>' . $next, esc_url(get_term_link($term->slug, $slug)), esc_html($term->name));
                }
            }
        }
    }
    echo implode('', $out);
}

/***
 * Add reserch to tag query for archiving
 */
function soinnolab_cpt_tags($query)
{
    if ($query->is_tag() && $query->is_main_query()) {
        $query->set('post_type', array('post', 'research', 'events'));
    }
}

add_action('pre_get_posts', 'soinnolab_cpt_tags');

/***
 * Get Single page Title by post type
 */
function soinnolab_get_title_by()
{
    $post_type = get_post_type();
    if ($post_type == 'research') $title = '연구';
    elseif ($post_type == 'events') $title = '행사';
    elseif ($post_type == 'post') $title = 'Lab 소식';
    else $title = false;
    return $title;
}

/***
 * 연관 글
 */
function soinnolab_related_posts($post_id)
{
    $post_type = get_post_type();
    $terms = get_the_terms($post_id, 'research_cat');
    if ($terms) {
        foreach ($terms as $term) {
            $item = $term->slug;
        }
        $args = array(
            'post_type' => $post_type,
            'post__not_in' => array($post_id),
            'taxonomy' => 'research_cat',
            'term' => $item,
            'posts_per_page' => 5,
            'orderby' => 'date',
            'order' => 'DESC',
        );
        $related_posts = new WP_Query($args);
        return $related_posts;
    } else {
        return false;
    }
}

/***
 * 메인 메뉴 커스텀 walker
 */
function soinnolab_menu($menu_name)
{
    if (($locations = get_nav_menu_locations()) && isset($locations[$menu_name])) {
        $menu = wp_get_nav_menu_object($locations[$menu_name]);

        $menu_items = wp_get_nav_menu_items($menu->term_id);

        $menu_list = '<menu>';
        $i = 1;
        foreach ((array)$menu_items as $key => $menu_item) {
            $title = $menu_item->title;
            $url = $menu_item->url;
            $menu_list .= '<li class="l' . $i . '"><a href="' . $url . '">' . $title . '</a></li>';
            $i++;
        }
        $menu_list .= '</menu>';
    } else {
        $menu_list = '<menu><li>Menu "' . $menu_name . '" not defined.</li></menu>';
    }
    echo $menu_list;
}

/***
 * Change Post type Name for CSS
 */
function soinnolab_get_post_type_css($post_type)
{
    switch ($post_type) {
        case 'research':
            $post_type = 'study';
            break;
        case 'events':
            $post_type = 'event';
            break;
        case 'user':
            $post_type = 'user';
            break;
        default:
            $post_type = 'lab';
            break;
    }
    return $post_type;
}

/***
 * 어드민 POST label 변경
 */
function change_post_menu_label()
{
    global $menu;
    global $submenu;
    $menu[5][0] = 'Lab소식';
    $submenu['edit.php'][5][0] = 'Lab소식';
    $submenu['edit.php'][10][0] = 'Lab소식 추가';
    echo '';
}

function change_post_object_label()
{
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'Lab소식';
    $labels->singular_name = 'Lab소식';
    $labels->add_new = 'Lab소식 추가';
    $labels->add_new_item = 'Lab소식 추가';
    $labels->edit_item = 'Lab소식 수정';
    $labels->new_item = 'Lab소식';
    $labels->view_item = 'Lab소식 보기';
    $labels->search_items = 'Lab소식 검색';
}

add_action('init', 'change_post_object_label');
add_action('admin_menu', 'change_post_menu_label');

/***
 * Url path 가져오기
 */
function soinnalab_fanal_url($url)
{
    $filter = explode('?', $url);
    $filter = explode('/', $filter[0]);
    return end($filter);
}

/***
 * Add Class to Active Menu Item
 */
function soinnolab_nav_class($classes, $item)
{
    if (in_array('current-menu-item', $classes)) {
        $classes[] = 'on ';
    }
    return $classes;
}

add_filter('nav_menu_css_class', 'soinnolab_nav_class', 10, 2);

/***
 * Comment Close to Post (Lab 소식)
 */
function soinnolab_comments_open($open, $post_id)
{
    $post = get_post($post_id);
    if ('page' == $post->post_type || 'post' == $post->post_type) $open = false;
    return $open;
}

add_filter('comments_open', 'soinnolab_comments_open', 10, 2);

/***
 * Get User Picture
 */
function get_user_pic($size = 'thumbnail', $user_id = '')
{
    $user_id = empty($user_id) ? get_current_user_id() : $user_id;
    if ($user_id) {
        $user_pic = get_field('user_pic', 'user_' . $user_id);
        if ($user_pic) {
            return $user_pic['sizes'][$size];
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/***
 * Get Current Url
 */
function get_current_url()
{
    return '//' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
}

/***
 * blockusers_init()
 */
function blockusers_init()
{
    if (is_admin() && !current_user_can('administrator') && !(defined('DOING_AJAX') && DOING_AJAX)) {
        wp_redirect(home_url());
        exit;
    }
}

add_action('init', 'blockusers_init');

function modify_post_thumbnail_html($html, $post_id, $post_thumbnail_id, $size, $attr)
{
    $id = get_post_thumbnail_id(); // gets the id of the current post_thumbnail (in the loop)
    $src = wp_get_attachment_image_src($id, $size); // gets the image url specific to the passed in size (aka. custom image size)
    $alt = get_the_title($id); // gets the post thumbnail title
    $class = $attr['class']; // gets classes passed to the post thumbnail, defined here for easier function access

    $src[0] = str_replace(' ', '%20', $src[0]);
    // Check to see if a 'retina' class exists in the array when calling "the_post_thumbnail()", if so output different <img/> html
    if (strpos($class, 'retina') !== false) {
        $html = '<img src="" alt="" data-src="' . $src[0] . '" data-alt="' . $alt . '" class="' . $class . '" />';
    } else {
        $html = '<img src="' . $src[0] . '" alt="' . $alt . '" class="' . $class . '" />';
    }

    return $html;
}

add_filter('post_thumbnail_html', 'modify_post_thumbnail_html', 99, 5);