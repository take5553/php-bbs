<?php

use phpDocumentor\Reflection\Types\Boolean;
use PHPUnit\Framework\TestCase;

require('config/properties_for_test.php');
require('model/GetFormAction.php');

class GetFormActionTest extends TestCase
{
    protected static $pdo;

    public static function setUpBeforeClass(): void
    {
        try {
            self::$pdo = new PDO(PDO_DSN, DATABASE_USER, DATABASE_PASSWORD);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            print('Error:'.$e->getMessage());
            die();
        }
    }

    public static function tearDownAfterClass(): void
    {
        self::$pdo = null;
    }

    /**
     * @dataProvider saveDataProvider
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
        if ($data['name'] == '') {
            $searchKey = $data['email'];
        } else {
            $searchKey = $data['name'];
        }
        $sql = "select * from posts where name = '$searchKey'";
        $stmt = self::$pdo->query($sql);
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
            $stmt = self::$pdo->query($sql);
        }
    }

    public function saveDataProvider()
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


    /**
     * @dataProvider getDataProvider
     */
    public function testGetDBPostData($data)
    {
        // 1. GetFormActionインスタンスを生成
        $action = new GetFormAction();

        // 2. SQL文で直接記事を登録
        $smt = self::$pdo->prepare('insert into posts (name,email,body,password,posted_at,updated_at) values(:name,:email,:body,:password,now(),now())');
        $smt->bindParam(':name', $data['name'], PDO::PARAM_STR);
        $smt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $smt->bindParam(':body', $data['post_body'], PDO::PARAM_STR);
        $smt->bindParam(':password', $data['password'], PDO::PARAM_STR);
        $smt->execute();

        // 3. GetDBPostDataで記事データ取得
        $actual_result = $action->GetDBPostData();
        $testPost = $actual_result[0];
        
        // 4. それぞれ確認
        $this->assertEquals($data['name'], $testPost['name']);
        $this->assertEquals($data['email'], $testPost['email']);
        $this->assertEquals($data['post_body'], $testPost['body']);
        $this->assertEquals($data['password'], $testPost['password']);

        // 5. 後片付け
        $sql = "delete from posts where name = '$data[name]'";
        $smt = self::$pdo->query($sql);
    }

    public function getDataProvider()
    {
        return [
            'successful' => [[
                'name' => 'gettest',
                'email' => 'get@get.get',
                'post_body' => 'gettest',
                'password' => 'getget'
            ]]
        ];
    }

    /**
     * @dataProvider getParamProvider
     */
    public function testGetParam($data, $expected)
    {
        // 1. GetFormActionインスタンスを生成
        $action = new GetFormAction();

        // 2. リクエストURIセット
        $_SERVER['REQUEST_URI'] = $data['uri'];

        // 3. GetParamでパラメーター取得
        $actual_result = $action->GetParam();

        // 4. 評価
        $this->assertEquals($expected['mode'], $actual_result['mode']);
        $this->assertEquals($expected['id'], $actual_result['id']);
    }

    public function getParamProvider()
    {
        return [
            'successful' => [[
                'uri' => '/bbs/edit/1234'
            ],[
                'mode' => 'edit',
                'id' => 1234
            ]],
            'successfulWithSlash' => [[
                'uri' => '/bbs/edit/5678/'
            ],[
                'mode' => 'edit',
                'id' => 5678
            ]],
            'successfulWithRedundant' => [[
                'uri' => '/bbs/edit/1122/hogehoge'
            ],[
                'mode' => 'edit',
                'id' => 1122
            ]],
            'wrongModePrameter' => [[
                'uri' => '/bbs/editable/5678'
            ],[
                'mode' => '',
                'id' => ''
            ]],
            'wrongOrder' => [[
                'uri' => '/bbs/2345/edit'
            ],[
                'mode' => '',
                'id' => ''
            ]],
            'wrongID' => [[
                'uri' => '/bbs/edit/123abc'
            ],[
                'mode' => '',
                'id' => ''
            ]]
        ];
    }

