<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 */
?>
<footer>
    <div class="inner">
        <div class="rightA">
            <?php do_shortcode('[soinnolab-newsletter]'); ?>
        </div>
        <div class="leftA">
            <h2>서울혁신센터 사회혁신리서치랩</h2>
            <address>
                <span>서울시 은평구 통일로 684(녹번동 5번지) 서울혁신파크 1동(미래청) 2층 서울혁신센터 사무실 안</span>
                <span>Tel : 02-6365-6815 / Email : <a href="mailto:soinnolab@gmail.com" title="관리자에게 메일쓰기">soinnolab@gmail.com</a></span>
            </address>
        </div>
    </div>
    <div class="body-loading display-none">로딩중</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
