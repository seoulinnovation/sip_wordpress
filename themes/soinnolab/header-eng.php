<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 */
setcookie('lang', 'eng', time() + 3600, COOKIEPATH, COOKIE_DOMAIN, false);
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no<?php if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')) echo ', target-densitydpi=medium-dpi'; ?>"/>
    <title><?php bloginfo('name'); ?></title>
    <?php if (is_singular() && pings_open(get_queried_object())) : ?>
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <?php endif; ?>
    <?php wp_head(); ?>
    <!--[if lte IE 9]>
    <link rel="stylesheet" type="text/css" href="//soinnolab.net/wp-content/themes/soinnolab/css/ie.css"/><![endif]-->
    <!--[if lt IE 9]>
    <script src="//soinnolab.net/wp-content/themes/soinnolab/js/html5.js"></script><![endif]-->
    <link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/favicon.ico" type="image/x-icon">
</head>
<body class="not-log-in">
<div id="skip-link">
    <a href="#main-content">본문으로 바로가기</a>
</div>
<div class="ng07">
    <div class="mArea">
        <div class="header">
            <div class="lg01"><a href="/about/eng"><em>Seoul Innovation Center</em><strong>Social Innovation Reseach
                        Lab</strong></a></div>
            <a class="btnClose" href="#close">메뉴 감추기</a>
        </div>
        <div class="menu">
            <ul class="ng01">
                <li class="translate"><a href="#" id="lang-ko" title="국문"><i class="xi-translate"><em>국문</em></i></a>
                </li>
            </ul>
            <menu>
                <li class="l3"><a href="/about/eng">Lab About</a></li>
            </menu>
        </div>
    </div>
</div>
<header>
    <div class="inner">
        <div class="lg01"><a href="/about/eng"><em>Seoul Innovation Center</em><strong>Social Innovation Reseach
                    Lab</strong></a></div>
        <a href="#mobile_menu" class="btn01">모바일 메뉴 열기</a>
        <nav>
            <menu>
                <li class="l3"><a href="/about/eng">Lab About</a></li>
            </menu>
            <ul class="ng01">
                <li class="translate"><a href="#" id="lang-ko" title="국문"><i class="xi-translate"><em>국문</em></i></a>
                </li>
            </ul>
        </nav>
    </div>
</header>