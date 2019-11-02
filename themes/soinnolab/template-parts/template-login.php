<?php
/***
 * Template Name: 로그인
 */
get_header() ?>
    <!-- 서브 영역 -->
    <div class="fc02 fc02_01" id="main-content">
        <div class="inner">
            <!-- 폼 박스 영역 -->
            <div class="cf06 loginForm">
                <div class="topA">
                    <h1>로그인</h1>
                    <ul class="sns">
                        <li class="fb"><?php do_shortcode('[soinnolab-facebook-login-btn]'); ?></li>
                        <li class="nv"><?php do_shortcode('[naverlogin]'); ?></li>
                        <li class="gg"><?php do_shortcode('[soinnolab-google-login-btn]'); ?></li>
                    </ul>
                </div>
                <div class="br"><em>또는</em></div>
                <div class="bottomA">
                    <form method="post" action="" name="loginform">
                        <input type="hidden" name="refer" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">
                        <h3>간단 로그인</h3>
                        <p><label for="user_email">이메일</label><input type="text" id="user_email" name="user_email"
                                                                     placeholder="이메일"/></p>
                        <p><label for="user_pw">이름</label><input type="password" id="user_pw" name="user_pw"
                                                                 placeholder="비밀번호"/>
                        <p>
                        <p class="submit">
                            <button id="wp-submit" class="btn02">로그인</button>
                            <!-- <a href="/pwfind">비밀번호를 잊으셨나요?</a> -->
                        <p>
                        <div class="row">
                            <a href="/pwfind" class="btn03">비밀번호를 잊으셨나요?</a>
                            <a href="/register" class="btn03">간단한 회원가입</a>
                        </div>
                    </form>
                    <p class="help">사회혁신리서치랩에 로그인 혹은 가입하시면 사회혁신리서치랩의 이용 약관, 개인정보 취급 방침과 사회혁신리서치랩 캠페인에 대해 가끔 이메일을 수신하는 데
                        동의하시는 것으로 간주합니다. (언제든지 구독을 취소하실 수 있습니다)</p>
                </div>
            </div>
            <!-- //폼 박스 영역 -->
        </div>
    </div>
    <!-- //서브 영역 -->
<?php get_footer(); ?>