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
        <div class="row mb-4">
            <h1>たけしのページの掲示板</h1>
        </div>
        <!-- 記事入力エリア -->
        <div class="row">
            <div class="input_area">
                <form action="./index.php" method="post" id="post_form" class="mb-5">
                    <div class="row mb-2 mb-sm-4">
                        <div class="col-sm-6 mb-2 mb-sm-0">
                            <label for="name">名前：</label>
                            <input type="text" class="form-control" name="name" id="name" value="<?php echo $repostFlag ? h($postDataFromUser['name']) : "";?>">
                        </div>
                        <div class="col-sm-6">
                            <label for="email">メールアドレス：</label>
                            <input type="email" class="form-control" name="email" id="email" value="<?php echo $repostFlag ? h($postDataFromUser['email']) : "";?>">
                        </div>
                    </div>
                    <div class="row mb-2 mb-sm-4">
                        <div class="col-12"><label for="body">本文：</label></div>
                        <div class="col-12"><textarea name="body" class="form-control" rows=10 id="body"><?php echo $repostFlag ? h($postDataFromUser['body']) : "";?></textarea></div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-12"><label for="password">パスワード：</label></div>
                        <div class="col-6"><input type="password" class="form-control" name="password" id="password"></div>
                        <input type="hidden" name="eventId" value="save">
                        <input type="hidden" name="token" value="<?php echo h(password_hash(session_id(), PASSWORD_DEFAULT)) ?>">
                    </div>
                    <div class="mb-4">
                        <button class="btn btn-primary" type="submit">送信</button>
                    </div>
                </form>
            </div>
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