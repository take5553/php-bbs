<?php
$post_data = $action->GetDBOnePostData($params['id']);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>たけしのページの掲示板</title>
</head>

<body>
    <h1>たけしのページの掲示板</h1>
    <h2>記事編集</h2>
    <!-- 記事入力エリア -->
    <div class="input_area">
        <form action="../index.php" method="post" id="post_form">
            <p>
                名前：<br>
                <input type="text" name="name" id="name"
                    value="<?php echo $post_data['name'] ?>">
            </p>
            <p>
                メールアドレス：<br>
                <input type="email" name="email" id="email"
                    value="<?php echo $post_data['email'] ?>">
            </p>
            <p>
                本文：<br>
                <textarea name="body" id="body" cols="30"
                    rows="10"><?php echo $post_data['body'] ?></textarea>
            </p>
            <p>
                パスワード：<br>
                <input type="password" name="password" id="password">
            </p>
            <p>
                <input type="hidden" name="eventId" value="update">
                <input type="hidden" name="id"
                    value="<?php echo $post_data['id'] ?>">
                <input type="submit" value="送信">
            </p>
        </form>
    </div>
    <!-- 記事入力エリア終了 -->
</body>

</html>