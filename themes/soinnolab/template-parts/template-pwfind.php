<?php
/***
 * Template Name: 비밀번호찾기
 */
get_header() ?>
    <!-- 서브 영역 -->
    <div class="fc02 fc02_01" id="main-content">
        <div class="inner">
            <!-- 폼 박스 영역 -->
            <div class="cf06 passwordForm">
                <h1>비밀번호 찾기</h1>
                <div class="alert" style="display:none">비밀번호 재설정에 관한 링크가<br/>첨부된 이메일이 전송되었습니다</div>
                <a class="btn03" href="/login">로그인 화면으로 돌아가기</a>
                <form method="post" action="">
                    <h3>등록되어 있는 이메일주소를 입력해주세요</h3>
                    <p class="help">회원 가입 시 입력했던 이메일주소를 입력해주시면 해당 이메일주소로 비밀번호를 재설정하기 위한 URL을 전송합니다</p>
                    <p><label for="user_email">이메일</label><input type="text" id="user_email" name="user_email"
                                                                 placeholder="이메일"/></p>
                    <p class="submit">
                        <button id="find-pw-submit" class="btn02">비밀번호 재설정</button>
                    <p>
                </form>
                <p class="help">사회혁신리서치랩에 로그인 혹은 가입하시면 사회혁신리서치랩의 이용 약관, 개인정보 취급 방침과 사회혁신리서치랩 캠페인에 대해 가끔 이메일을 수신하는 데
                    동의하시는 것으로 간주합니다. (언제든지 구독을 취소하실 수 있습니다)</p>
            </div>
            <!-- //폼 박스 영역 -->
        </div>
    </div>
    <!-- //서브 영역 -->
<?php get_footer(); ?>