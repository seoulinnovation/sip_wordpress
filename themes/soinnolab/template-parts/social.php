<ul>
    <li><a href="#" title="공유하기"><i class="xi-share-alt"></i></a>
        <ul>
            <li><a href="#" onclick="sendSns('facebook', 'http:<?php echo get_current_url(); ?>');">페이스북</a></li>
            <li><a href="#"
                   onclick="sendSns('twitter', 'http:<?php echo get_current_url(); ?>', '<?php the_title() ?>');">트위터</a>
            </li>
            <li>
                <!--a href="#" onclick="sendSns('kakaotalk', '<?php echo get_current_url(); ?>', '<?php the_title() ?>');">카카오톡</a-->
                <a href="javascript:" id="kakao-link-btn">카카오톡</a>
            </li>
        </ul>
    </li>
    <li><?php do_shortcode('[scrap-bookmark]'); ?></li>
    <li><a href="#" title="인쇄하기"><i class="xi-print"></i></a></li>
</ul>