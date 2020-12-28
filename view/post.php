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
                <input type="text" name="name" id="name">
            </p>
            <p>
                メールアドレス：<br>
                <input type="email" name="email" id="email">
            </p>
            <p>
                本文：<br>
                <textarea name="post_body" id="post_body" cols="30" rows="10"></textarea>
            </p>
            <p>
                パスワード：<br>
                <input type="password" name="password" id="password">
            </p>
            <p>
                <input type="hidden" name="eventId" value="save">
                <input type="submit" value="送信">
            </p>
        </form>
    </div>
    <!-- 記事入力エリア終了 -->

    <!-- 記事表示エリア -->
    <div class="posts">
        <?php if (!empty($post_data)) : ?>
        <div class="post">
            <?php foreach ($post_data as $post) :?>
            <div class="name">
                <p>名前：<a
                        href="mailto:<?php echo $post['email']; ?>"><?php echo $post['name']; ?></a>
                </p>
            </div>
            <div class="post_body">
                <p><?php echo $post['body']; ?>
                </p>
            </div>
            <div class="posted_at">
                <p><?php echo $post['posted_at']; ?>
                </p>
            </div>
            <?php if ($post['posted_at'] != $post['updated_at']) : ?>
            <p>
                <?php echo $post['updated_at']; ?>
            </p>
            <?php endif;?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <!-- 記事表示エリア終了 -->
</body>

</html>