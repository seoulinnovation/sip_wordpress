<?php
add_action('wp_ajax_soinnolab_login', 'soinnolab_login');
add_action('wp_ajax_nopriv_soinnolab_login', 'soinnolab_login');
function soinnolab_login()
{
    global $wpdb;
    $section = $_POST['section'];
    switch ($section) {
        case 'login':                // 일반회원로그인
            $user_login = $_POST['user_login'];
            $user_pass = $_POST['user_pass'];
            $exists = email_exists($user_login);
            if (!$exists) {
                $return = "등록되지 않은 메일입니다. 회원가입을 해주세요.";
            } else {
                $user_info = get_user_by('email', $user_login);
                if (!wp_check_password($user_pass, $user_info->data->user_pass, $user_info->ID)) {
                    $return = "비밀번호가 일치하지 않습니다. 다시 확인해주세요.";
                } else {
                    $user_id = $user_info->ID;
                    $user_login = $user_info->data->user_login;
                    wp_set_current_user($user_id, $user_login);
                    wp_set_auth_cookie($user_id);
                    do_action('wp_login', $user_login);
                    $return = 'ok';
                }
            }
            break;
        case 'join':                // 회원가입
            $join_user_login = $_POST['join_user_login'];
            $join_user_mail = $_POST['join_user_mail'];
            $join_user_pass = $_POST['join_user_pass'];
            $name = explode("@", $join_user_mail);
            $exists = email_exists($join_user_mail);
            if ($exists) {
                $return = "이미 등록된 메일입니다. 로그인을 해주세요";
            } else {
                $check = $wpdb->get_var("SELECT ID FROM wp_users WHERE user_login like '$name[0]'");
                if ($check) {
                    $name[0] = 'user_' . $name[0];
                }
                //회원 등록
                $userdata = array(
                    'user_login' => $name[0],
                    'user_email' => $join_user_mail,
                    'display_name' => $join_user_login,
                    'user_pass' => $join_user_pass,  // When creating an user, `user_pass` is expected.
                    'show_admin_bar_front' => 'false'
                );
                $user_id = wp_insert_user($userdata);

                $user_info = get_user_by('email', $join_user_mail);
                $user_login = $user_info->date->user_login;
                wp_set_current_user($user_id, $user_login);
                wp_set_auth_cookie($user_id);
                do_action('wp_login', $user_login);
                $return = 'ok';
            }
            break;
        case 'findpw':                // 비밀번호찾기
            $pw_user_mail = $_POST['pw_user_mail'];
            $exists = email_exists($pw_user_mail);
            if (!$exists) {
                $return = "등록되지 않은 메일입니다. 회원가입을 해주세요.";
            } else {
                $new_pw = RandomString(8); //임시비밀번호 생성 커스텀함수
                $hashedPassword = wp_hash_password($new_pw); //암호화
                $user_info = get_user_by('email', $pw_user_mail);
                $user_id = $user_info->ID;
                $wpdb->update('wp_users', array('user_pass' => $hashedPassword), array('ID' => $user_id));

                //신청한 사람에게
                $to = $pw_user_mail;
                $admin = "webmaster@soinnolab.net";
                $subject = "[서울혁신리서치랩]임시비밀번호 입니다.";
                $message = "<table cellspacing='0' cellpadding='0' style='margin: 0 auto;padding: 0;border: 0;max-width: 700px;font-family:Dotum ,Sans-serif;background-color: #f6f6f6;width: 100%;-webkit-text-size-adjust: 100%;vertical-align: top;'><tr style='margin: 0;padding: 0;border: 0;font-family:Dotum ,Sans-serif;-webkit-text-size-adjust: 100%;vertical-align: top;'><td style='margin: 0;padding: 0;border: 0;font-family:Dotum ,Sans-serif;-webkit-text-size-adjust: 100%;vertical-align: top; width:6%;'></td><td style='margin: 0;padding:0;border: 0;font-family:Dotum ,Sans-serif;-webkit-text-size-adjust: 100%;vertical-align: top; width:88%;text-align:center'><table cellspacing='0' cellpadding='0' style='margin: 0 auto;padding: 0;border: 0;font-family:Dotum ,Sans-serif;background-color: #f6f6f6;width: 100%;-webkit-text-size-adjust: 100%;vertical-align: top;'><tr style='margin: 0;padding: 0;border: 0;font-family:Dotum ,Sans-serif;-webkit-text-size-adjust: 100%;vertical-align: top;'><td style='margin: 0;padding:70px 0 30px;border: 0;font-family:Dotum ,Sans-serif;-webkit-text-size-adjust: 100%;vertical-align: top;text-align:center'><img src='http://http://soinnolab.net//wp-content/themes/soinnolab/images/brand.png' alt='soinnolab'></td></tr><tr style='margin: 0;padding: 0;border: 0;font-family:Dotum ,Sans-serif;-webkit-text-size-adjust: 100%;vertical-align: top;'><td style='margin: 0;border: 0;font-family:Dotum ,Sans-serif;-webkit-text-size-adjust: 100%;vertical-align: top;background-color:#fff;'><table cellspacing='0' cellpadding='0' style='margin: 0 auto;padding: 0;border: 0;font-family:Dotum ,Sans-serif;width: 100%;-webkit-text-size-adjust: 100%;vertical-align: top;'><tr style='margin: 0;padding: 0;border: 0;font-family:Dotum ,Sans-serif;-webkit-text-size-adjust: 100%;vertical-align: top;text-align:left;'><td style='margin: 0;border: 0;font-family:Dotum ,Sans-serif;-webkit-text-size-adjust: 100%;vertical-align: top;text-align:center; width:30px;'></td><td style='margin: 0;border: 0;font-family:Dotum ,Sans-serif;-webkit-text-size-adjust: 100%;vertical-align: top;text-align:left'><p style='font-size: 14px;line-height: 23px; color:#444;padding: 0;margin: 0;font-family:Malgun Gothic,Dotum,Sans-serif;-webkit-text-size-adjust:100%;vertical-align: top'><br>안녕하세요. {$user_info->data->display_name}님<br>고객님의 임시비밀번호를 전달드립니다.</p><br><p style='font-size: 14px;line-height: 23px; color:#1d4280;padding: 0;margin: 0;font-family:Malgun Gothic,Dotum,Sans-serif;-webkit-text-size-adjust:100%;vertical-align: top'>고객님의 임시비밀번호는 <strong stlye='font-weight:bold;font-size: 16px;line-height: 23px; color:#1d4280;padding: 0;margin: 0;font-family:Malgun Gothic,Dotum,Sans-serif;-webkit-text-size-adjust:100%;vertical-align: top'>{$new_pw}</strong>입니다.<br>로그인하시고 수정해주세요.</p><br><h2 style='font-size: 13px;line-height: 20px; color:#eb6943;padding: 0;margin: 0;font-family:Malgun Gothic,Dotum,Sans-serif;-webkit-text-size-adjust:100%;vertical-align: top;font-weight:bold'>참고하세요!</h2><p class='small' style='font-size: 13px;line-height: 20px; color:#444;padding: 0;margin: 0;font-family:Malgun Gothic,Dotum,Sans-serif;-webkit-text-size-adjust:100%;vertical-align: top'>비밀번호는 로그인 > 마이페이지 >회원정보수정 에서 수정하실 수 있습니다.<br>안전한 서비스 이용을 위해서 비밀번호는 정기적으로 변경해주는 것이 좋습니다.</p><br></td><td style='margin: 0;border: 0;font-family:Dotum ,Sans-serif;-webkit-text-size-adjust: 100%;vertical-align: top;text-align:center; width:30px;'></td></tr></table></td></tr><tr style='margin: 0;padding: 0;border: 0;font-family:Dotum ,Sans-serif;-webkit-text-size-adjust: 100%;vertical-align: top;'><td style='margin: 0;padding:20px 0 70px;border: 0;font-family:Dotum ,Sans-serif;-webkit-text-size-adjust: 100%;vertical-align: top;text-align:center;font-size:12px;color:#888;line-height:1'>본 메일은 발신 전용입니다. 궁금한 사항을 문의하려면 I MAKE 00 문의사항 을 이용해 주시기 바랍니다.</td></tr></table></td><td style='margin: 0;padding: 0;border: 0;font-family:Dotum ,Sans-serif;-webkit-text-size-adjust: 100%;vertical-align: top; width:6%;'></td></tr></table>";
                $headers = "From: 서울혁신리서치랩 <{$admin}>" . "\r\n";
                add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));
                wp_mail($to, $subject, $message, $headers);
                $return = 'ok';
            }
            break;
        case 'editmypage':            // 내정보수정하기
            if (!empty($_POST['user_password'])) { //비밀번호도 수정했을때
                $display_name = $_POST['user_name'];
                $edited_pw = $_POST['user_password'];
                $hashedPassword = wp_hash_password($edited_pw);
                $login_user_id = $_POST['user_id'];
                $wpdb->update('wp_users', array('display_name' => $display_name), array('ID' => $login_user_id));
                wp_set_password($edited_pw, $login_user_id);
                $user_info = get_user_by('id', $login_user_id);
                $user_email = $user_info->data->user_email;
                $user_login = $user_info->date->user_login;
                wp_set_current_user($login_user_id, $user_login);
                wp_set_auth_cookie($login_user_id);
                do_action('wp_login', $user_login);
                $return = 'ok';
            } else {
                $display_name = $_POST['user_name'];
                $login_user_id = $_POST['user_id'];
                $wpdb->update('wp_users', array('display_name' => $display_name), array('ID' => $login_user_id));
                $return = 'ok';
            }
            break;
        case 'deletecomments':        // 댓글 삭제하기
            $comment_id = $_POST['comment_id'];
            wp_delete_comment($comment_id, false);
            $return = 'ok';
            break;
        case 'edituserpic':
            register_upload_user_pic($_FILES);
            $return = $_FILES;
            break;
        case 'deleteuserpic':
            $attach_id = $_POST['attachid'];
            $return = wp_delete_attachment($attach_id, true);
            break;
        case 'deletepost':
            $post_id = $_POST['post_id'];
            //wp_delete_post($post_id);
            wp_trash_post($post_id);
            $return = 'ok';
            break;
    }
    echo json_encode($return);
    wp_die();
}

function register_upload_user_pic($files)
{
    // Move file to media library
    $movefile = wp_handle_upload($files['user_pic'], array('test_form' => false));

    // If move was successful, insert WordPress attachment
    if ($movefile && !isset($movefile['error'])) {
        $wp_upload_dir = wp_upload_dir();
        $attachment = array(
            'guid' => $wp_upload_dir['url'] . '/' . basename($movefile['file']),
            'post_mime_type' => $movefile['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', basename($movefile['file'])),
            'post_content' => '',
            'post_status' => 'inherit'
        );
        $attach_id = wp_insert_attachment($attachment, $movefile['file']);
        $user_id = get_current_user_id();
        update_field('field_57625ea39e33f', $attach_id, 'user_' . $user_id);
    }
}