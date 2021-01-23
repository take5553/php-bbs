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
        if ($this->IsDataIncorrect($data)) {
            return false;
        }

        // 投稿データのエスケープ
        foreach ($data as $key => $value) {
            $escapedData[$key] = htmlentities($value, ENT_HTML5 | ENT_QUOTES, "UTF-8");
        }

        // パスワードのハッシュ化
        $escapedData['password'] = password_hash($escapedData['password'], PASSWORD_DEFAULT);
        if ($escapedData['password'] === false) {
            return false;
        }

        // 投稿された記事をDBに保存
        $smt = $this->pdo->prepare('insert into posts (name,email,body,password,posted_at,updated_at) values(:name,:email,:body,:password,now(),now())');
        $smt->bindParam(':name', $escapedData['name'], PDO::PARAM_STR);
        $smt->bindParam(':email', $escapedData['email'], PDO::PARAM_STR);
        $smt->bindParam(':body', $escapedData['body'], PDO::PARAM_STR);
        $smt->bindParam(':password', $escapedData['password'], PDO::PARAM_STR);
        return $smt->execute();
    }

    private function IsDataIncorrect($data)
    {
        // idのチェック
        if (isset($data['id'])) {
            if ($data['id'] === '' or (! ctype_digit($data['id']))) {
                return true;
            }
        }

        // nameのチェック
        if ($data['name'] === '' or ((mb_strlen($data['name'])) > 100)) {
            return true;
        }

        // emailのチェック
        if ($data['email'] === '' or ((mb_strlen($data['email']) > 256))) {
            return true;
        }

        // bodyのチェック
        if ($data['body'] === '' or (mb_strlen($data['body']) > 5000)) {
            return true;
        }

        // passwordのチェック
        if ($data['password'] === '' or (mb_strlen($data['password']) > 50) or (mb_strlen($data['password']) < 4)) {
            return true;
        }

        return false;
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
        if ($this->IsDataIncorrect($data)) {
            return false;
        }

        // 投稿データのエスケープ
        foreach ($data as $key => $value) {
            $escapedData[$key] = htmlentities($value, ENT_HTML5 | ENT_QUOTES, "UTF-8");
        }

        // パスワードを確認
        $old_data = $this->GetDBOnePostData((int)$data['id']);
        if (! password_verify($escapedData['password'], $old_data['password'])) {
            return false;
        }

        // 編集された記事をDBに保存
        $smt = $this->pdo->prepare('update posts set name=:name, email=:email, body=:body where id=:id');
        $smt->bindParam(':name', $escapedData['name'], PDO::PARAM_STR);
        $smt->bindParam(':email', $escapedData['email'], PDO::PARAM_STR);
        $smt->bindParam(':body', $escapedData['body'], PDO::PARAM_STR);
        $id = (int)$escapedData['id'];
        $smt->bindParam(':id', $id, PDO::PARAM_INT);
        return $smt->execute();
    }

    public function DeleteDBPostData($data)
    {
        // 渡されたデータが正当なものかどうか
        if ($this->IsDataIncorrect($data)) {
            return false;
        }

        // 投稿データのエスケープ
        foreach ($data as $key => $value) {
            $escapedData[$key] = htmlentities($value, ENT_HTML5 | ENT_QUOTES, "UTF-8");
        }

        // パスワードを確認
        $old_data = $this->GetDBOnePostData((int)$data['id']);
        if (! password_verify($escapedData['password'], $old_data['password'])) {
            return false;
        }

        $smt = $this->pdo->prepare('update posts set deleted_at=now() where id=:id');
        $postId = (int)$data['id'];
        $smt->bindParam(':id', $postId, PDO::PARAM_INT);
        return $smt->execute();
    }
}
