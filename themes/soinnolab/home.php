<?php
/**
 * The home template file
 */

get_header(); ?>
<!-- 메인 컨텐츠 영역 -->
<div class="fc01" id="main-content">
    <div class="ib01">
        <?php soinnolab_get_slide(); ?>
        <form class="cf02" action="/" method="get">
            <fieldset>
                <div class="innerBox">
                    <p>일상의 혁신과 연구를 이어줍니다</p>
                    <label for="search_query_m">검색어</label>
                    <input type="text" name="s" id="search_query_m" placeholder="혁신을 검색하라"/>
                    <button>검색</button>
                </div>
            </fieldset>
        </form>
    </div>
    <div class="mc01 inner">
        <h2>인기 연구 모아보기</h2>
        <div class="ib02">
            <?php soinnolab_get_bestwrites(); ?>
        </div>
        <a class="more" href="/archives/research">더보기</a>
    </div>
    <div class="mc02">
        <div class="inner">
            <?php soinnolab_get_event(); ?>
            <?php soinnolab_get_research(); ?>
        </div>
    </div>
    <div class="mc03 inner">
        <h2>행사 소식을 공유하면 좋은 연구를 만난다</h2>
        <ul>
            <li class="l1">SNS로그인으로 간편하게<br/>회원가입과 로그인을 해주세요</li>
            <li class="l2">행사 소식을 구체적으로<br/>입력해 주세요</li>
            <li class="l3">관련연구,다른행사 소식을<br/>만날 수 있습니다</li>
        </ul>
    </div>
</div>
<!-- //메인 컨텐츠 영역 -->
<?php get_footer(); ?>