    public function testGetDBOnePostData()
    {
        // 1. GetFormActionインスタンスを生成
        $action = new GetFormAction();

        // 2. 投稿データを作成
        $data = array(
            'name' => 'OnePostData',
            'email' => 'test@test',
            'post_body' => 'testtest',
            'password' => 'hogehoge'
        );

        // 3. SQL文で直接記事を登録
        $smt = self::$pdo->prepare('insert into posts (name,email,body,password,posted_at,updated_at) values(:name,:email,:body,:password,now(),now())');
        $smt->bindParam(':name', $data['name'], PDO::PARAM_STR);
        $smt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $smt->bindParam(':body', $data['post_body'], PDO::PARAM_STR);
        $smt->bindParam(':password', $data['password'], PDO::PARAM_STR);
        $smt->execute();

        // 4. SQL文で直接記事を取得を試みる
        $sql = "select * from posts where name = '$data[name]' and email = '$data[email]' and body = '$data[post_body]'";
        $stmt = self::$pdo->query($sql);
        $actual_fetch = $stmt->fetch();

        // 5. GetDBOnePostDataで1レコードのみ取得
        $actual_result = $action->GetDBOnePostData($actual_fetch['id']);

        // 6. SQL文で取得したデータと比較
        $this->assertEquals($actual_fetch['name'], $actual_result['name']);
        $this->assertEquals($actual_fetch['email'], $actual_result['email']);
        $this->assertEquals($actual_fetch['body'], $actual_result['body']);
        $this->assertEquals($actual_fetch['password'], $actual_result['password']);

        // 7. 引数に空欄が渡されたら
        $failure = $action->GetDBOnePostData('');

        // 8. 失敗する
        $this->assertFalse($failure);

        // 9. testUpdateDBPostDataへ引き継ぎ
        return $actual_fetch;
    }

    /**
     * @depends testGetDBOnePostData
     */
    public function testUpdateDBPostData($originalPostData)
    {
        // 1. GetFormActionインスタンスを生成
        $action = new GetFormAction();

        // 2. 変更後のデータを準備
        $data = array(
            'id' => $originalPostData['id'],
            'name' => 'UpdatePostData',
            'email' => 'update@update',
            'post_body' => 'updateupdate',
            'password' => $originalPostData['password']
        );

        // 3. 更新
        $result = $action->UpdateDBPostData($data);
        $this->assertTrue($result);

        // 4. SQL文で直接記事を取得を試みる
        $sql = "select * from posts where id = $originalPostData[id]";
        $stmt = self::$pdo->query($sql);
        $actual_fetch = $stmt->fetch();

        // 5. 評価
        $this->assertEquals($data['name'], $actual_fetch['name']);
        $this->assertEquals($data['email'], $actual_fetch['email']);
        $this->assertEquals($data['post_body'], $actual_fetch['post_body']);
        $this->assertNotEquals($originalPostData['name'], $actual_fetch['name']);
        $this->assertNotEquals($originalPostData['email'], $actual_fetch['email']);
        $this->assertNotEquals($originalPostData['post_body'], $actual_fetch['post_body']);

        // 6. パスワードが違うと失敗する
        $wrongData = $originalPostData;
        $wrongData['password'] = "hugahuga";
        $result = $action->UpdateDBPostData($wrongData);
        $this->assertFalse($result);

        // 7. 記事データが更新されていないことを確認
        $sql = "select * from posts where id = $wrongData[id]";
        $stmt = self::$pdo->query($sql);
        $actual_fetch = $stmt->fetch();
        $this->assertEquals($data['name'], $actual_fetch['name']);
        $this->assertEquals($data['email'], $actual_fetch['email']);
        $this->assertEquals($data['post_body'], $actual_fetch['post_body']);
        $this->assertNotEquals($wrongData['name'], $actual_fetch['name']);
        $this->assertNotEquals($wrongData['email'], $actual_fetch['email']);
        $this->assertNotEquals($wrongData['post_body'], $actual_fetch['post_body']);

        // 8. 後片付け
        $sql = "delete from posts where where id = $wrongData[id]";
        $smt = self::$pdo->query($sql);
    }
}
