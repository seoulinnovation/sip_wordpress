<?php
/**
 * Enqueues scripts and styles.
 *
 * @since soinnolab 1.0
 */
function soinnolab_scripts()
{
    global $wp_query;
    wp_enqueue_style('jquery-confirm', get_template_directory_uri() . '/js/craftpip-jquery-confirm/css/jquery-confirm.css', array());
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'));
    wp_enqueue_script('jquery-confirm', get_template_directory_uri() . '/js/craftpip-jquery-confirm/js/jquery-confirm.js', array('bootstrap', 'jquery'));
    wp_enqueue_script('bxslider', get_template_directory_uri() . '/js/jquery.bxslider.min.js', array('jquery'));
    wp_enqueue_script('validate', get_template_directory_uri() . '/js/jquery.validate.min.js', array('jquery'));
    wp_enqueue_script('form', get_template_directory_uri() . '/js/jquery.form.js', array('jquery'));
    wp_enqueue_script('countTo', get_template_directory_uri() . '/js/jquery.countTo.js', array('jquery'));
    wp_enqueue_script('hoverIntent', get_template_directory_uri() . '/js/jquery.hoverIntent.js', array('jquery'));
    wp_enqueue_script('isotope', get_template_directory_uri() . '/js/isotope.pkgd.min.js', array('jquery'));
    wp_enqueue_script('sirl', get_template_directory_uri() . '/js/sirl.js', array('jquery'));
    wp_enqueue_style('style', get_template_directory_uri() . '/style.css', array());
    wp_enqueue_style('sirl', get_template_directory_uri() . '/css/sirl.css', array());
    wp_enqueue_style('jquery.bxslider', get_template_directory_uri() . '/css/jquery.bxslider.css', array());
    wp_enqueue_style('responsive', get_template_directory_uri() . '/css/responsive.css', array());
    wp_enqueue_style('xeicon', 'http://cdn.jsdelivr.net/xeicon/2/xeicon.min.css', array());
    if (is_single()) {
        wp_enqueue_script('kakao', 'https://developers.kakao.com/sdk/js/kakao.min.js', array('jquery'));
        wp_enqueue_script('social', get_template_directory_uri() . '/js/social.js', array('jquery'));
        if ($wp_query->query['post_type'] == 'events') {
            wp_enqueue_script('googlemap', 'https://maps.googleapis.com/maps/api/js?sensor=false&region=KR&v=3.exp&sensor=false', array('jquery'));
            wp_enqueue_script('events', get_template_directory_uri() . '/js/events.js', array('jquery'));
            wp_enqueue_style('events', get_template_directory_uri() . '/css/events.css', array());
        }
    }
    if (isset($wp_query->query['pagename'])) {
        $pagename = $wp_query->query['pagename'];
        $acceptnames = array('login', 'pwfind', 'register', 'mypage/comment', 'mypage', 'mypage/write');
        if (in_array($pagename, $acceptnames)) {
            wp_enqueue_script('login', get_template_directory_uri() . '/js/login.js', array('jquery', 'jquery-confirm'));
        }
        $writepage = array('research-write', 'events-write', 'lab-write');
        if (in_array($pagename, $writepage)) {
            wp_enqueue_script('write', get_template_directory_uri() . '/js/write.js', array('jquery', 'jquery-confirm'));
            wp_enqueue_style('tinyeditor-button', get_template_directory_uri() . '/css/tinyeditor.css', array());
        }
    }
    wp_enqueue_style('print', get_template_directory_uri() . '/css/print.css', array());
}

add_action('wp_enqueue_scripts', 'soinnolab_scripts');

function meta_checkbox_to_radio()
{
    echo '
    <script>
        ( function( $ ) {
            $(document).ready(function(){
                $("#research_catchecklist li").each(function(){
                    $(this).find("input").attr("type","radio");
                });
            })
        } )( jQuery );
    </script>';
}

add_action('admin_head', 'meta_checkbox_to_radio');