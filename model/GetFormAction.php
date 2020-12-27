<?php
class GetFormAction
{
    private $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO(PDO_DSN, DATABASE_USER, DATABASE_PASSWORD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            exit($e->getMessage());
        }
    }

    public function SaveDBPostData($data)
    {
        // 投稿された記事をDBに保存
        $smt = $this->pdo->prepare('insert into posts (name,email,body,password,posted_at,updated_at) values(:name,:email,:body,:password,now(),now())');
        if (($data['name'] == '') or ($data['email'] == '') or ($data['post_body'] == '') or ($data['password'] == '')) {
            return false;
        }
        if (((mb_strlen($data['name'])) > 100) or ((mb_strlen($data['email']) > 256)) or (mb_strlen($data['post_body']) > 5000) or (mb_strlen($data['password']) > 50) or (mb_strlen($data['password']) < 4)) {
            return false;
        }
        $smt->bindParam(':name', $data['name'], PDO::PARAM_STR);
        $smt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $smt->bindParam(':body', $data['post_body'], PDO::PARAM_STR);
        $smt->bindParam(':password', $data['password'], PDO::PARAM_STR);
        return $smt->execute();
    }
}
