<?php
$posts = $action->GetDBPostData();
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
                <input type="text" name="name" id="name" value="<?php if ($repostFlag) {
    echo h($postDataFromUser['name']);
}?>">
            </p>
            <p>
                メールアドレス：<br>
                <input type="email" name="email" id="email" value="<?php if ($repostFlag) {
    echo h($postDataFromUser['email']);
}?>">
            </p>
            <p>
                本文：<br>
                <textarea name="body" id="body" cols="30" rows="10"><?php if ($repostFlag) {
    echo h($postDataFromUser['body']);
}?></textarea>
            </p>
            <p>
                パスワード：<br>
                <input type="password" name="password" id="password">
            </p>
            <p>
                <input type="hidden" name="eventId" value="save">
                <input type="hidden" name="token"
                    value="<?php echo h(password_hash(session_id(), PASSWORD_DEFAULT)) ?>">
                <input type="submit" value="送信">
            </p>
        </form>
    </div>
    <!-- 記事入力エリア終了 -->

    <!-- エラーメッセージ表示エリア -->
    <?php if (isset($errmsg)) :?>
    <div class="errormsg">
        <p><?php echo h($errmsg) ?>
        </p>
    </div>
    <?php endif; ?>
    <!-- エラーメッセージ終了 -->

    <!-- 記事表示エリア -->
    <div class="posts">
        <?php if ($posts->HavePosts()) : ?>
        <div class="post">
            <?php foreach ($posts as $post) :?>
            <div class="name">
                <p>名前：<a
                        href="mailto:<?php echo $post->TheEmail(); ?>"><?php echo $post->TheName(); ?></a>
                    <a
                        href="edit/<?php echo $post->TheId(); ?>">編集・削除</a>
                </p>
            </div>
            <div class="post_body">
                <p><?php echo nl2br(h($post->TheBody())); ?>
                </p>
            </div>
            <div class="posted_at">
                <p>投稿日時：<?php echo $post->ThePostedDate(); ?>
                </p>
            </div>
            <?php if ($post->IsUpdated()) : ?>
            <p>
                更新日時：<?php echo $post->TheUpdatedDate(); ?>
            </p>
            <?php endif;?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <!-- 記事表示エリア終了 -->
</body>

</html>