<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>たけしのページの掲示板</title>
</head>

<body>
    <h1>たけしのページの掲示板</h1>
    <h2>記事編集</h2>
    <!-- エラーメッセージ表示エリア -->
    <?php if (isset($errmsg)) :?>
    <div class="errormsg">
        <p><?php echo h($errmsg) ?>
        </p>
    </div>
    <?php endif; ?>
    <!-- エラーメッセージ終了 -->
    <!-- 記事入力エリア -->
    <div class="input_area">
        <form action="../index.php" method="post" id="post_form">
            <p>
                名前：<br>
                <input type="text" name="name" id="name"
                    value="<?php echo h($edit_data['name']) ?>">
            </p>
            <p>
                メールアドレス：<br>
                <input type="email" name="email" id="email"
                    value="<?php echo h($edit_data['email']) ?>">
            </p>
            <p>
                本文：<br>
                <textarea name="body" id="body" cols="30"
                    rows="10"><?php echo h(preg_replace("/(\r\n|\n|\r)/", "\n", $edit_data['body'])) ?></textarea>
            </p>
            <p>
                パスワード：<br>
                <input type="password" name="password" id="password">
            </p>
            <p>
                <input type="hidden" name="id"
                    value="<?php echo h($edit_data['id']) ?>">
                <input type="hidden" name="token"
                    value="<?php echo h(password_hash(session_id(), PASSWORD_DEFAULT)) ?>">
                <button name="eventId" value="update">更新</button><br><br>
                <button name="eventId" value="delete">削除</button>
            </p>
        </form>
    </div>
    <!-- 記事入力エリア終了 -->
</body>

</html>