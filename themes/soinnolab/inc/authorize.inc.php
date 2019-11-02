<?php
if (!is_user_logged_in()) {
    $url = get_current_url();
    $final = soinnalab_fanal_url($url);
    $authorized_files = array('events-write', 'research-write', 'mypage', 'mypage/scrap', 'mypage/write', 'mypage/commnet');
    if (in_array($final, $authorized_files)) {
        wp_redirect(home_url('/'), 301);
        exit;
    }
} else {
    $accepts = array('/login', '/register', '/pwfind');
    if (in_array($_SERVER['REQUEST_URI'], $accepts)) {
        wp_redirect(home_url('/'), 301);
        exit;
    }
}

if (isset($_GET['loggedout']) && $_GET['loggedout'] == 'true') {
    wp_redirect(home_url('/'));
    exit;
}
