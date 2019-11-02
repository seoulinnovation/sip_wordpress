<?php
/***
 * 검색시 리다이렉트
 */
if (isset($_GET['ps'])) {
    $ps = str_replace(' ', '+', $_GET['ps']);
    $refer = $_SERVER['HTTP_REFERER'];
    $filter = explode('&search=', $refer);
    $refer = $filter[0];
    if (strpos($refer, 'page')) {
        $refer = explode('/page', $refer);
        $refer = $refer[0];
    }
    wp_redirect($refer . '&search=' . $ps);
    exit;
}

if (isset($_GET['ls'])) {
    $ls = str_replace(' ', '+', $_GET['ls']);
    $refer = $_SERVER['HTTP_REFERER'];
    $filter = explode('?search=', $refer);
    $refer = $filter[0];
    if (strpos($refer, 'page')) {
        $refer = explode('/page', $refer);
        $refer = $refer[0];
    }
    wp_redirect($refer . '?search=' . $ls);
    exit;
}

/***
 * 태그 자동 완성 검색을 위한 스크립트
 */
add_action('wp_enqueue_scripts', 'se_wp_enqueue_scripts');
function se_wp_enqueue_scripts()
{
    wp_enqueue_script('suggest');
}

add_action('wp_head', 'se_wp_head');
function se_wp_head()
{
    ?>
    <script type="text/javascript">
        (function ($) {
            $(document).ready(function () {
                $('#form-tag').suggest("<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php?action=ajax-tag-search&tax=post_tag", {
                    multiple: true,
                    multipleSep: ","
                });
            });
        }(jQuery));
    </script>
    <?php
}

// autocompletion for non-logged-in users
add_action('wp_ajax_nopriv_ajax-tag-search', 'add_autosuggest_20links_callback');
// cribbed from admin-ajax.php
function add_autosuggest_20links_callback()
{
    if (!isset($_GET['tax'])) {
        wp_die(0);
    }

    $taxonomy = sanitize_key($_GET['tax']);
    $tax = get_taxonomy($taxonomy);
    if (!$tax) {
        wp_die(0);
    }

    $s = wp_unslash($_GET['q']);

    $comma = _x(',', 'tag delimiter');
    if (',' !== $comma)
        $s = str_replace($comma, ',', $s);
    if (false !== strpos($s, ',')) {
        $s = explode(',', $s);
        $s = $s[count($s) - 1];
    }
    $s = trim($s);

    /**
     * Filter the minimum number of characters required to fire a tag search via AJAX.
     *
     * @param int $characters The minimum number of characters required. Default 2.
     * @param object $tax The taxonomy object.
     * @param string $s The search term.
     * @since 4.0.0
     *
     */
    $term_search_min_chars = (int)apply_filters('term_search_min_chars', 2, $tax, $s);

    /*
     * Require $term_search_min_chars chars for matching (default: 2)
     * ensure it's a non-negative, non-zero integer.
     */
    if (($term_search_min_chars == 0) || (strlen($s) < $term_search_min_chars)) {
        wp_die();
    }

    $results = get_terms($taxonomy, array('name__like' => $s, 'fields' => 'names', 'hide_empty' => false));

    echo join($results, "\n");
    wp_die();
}

