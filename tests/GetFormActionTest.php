<?php

use PHPUnit\Framework\TestCase;

require('config/properties_for_test.php');
require('model/GetFormAction.php');

class GetFormActionTest extends TestCase
{
    public function testSaveDBPostDataSuccessful()
    {
        // 1. テスト記事データ作成
        $action = new GetFormAction();
        $testpost = array(
            'name' => 'testpost',
            'email' => 'hoge@hoge.hoge',
            'post_body' => 'これはテストです',
            'password' => 'password'
        );

        // 2. 投稿
        $action->SaveDBPostData($testpost);
        
        // 3. SQL文で直接記事を取得
        try {
            $pdo = new PDO(PDO_DSN, DATABASE_USER, DATABASE_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            print('Error:'.$e->getMessage());
            die();
        }
        $sql = "select * from posts where name = '$testpost[name]'";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch();

        // 4. アサーションメソッドで確認
        $this->assertEquals($testpost['name'], $result['name']);
        $this->assertEquals($testpost['email'], $result['email']);
        $this->assertEquals($testpost['post_body'], $result['body']);
        $this->assertEquals($testpost['password'], $result['password']);

        // 5. 今保存した記事を削除
        $sql = "delete from posts where name = '$testpost[name]'";
        $stmt = $pdo->query($sql);
        $pdo = null;
    }

    public function testSaveDBPostDataWithoutName()
    {
        $testpost = array(
            'name' => '',
            'email' => 'hoge@hoge.hoge',
            'post_body' => 'これはテストです',
            'password' => 'password'
        );

        $this->failSaveDBPostData($testpost);
    }

    public function testSaveDBPostDataWithoutEmail()
    {
        $testpost = array(
            'name' => 'testpost',
            'email' => '',
            'post_body' => 'これはテストです',
            'password' => 'password'
        );

        $this->failSaveDBPostData($testpost);
    }

    public function testSaveDBPostDataWithoutPostBody()
    {
        $testpost = array(
            'name' => 'testpost',
            'email' => 'hoge@hoge.hoge',
            'post_body' => '',
            'password' => 'password'
        );

        $this->failSaveDBPostData($testpost);
    }

    public function testSaveDBPostDataWithoutPassword()
    {
        $testpost = array(
            'name' => 'testpost',
            'email' => 'hoge@hoge.hoge',
            'post_body' => 'これはテストです',
            'password' => ''
        );

        $this->failSaveDBPostData($testpost);
    }

    public function testSaveDBPostDataWithLongName()
    {
        $testpost = array(
            'name' => '12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901',
            'email' => 'hoge@hoge.hoge',
            'post_body' => 'これはテストです',
            'password' => 'password'
        );

        $this->failSaveDBPostData($testpost);
    }

    private function failSaveDBPostData($data)
    {

        // 1. テスト記事データ作成
        $action = new GetFormAction();

        // 2. 投稿
        $postResult = $action->SaveDBPostData($data);

        // 3. アサーションメソッドで確認
        $this->assertEquals(false, $postResult);
        
        // 4. SQL文で直接記事を取得を試みる
        try {
            $pdo = new PDO(PDO_DSN, DATABASE_USER, DATABASE_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            print('Error:'.$e->getMessage());
            die();
        }
        if ($data['name'] == '') {
            $searchKey = $data['email'];
        } else {
            $searchKey = $data['name'];
        }
        $sql = "select * from posts where name = '$searchKey'";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch();

        // 5. アサーションメソッドで確認
        $this->assertEquals(false, $result);
        
        // 6. 後片付け
        $pdo = null;
    }
}
