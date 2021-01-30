<?php
$post_data = $action->GetDBPostData();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>たけしのページの掲示板</title>
</head>

<body>
    <h1>たけしのページの掲示板</h1>
    <!-- 記事入力エリア -->
    <div class="input_area">
        <form action="./index.php" method="post" id="post_form">
            <p>
                名前：<br>
                <input type="text" name="name" id="name" value="<?php if ($repost_flag) {
    echo htmlentities($filtered_post_data['name'], ENT_HTML5 | ENT_QUOTES, "UTF-8");
}?>">
            </p>
            <p>
                メールアドレス：<br>
                <input type="email" name="email" id="email" value="<?php if ($repost_flag) {
    echo htmlentities($filtered_post_data['email'], ENT_HTML5 | ENT_QUOTES, "UTF-8");
}?>">
            </p>
            <p>
                本文：<br>
                <textarea name="body" id="body" cols="30" rows="10"><?php if ($repost_flag) {
    echo htmlentities($filtered_post_data['body'], ENT_HTML5 | ENT_QUOTES, "UTF-8");
}?></textarea>
            </p>
            <p>
                パスワード：<br>
                <input type="password" name="password" id="password">
            </p>
            <p>
                <input type="hidden" name="eventId" value="save">
                <input type="hidden" name="token"
                    value="<?php echo htmlentities(password_hash(session_id(), PASSWORD_DEFAULT), ENT_HTML5 | ENT_QUOTES, "UTF-8") ?>">
                <input type="submit" value="送信">
            </p>
        </form>
    </div>
    <!-- 記事入力エリア終了 -->

    <!-- エラーメッセージ表示エリア -->
    <?php if (isset($saveResult) && $saveResult == false) :?>
    <div class="errormsg">
        <p>記事投稿に失敗しました。</p>
    </div>
    <?php elseif (isset($updateResult) && $updateResult == false) :?>
    <div class="errormsg">
        <p>記事編集に失敗しました。</p>
    </div>
    <?php elseif (isset($errmsg)) :?>
    <div class="errormsg">
        <p><?php echo htmlentities($errmsg, ENT_HTML5 | ENT_QUOTES, "UTF-8") ?>
        </p>
    </div>
    <?php endif; ?>
    <!-- エラーメッセージ終了 -->

    <!-- 記事表示エリア -->
    <div class="posts">
        <?php if (!empty($post_data)) : ?>
        <div class="post">
            <?php foreach ($post_data as $post) :?>
            <div class="name">
                <p>名前：<a
                        href="mailto:<?php echo htmlentities($post['email'], ENT_HTML5 | ENT_QUOTES, "UTF-8"); ?>"><?php echo htmlentities($post['name'], ENT_HTML5 | ENT_QUOTES, "UTF-8"); ?></a>
                    <a
                        href="edit/<?php echo htmlentities($post['id'], ENT_HTML5 | ENT_QUOTES, "UTF-8") ?>">編集・削除</a>
                </p>
            </div>
            <div class="post_body">
                <p><?php echo htmlentities($post['body'], ENT_HTML5 | ENT_QUOTES, "UTF-8"); ?>
                </p>
            </div>
            <div class="posted_at">
                <p>投稿日時：<?php echo htmlentities($post['posted_at'], ENT_HTML5 | ENT_QUOTES, "UTF-8"); ?>
                </p>
            </div>
            <?php if ($post['posted_at'] != $post['updated_at']) : ?>
            <p>
                更新日時：<?php echo htmlentities($post['updated_at'], ENT_HTML5 | ENT_QUOTES, "UTF-8"); ?>
            </p>
            <?php endif;?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <!-- 記事表示エリア終了 -->
</body>

</html>