function soinnolab_write()
{
    $section = $_POST['section'];
    switch ($section) {
        case 'research':
            $title = trim($_POST['write_title']);
            $content = trim($_POST['write_content']);
            $tags = preg_replace("/\s+/", "", $_POST['write_tag']);
            $tags = explode(',', $tags);
            $tax = trim($_POST['write_category']);
            $write_thumb = $_FILES['write_thumb'];

            $args = array(
                'post_title' => $_POST['write_title'],
                'post_type' => 'research',
                'post_content' => $_POST['write_content'],
                'post_status' => 'publish',
                'comment_status' => 'open',
            );
            if (is_string($_POST['post_id'])) $args['ID'] = $_POST['post_id'];
            $post_ID = wp_insert_post($args);
            wp_set_object_terms($post_ID, $tax, 'research_cat', true);
            wp_set_object_terms($post_ID, $tags, 'post_tag', true);
            if ($write_thumb) Generate_Featured_Image($write_thumb, $post_ID);
            $return = $post_ID;
            break;
        case 'events':
            $title = trim($_POST['write_title']);
            $content = trim($_POST['write_content']);
            $tags = preg_replace("/\s+/", "", $_POST['write_tag']);
            $tags = explode(',', $tags);
            $tax = trim($_POST['write_category']);
            if (isset($_FILES)) $write_thumb = $_FILES['write_thumb'];
            else $write_thumb = '';
            $start_date = trim($_POST['start_date']);
            if ($start_date) $start_date = date('Ymd', strtotime($start_date));
            $finish_date = trim($_POST['finish_date']);
            if ($finish_date) $finish_date = date('Ymd', strtotime($finish_date));
            $address = trim($_POST['write_place']);
            $lat = trim($_POST['lat']);
            $lng = trim($_POST['lng']);
            $zoom = 17;
            $args = array(
                'post_title' => $_POST['write_title'],
                'post_type' => 'events',
                'post_content' => $_POST['write_content'],
                'post_status' => 'publish',
                'comment_status' => 'open',
            );
            if (is_string($_POST['post_id'])) $args['ID'] = $_POST['post_id'];
            $post_ID = wp_insert_post($args);
            wp_set_object_terms($post_ID, $tax, 'research_cat', true);
            wp_set_object_terms($post_ID, $tags, 'post_tag', true);
            if ($write_thumb) Generate_Featured_Image($write_thumb, $post_ID);
            update_field('field_5763ec4f62457', $start_date, $post_ID);
            update_field('field_5763ec8262458', $finish_date, $post_ID);
            // Google Map ACF
            $field_name = "field_5763cd97a1367";
            $value = array("address" => $address, "lat" => $lat, "lng" => $lng, "zoom" => $zoom);
            if ($address && $lat && $lng) update_field($field_name, $value, $post_ID);
            $return = $post_ID;
            break;
        case 'lab':
            $title = trim($_POST['write_title']);
            $content = trim($_POST['write_content']);
            $tax = trim($_POST['lab_category']);
            $write_thumb = $_FILES['write_thumb'];
            $category = get_term_by('slug', $tax, 'category');
            $cat_id = $category->term_id;
            $args = array(
                'post_title' => $_POST['write_title'],
                'post_type' => 'post',
                'post_content' => $_POST['write_content'],
                'post_status' => 'publish',
                'comment_status' => 'open',
                'post_category' => array($cat_id)
            );
            if (is_string($_POST['post_id'])) $args['ID'] = $_POST['post_id'];
            $post_ID = wp_insert_post($args);
            //wp_set_object_terms($post_ID, $tax, 'category', true);
            if ($write_thumb) Generate_Featured_Image($write_thumb, $post_ID);
            //국영문카테고리
            update_field('field_576421408972e', $_POST['lab_language'], $post_ID);
            $return = $post_ID;
            break;
        case 'thumbnail_delete':
            $attach_id = $_POST['attachid'];
            $return = wp_delete_attachment($attach_id, true);
            break;
    }
    echo json_encode($return);
    wp_die();
}

add_action('wp_ajax_soinnolab_write', 'soinnolab_write');

function Generate_Featured_Image($files, $post_id)
{
    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($files['tmp_name']);
    $filename = basename($files['name']);
    if (wp_mkdir_p($upload_dir['path'])) $file = $upload_dir['path'] . '/' . $filename;
    else                                    $file = $upload_dir['basedir'] . '/' . $filename;
    file_put_contents($file, $image_data);

    $wp_filetype = wp_check_filetype($filename, null);
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment($attachment, $file, $post_id);
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $file);
    $res1 = wp_update_attachment_metadata($attach_id, $attach_data);
    $res2 = set_post_thumbnail($post_id, $attach_id);
}