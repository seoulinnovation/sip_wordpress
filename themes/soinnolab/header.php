<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 */
setcookie('lang', 'ko', time() + 3600, COOKIEPATH, COOKIE_DOMAIN, false);
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no<?php if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')) echo ', target-densitydpi=medium-dpi'; ?>"/>
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
<body <?php body_class('not-log-in'); ?>>
<div id="skip-link">
    <a href="#main-content">본문으로 바로가기</a>
</div>
<div class="ng07">
    <div class="mArea">
        <div class="header">
            <div class="lg01"><a href="/"><em>서울혁신센터</em><strong>사회혁신리서치랩</strong></a></div>
            <a class="btnClose" href="#close">메뉴 감추기</a>
        </div>
        <div class="menu">
            <ul class="ng01">
                <?php if (get_current_user_id() > 0): ?>
                    <?php if (get_user_pic('thumbnail')): ?>
                        <li class="mypage">
                            <a href="/mypage" title="마이페이지"><i class="thumb"><img
                                            src="<?php echo get_user_pic('thumbnail'); ?>" alt="홍길동 사진"/></i></a>
                            <!-- 프로필 사진 있는 경우 -->
                            <ul>
                                <li><a href="/mypage">마이페이지</a></li>
                                <li><a href="<?php echo wp_logout_url(get_current_url()); ?>">로그아웃</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="mypage">
                            <a href="/mypage" title="마이페이지"><i class="xi-user"><em>마이페이지</em></i></a>
                            <!-- 프로필 사진 없는 경우 -->
                            <ul>
                                <li><a href="/mypage">마이페이지</a></li>
                                <li><a href="<?php echo wp_logout_url(get_current_url()); ?>">로그아웃</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <li class="user"><a href="/login" title="로그인"><i class="xi-user"><em>로그인</em></i></a></li>
                <?php endif; ?>
                <li class="translate"><a href="#" id="lang-eng" title="영문"><i class="xi-translate"><em>영문</em></i></a>
                </li>
            </ul>
            <form class="cf01" action="/" method="get">
                <label for="search_query">검색어</label>
                <input type="text" name="s" id="search_query" placeholder="혁신을 검색하라"/>
                <a class="openSearch" href="#"><i class="xi-search"><em>검색</em></i></a>
            </form>
            <?php soinnolab_menu('primary'); ?>
        </div>
    </div>
</div>
<header>
    <div class="inner">
        <div class="lg01"><a href="/"><em>서울혁신센터</em><strong>사회혁신리서치랩</strong></a></div>
        <a href="#mobile_menu" class="btn01">모바일 메뉴 열기</a>
        <nav>
            <?php soinnolab_menu('primary'); ?>
            <form class="cf01" action="/" method="get">
                <label for="search_query">검색어</label>
                <input type="text" name="s" id="search_query" placeholder="혁신을 검색하라"/>
                <a class="openSearch" href="#"><i class="xi-search"><em>검색</em></i></a>
            </form>
            <ul class="ng01">
                <?php if (get_current_user_id() > 0): ?>
                    <?php if (get_user_pic('thumbnail')): ?>
                        <li class="mypage">
                            <a href="/mypage" title="마이페이지"><i class="thumb"><img
                                            src="<?php echo get_user_pic('thumbnail'); ?>" alt="홍길동 사진"/></i></a>
                            <!-- 프로필 사진 있는 경우 -->
                            <ul>
                                <li><a href="/mypage">마이페이지</a></li>
                                <li><a href="<?php echo wp_logout_url(get_current_url()); ?>">로그아웃</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="mypage">
                            <a href="/mypage" title="마이페이지"><i class="xi-user"><em>마이페이지</em></i></a>
                            <!-- 프로필 사진 없는 경우 -->
                            <ul>
                                <li><a href="/mypage">마이페이지</a></li>
                                <li><a href="<?php echo wp_logout_url(get_current_url()); ?>">로그아웃</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <li class="user"><a href="/login" title="로그인"><i class="xi-user"><em>로그인</em></i></a></li>
                <?php endif; ?>
                <li class="translate"><a href="#" id="lang-eng" title="영문"><i class="xi-translate"><em>영문</em></i></a>
                </li>
            </ul>
        </nav>
    </div>
</header>