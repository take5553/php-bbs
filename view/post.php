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
                <textarea name="post_body" id="post_body" cols="30" rows="10">本文を入力してください。</textarea>
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
        <div class="post">
            <div class="auther">
                <p>投稿者　表示場所</p>
            </div>
            <div class="post_timestamp">
                <p>投稿日時　表示場所</p>
            </div>
            <div class="post_body">
                <p>記事本文　表示場所</p>
            </div>
        </div>
        <div class="post">
            <div class="auther">
                <p>投稿者　表示場所</p>
            </div>
            <div class="post_timestamp">
                <p>投稿日時　表示場所</p>
            </div>
            <div class="post_body">
                <p>記事本文　表示場所</p>
            </div>
        </div>
    </div>
    <!-- 記事表示エリア終了 -->
</body>

</html>