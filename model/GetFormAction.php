<?php
require_once('Validator.php');
require_once('Post.php');
require_once('Posts.php');
class GetFormAction
{
    private $pdo;
    private $validator;

    public function __construct()
    {
        $this->validator = new Validator();

        try {
            $this->pdo = new PDO(PDO_DSN, DATABASE_USER, DATABASE_PASSWORD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            exit($e->getMessage());
        }
    }

    public function SaveDBPostData($postData)
    {
        // 渡されたデータが正当なものかどうか
        if ($this->validator->IsDataIncorrect($postData)) {
            return false;
        }

        // パスワードのハッシュ化
        $postData['password'] = password_hash($postData['password'], PASSWORD_DEFAULT);
        if ($postData['password'] === false) {
            return false;
        }

        // 投稿された記事をDBに保存
        $smt = $this->pdo->prepare('insert into posts (name,email,body,password,posted_at,updated_at) values(:name,:email,:body,:password,now(),now())');
        $smt->bindParam(':name', $postData['name'], PDO::PARAM_STR);
        $smt->bindParam(':email', $postData['email'], PDO::PARAM_STR);
        $smt->bindParam(':body', $postData['body'], PDO::PARAM_STR);
        $smt->bindParam(':password', $postData['password'], PDO::PARAM_STR);
        return $smt->execute();
    }

    public function GetDBPostData()
    {
        $stm = $this->pdo->prepare('select * from posts where deleted_at is null order by posted_at DESC');
        $stm->execute();
        $results = $stm->fetchAll(PDO::FETCH_ASSOC);

        return new Posts($results);
    }

    public function GetDBOnePostData(int $postId)
    {
        $stm = $this->pdo->prepare('select * from posts where id = :id and deleted_at is null');
        $stm->bindParam(':id', $postId, PDO::PARAM_INT);
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
    
        return is_array($result) ? new Post($result) : false;
    }

    public function UpdateDBPostData(array $postData)
    {
        // 渡されたデータが正当なものかどうか
        if ($this->validator->IsDataIncorrect($postData)) {
            return false;
        }

        // パスワードを確認
        $old_data = $this->GetDBOnePostData((int)$postData['id']);
        if (! $old_data->password_verify($postData['password'])) {
            return false;
        }

        // 編集された記事をDBに保存
        $smt = $this->pdo->prepare('update posts set name=:name, email=:email, body=:body where id=:id');
        $smt->bindParam(':name', $postData['name'], PDO::PARAM_STR);
        $smt->bindParam(':email', $postData['email'], PDO::PARAM_STR);
        $smt->bindParam(':body', $postData['body'], PDO::PARAM_STR);
        $id = (int)$postData['id'];
        $smt->bindParam(':id', $id, PDO::PARAM_INT);
        return $smt->execute();
    }

    public function DeleteDBPostData($postData)
    {
        // 渡されたデータが正当なものかどうか
        if ($this->validator->IsDataIncorrect($postData)) {
            return false;
        }

        // パスワードを確認
        $old_data = $this->GetDBOnePostData((int)$postData['id']);
        // もし削除済みの記事を再度削除しようとしたとき（削除後トップページでリロードしてしまった時など）falseが返ってくる対策
        if ($old_data === false) {
            return false;
        }
        if (! $old_data->password_verify($postData['password'])) {
            return false;
        }

        $smt = $this->pdo->prepare('update posts set deleted_at=now() where id=:id');
        $postId = (int)$postData['id'];
        $smt->bindParam(':id', $postId, PDO::PARAM_INT);
        return $smt->execute();
    }
}
