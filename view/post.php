<?php
$posts = $action->GetDBPostData();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>たけしのページの掲示板</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>

<body>
    <div class="container">
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
                    <input type="hidden" name="token" value="<?php echo h(password_hash(session_id(), PASSWORD_DEFAULT)) ?>">
                    <input type="submit" value="送信">
                </p>
            </form>
        </div>
        <!-- 記事入力エリア終了 -->

        <!-- エラーメッセージ表示エリア -->
        <?php if (isset($errmsg)) :?>
        <div class="errormsg">
            <p><?php echo h($errmsg) ?></p>
        </div>
        <?php endif; ?>
        <!-- エラーメッセージ終了 -->

        <!-- 記事表示エリア -->
        <div class="posts">
            <?php if ($posts->HavePosts()) : ?>
            <div class="post">
                <?php foreach ($posts as $post) :?>
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="name">
                            <div class="row justify-content-between mb-0">
                                <div class="col">
                                    名前：
                                <?php if ($post->TheEmail() !== "") : ?>
                                    <a href="mailto:<?php echo $post->TheEmail(); ?>">
                                <?php endif; ?>
                                    <?php echo $post->TheName(); ?></a>
                                </div>
                                <div class="col-auto">
                                    <a href="edit/<?php echo $post->TheId(); ?>">編集・削除</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="post_body">
                            <p class="mb-0">
                                <?php echo nl2br(h($post->TheBody())); ?>
                            </p>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm">
                                <div class="posted_at">
                                    <p class="mb-0">
                                        投稿日時：<?php echo $post->ThePostedDate(); ?>
                                    </p>
                                </div>
                            </div>
                            <?php if ($post->IsUpdated()) : ?>
                                <div class="col-sm">
                                    <p class="mb-0">
                                        更新日時：<?php echo $post->TheUpdatedDate(); ?>
                                    </p>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <!-- 記事表示エリア終了 -->
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous">
    </script>
</body>

</html>