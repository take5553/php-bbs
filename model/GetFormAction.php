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
        // 渡されたデータが正当なものかどうか
        // TODO: ===を使って型までチェック
        if (($data['name'] == '') or ($data['email'] == '') or ($data['body'] == '') or ($data['password'] == '')) {
            return false;
        }
        if (((mb_strlen($data['name'])) > 100) or ((mb_strlen($data['email']) > 256)) or (mb_strlen($data['body']) > 5000) or (mb_strlen($data['password']) > 50) or (mb_strlen($data['password']) < 4)) {
            return false;
        }

        // 投稿された記事をDBに保存
        // TODO: HTML文字のエスケープが必要
        $smt = $this->pdo->prepare('insert into posts (name,email,body,password,posted_at,updated_at) values(:name,:email,:body,:password,now(),now())');
        $smt->bindParam(':name', $data['name'], PDO::PARAM_STR);
        $smt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $smt->bindParam(':body', $data['body'], PDO::PARAM_STR);
        $smt->bindParam(':password', $data['password'], PDO::PARAM_STR);
        return $smt->execute();
    }

    public function GetDBPostData()
    {
        $stm = $this->pdo->prepare('select * from posts where deleted_at is null order by posted_at DESC');
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function GetParam()
    {
        $ret = array(
            'mode' => '',
            'id' => ''
        );
        $params = explode('/', $_SERVER['REQUEST_URI'], 5);
        if ($params[1] != 'bbs') {
            return $ret;
        }
        if ($params[2] != 'edit') {
            return $ret;
        }
        if (! ctype_digit($params[3])) {
            return $ret;
        }

        $ret['mode'] = $params[2];
        $ret['id'] = (int)$params[3];

        return $ret;
    }

    public function GetDBOnePostData(int $postId)
    {
        $stm = $this->pdo->prepare('select * from posts where id = :id and deleted_at is null');
        $stm->bindParam(':id', $postId, PDO::PARAM_INT);
        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
    
        return $result;
    }

    public function UpdateDBPostData(array $data)
    {
        // 渡されたデータが正当なものかどうか
        // TODO: SaveDBPostDataとコードがほとんど被ってる
        if (($data['id'] == '') or ($data['name'] == '') or ($data['email'] == '') or ($data['body'] == '') or ($data['password'] == '')) {
            return false;
        }
        if ((! ctype_digit($data['id'])) or ((mb_strlen($data['name'])) > 100) or ((mb_strlen($data['email']) > 256)) or (mb_strlen($data['body']) > 5000) or (mb_strlen($data['password']) > 50) or (mb_strlen($data['password']) < 4)) {
            return false;
        }

        // パスワードを確認
        $old_data = $this->GetDBOnePostData((int)$data['id']);
        if ($data['password'] != $old_data['password']) {
            return false;
        }

        // 編集された記事をDBに保存
        $smt = $this->pdo->prepare('update posts set name=:name, email=:email, body=:body where id=:id');
        $smt->bindParam(':name', $data['name'], PDO::PARAM_STR);
        $smt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $smt->bindParam(':body', $data['body'], PDO::PARAM_STR);
        $id = (int)$data['id'];
        $smt->bindParam(':id', $id, PDO::PARAM_INT);
        return $smt->execute();
    }

    public function DeleteDBPostData(int $postId, string $password)
    {
        // TODO: 引数のバリデーションが出来ていない

        // パスワードを確認
        // TODO: UpdateDBPostDataとコードが被っている
        $target_data = $this->GetDBOnePostData($postId);
        if ($password != $target_data['password']) {
            return false;
        }

        $smt = $this->pdo->prepare('update posts set deleted_at=now() where id=:id');
        $smt->bindParam(':id', $postId, PDO::PARAM_INT);
        return $smt->execute();
    }
}
