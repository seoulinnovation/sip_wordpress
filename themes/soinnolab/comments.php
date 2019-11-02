<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
    return;
}
?>

<div class="rep01">
    <?php
    if (get_current_user_id() != 0) :
        comment_form(array(
            'class_form' => 'regReplyForm',
            'label_submit' => '등록',
            'logged_in_as' => '',
            'title_reply' => '',
            'class_submit' => 'btn btn01 btn-md',
            'comment_notes_after' => '',
            'comment_field' => '<p class="con"><label for="reply_content">댓글내용</label><textarea id="reply_content comment" name="comment" placeholder="댓글입력" required="required"></textarea></p>'
        ));
    else:
        ?>
        <form method="post" class="regReplyForm" action="/wp-comments-post.php" id="commentform" novalidate>
            <p class="con"><label for="reply_content">댓글내용</label><textarea id="reply_content comment" name="comment"
                                                                            placeholder="댓글입력"
                                                                            required="required"></textarea></p>
            <p class="chk"><input type="checkbox" id="reply_agree" name="reply_agree"/><label for="reply_agree">댓글 입력시
                    로그인 혹은 가입하게 되어 사회혁신리서치랩의 이용 약관과 개인정보 취급방침에 동의하시는 것으로 간주합니다. <br/>또한 사회혁신리서치랩의 이메일 수신하는 데 동의합니다.
                    (언제든지 구독을 취소하실 수 있습니다.)</label></p>
            <p class="inf">
                <label for="reply_email">이메일</label><input type="text" id="reply_email" name="email"
                                                           placeholder="이메일 입력"/>
                <label for="reply_pw">패스워드</label><input type="text" id="reply_pw" name="author" placeholder="비밀번호 입력"/>
                <button>등록</button>
            </p>
        </form>
    <?php endif; ?>
    <?php if (have_comments()) : ?>
        <div class="replies">
            <?php $comments_number = get_comments_number(); ?>
            <h3>댓글<em>(<?php echo $comments_number; ?>)</em></h3>
            <ul>
                <?php $comments = get_comments(array('post_id' => get_the_ID(), 'orderby' => 'comment_date', 'order' => 'descs')); ?>
                <?php foreach ($comments as $comment): ?>
                    <li>
                        <span class="thumb"><?php if (get_user_pic('thumbnail', $comment->user_id)): ?><img
                                src="<?php echo get_user_pic('thumbnail', $comment->user_id); ?>"
                                alt="사용자 이미지"><?php endif; ?></span>
                        <dl>
                            <dt><?php echo $comment->comment_author; ?></dt>
                            <dd class="sum"><?php echo nl2br($comment->comment_content); ?></dd>
                            <dd class="date"><?php echo $comment->comment_date; ?></dd>
                        </dl>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php the_comments_navigation(); ?>

</div><!-- .comments-area -->
