<?php
/***
 * Template Name: 마이페이지 - 내 정보 수정
 */
get_header();
$current_user = wp_get_current_user();
$user_name = '';
$user_email = '';
$user_pic = '';
if ($current_user) {
    $user_name = $current_user->data->display_name;
    $user_email = $current_user->data->user_email;
    $user_pic = get_field('user_pic', 'user_' . $current_user->ID);
}
?>
    <!-- 서브 영역 -->
    <div class="fc02 user" id="main-content"><!-- 카테고리 코드 : study,event,lab,user -->
        <div class="th01">
            <dl class="inner">
                <dt>마이페이지</dt>
                <dd class="tabs">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'mypage',
                        'menu_class' => 'tab-menu',
                    ));
                    ?>
                </dd>
            </dl>
        </div>
        <div class="inner tc01">
            <!-- 컨텐츠 영역 -->
            <h1 class="th05">내 정보</h1>
            <form class="cf05 userInfoForm" method="post" action="/wp-admin/admin-ajax.php"
                  enctype="multipart/form-data">
                <input type="hidden" name="action" value="soinnolab_login"/>
                <input type="hidden" name="section" value="editmypage"/>
                <input type="hidden" name="user_id" value="<?php echo $current_user->ID; ?>"/>
                <table>
                    <tbody>
                    <tr class="name">
                        <th><label for="user_name">이름</label></th>
                        <td><input type="text" id="user_name" name="user_name" value="<?php echo $user_name; ?>"/></td>
                    </tr>
                    <tr class="pw">
                        <th>비밀번호</th>
                        <td><a class="btn03 pw-btn" id="pw-btn" href="#">비밀번호 수정하기</a></td>
                    </tr>
                    <tr class="pw-active pw01">
                        <th><label for="user_password">비밀번호</label></th>
                        <td><input type="password" id="user_password" name="user_password"/></td>
                    </tr>
                    <tr class="pw-active pw02">
                        <th><label for="user_password_again">비밀번호 확인</label></th>
                        <td><input type="password" id="user_password_again" name="user_password_again"/></td>
                    </tr>

                    <tr class="email">
                        <th><label for="user_email">이메일</label></th>
                        <td><input type="text" id="user_email" name="user_email" value="<?php echo $user_email; ?>"
                                   disabled="disabled"/><i>이메일은 변경하실 수 없습니다</i></td>
                    </tr>
                    <tr class="pic">
                        <th><label for="user_pic">프로필 이미지</label></th>
                        <td>
                            <?php if ($user_pic): ?>
                                <em><img id="preview_pic" src="<?php echo $user_pic['sizes']['thumbnail']; ?>"
                                         alt="<?php echo $user_name; ?>"/></em>
                                <input type="hidden" name="attachid" value="<?php echo $user_pic['ID']; ?>"/>
                                <a class="btn03 user_pic delete" href="#">삭제하기</a>
                            <?php else: ?>
                                <input type="file" accept=".jpg, .jpeg, .jpe, .gif, .png" id="user_pic"
                                       name="user_pic"/>
                                <i>프로필 이미지를 등록해주세요 (jpg,jpeg,jpe,gif,png 만 가능합니다)</i>
                                <a class="btn03 user_pic add" href="#">등록하기</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="fc04">
                    <button class="btn02">수정하기</button>
                </div>
            </form>
            <!-- //컨텐츠 영역 -->
        </div>
    </div>
    <!-- //서브 컨텐츠 영역 -->
<?php get_footer(); ?>