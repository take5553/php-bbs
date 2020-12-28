<?php

use phpDocumentor\Reflection\Types\Boolean;
use PHPUnit\Framework\TestCase;

require('config/properties_for_test.php');
require('model/GetFormAction.php');

class GetFormActionTest extends TestCase
{
    /**
     * @dataProvider postDataProvider
     */
    public function testSaveDBPostData($data, $expected)
    {

        // 1. GetFormActionインスタンスを生成
        $action = new GetFormAction();

        // 2. 投稿
        $actual_postResult = $action->SaveDBPostData($data);

        // 3. アサーションメソッドで確認
        $this->assertEquals($expected, $actual_postResult);
        
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
        $actual_fetch = $stmt->fetch();

        // 5. アサーションメソッドで確認
        $this->assertEquals($expected, is_array($actual_fetch));
        if (is_array($actual_fetch) == true) {
            // $actual_fetchが配列なら記事が取得できているはず
            $this->assertEquals($data['name'], $actual_fetch['name']);
            $this->assertEquals($data['email'], $actual_fetch['email']);
            $this->assertEquals($data['post_body'], $actual_fetch['body']);
            $this->assertEquals($data['password'], $actual_fetch['password']);
        }
        
        // 6. 後片付け
        if (is_array($actual_fetch) == true) {
            $sql = "delete from posts where name = '$data[name]'";
            $stmt = $pdo->query($sql);
        }
        $pdo = null;
    }

    public function postDataProvider()
    {
        return [
            'Successful' => [[
                'name' => 'testpost',
                'email' => 'hoge@hoge.hoge',
                'post_body' => 'これはテストです',
                'password' => 'password'
            ],true],
            'withoutName' => [[
                'name' => '',
                'email' => 'hoge@hoge.hoge',
                'post_body' => 'これはテストです',
                'password' => 'password'
            ],false],
            'withoutEmail' => [[
                'name' => 'testpost',
                'email' => '',
                'post_body' => 'これはテストです',
                'password' => 'password'
            ],false],
            'withoutBody' => [[
                'name' => 'testpost',
                'email' => 'hoge@hoge.hoge',
                'post_body' => '',
                'password' => 'password'
            ],false],
            'withoutPassword' => [[
                'name' => 'testpost',
                'email' => 'hoge@hoge.hoge',
                'post_body' => 'これはテストです',
                'password' => ''
            ],false],
            'withLongName' => [[
                'name' => '12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901',
                'email' => 'hoge@hoge.hoge',
                'post_body' => 'これはテストです',
                'password' => 'password'
            ],false]
        ];
    }
}
