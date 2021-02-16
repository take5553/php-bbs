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

        <div class="row my-5">
            <h1>たけしのページの掲示板</h1>
        </div>
        <div class="row mb-3">
            <div class="col">
                <div class="bg-light py-2 h4 text-center border rounded">
                    記事編集
                </div>
            </div>
        </div>
        
        <!-- エラーメッセージ表示エリア -->
        <?php if (isset($errmsg)) :?>
        <div class="errormsg alert alert-danger mb-5" role="alert">
            <?php echo h($errmsg) ?>
        </div>
        <?php endif; ?>
        <!-- エラーメッセージ終了 -->
        <!-- 記事入力エリア -->
            <div class="row">
                <div class="input_area">
                    <form action="../index.php" method="post" id="post_form" class="mb-5">
                        <div class="row mb-2 mb-sm-4">
                            <div class="col-sm-6 mb-2 mb-sm-0">
                                <label for="name">名前：</label>
                                <input type="text" class="form-control" name="name" id="name" value="<?php echo $reupdateFlag ? h($_SESSION['name']) : $edit_data->TheName();?>">
                            </div>
                            <div class="col-sm-6">
                                <label for="email">メールアドレス：</label>
                                <input type="email" class="form-control" name="email" id="email" value="<?php echo $reupdateFlag ? h($_SESSION['email']) : $edit_data->TheEmail();?>">
                            </div>
                        </div>
                        <div class="row mb-2 mb-sm-4">
                            <div class="col-12"><label for="body">本文：</label></div>
                            <div class="col-12"><textarea name="body" class="form-control" rows=10 id="body"><?php echo h(preg_replace("/(\r\n|\n|\r)/", "\n", $reupdateFlag ? $_SESSION['body'] : $edit_data->TheBody()));?></textarea></div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-12"><label for="password">パスワード：</label></div>
                            <div class="col-6"><input type="password" class="form-control" name="password" id="password"></div>
                            <input type="hidden" name="id"
                                value="<?php echo h($edit_data->TheId()) ?>">
                            <input type="hidden" name="token"
                                value="<?php echo h(password_hash(session_id(), PASSWORD_DEFAULT)) ?>">
                        </div>
                        <div class="mb-5">
                            <button name="eventId" value="update" class="btn btn-primary mb-4">更新</button><br>
                        </div>
                        <button name="eventId" value="delete" class="btn btn-danger">削除</button>
                    </form>
                </div>
            </div>
        <!-- 記事入力エリア終了 -->
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous">
    </script>
</body>

</html>