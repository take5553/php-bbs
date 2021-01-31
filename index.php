<?php
// CSRF対策としてセッションIDを使う
session_start();

require_once('./config/properties.php');
require_once('./model/GetFormAction.php');
require_once('./lib/func.php');

// クリッキングジャック対策
header('X-Frame-Options: DENY');

$action = new GetFormAction();
$eventId = null;
$repostFlag = false;
$reupdateFlag = isset($_SESSION['reupdate_flag']) ? $_SESSION['reupdate_flag'] : false;

$postDataFromUser['id'] = filter_input(INPUT_POST, 'id');
$postDataFromUser['name'] = (string)filter_input(INPUT_POST, 'name');
$postDataFromUser['email'] = (string)filter_input(INPUT_POST, 'email');
$postDataFromUser['body'] = (string)filter_input(INPUT_POST, 'body');
$postDataFromUser['password'] = (string)filter_input(INPUT_POST, 'password');

$token = (string)filter_input(INPUT_POST, 'token');
$params = $action->GetParam();

// イベントIDを取得
// CSRF対策としてpost.phpおよびedit.phpに埋め込んだトークンが一致しなければアクションは起こさない
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eventId'])) {
    if (password_verify(session_id(), $token)) {
        $eventId = $_POST['eventId'];
    } else {
        $errmsg = "セッションエラー。";
        // もし投稿だったり更新だったりしたときに、内容が失われると可哀そうだからそれだけ救ってあげる
        if ($_POST['eventId'] === 'update' && ctype_digit($_POST['id'])) {

            // 必要な情報をセッションに持たせてリダイレクトする（パスワード以外）
            $_SESSION['name'] = $postDataFromUser['name'];
            $_SESSION['email'] = $postDataFromUser['email'];
            $_SESSION['body'] = $postDataFromUser['body'];
            $_SESSION['reupdate_flag'] = true;
            $_SESSION['errmsg'] = $errmsg . "再度更新してください。";

            $url = "/bbs/edit/" . $postDataFromUser['id'];
            header('Location: ' . $url, true, 302);
            exit;
        } elseif ($_POST['eventId'] === 'save') {
            $repostFlag = true;
            $errmsg .= "再度投稿してください。";
        }
    }
}

switch ($eventId) {
    case 'save':
        $saveResult = $action->SaveDBPostData($postDataFromUser);
        if ($saveResult == false) {
            $errmsg = "記事投稿に失敗しました。";
        }
        require('./view/post.php');
        break;
    case 'update':
        $updateResult = $action->UpdateDBPostData($postDataFromUser);
        if ($updateResult == false) {
            $errmsg = "記事編集に失敗しました。";
        }
        require('./view/post.php');
        break;
    case 'delete':
        $deleteResult = $action->DeleteDBPostData($postDataFromUser);
        if ($deleteResult == false) {
            $errmsg = "記事削除に失敗しました。";
        }
        require('./view/post.php');
        break;
    default:
        switch ($params['mode']) {
            case 'edit':
                $edit_data = $action->GetDBOnePostData($params['id']);
                if (is_array($edit_data)) {
                    // $edit_dataが配列かどうか確認してからセッション切れ救済の処理をしているのは、
                    // 渡された記事IDが、未削除の記事を指定していないか確認したいから。
                    if ($reupdateFlag) {
                        $edit_data['name'] = $_SESSION['name'];
                        $edit_data['email'] = $_SESSION['email'];
                        $edit_data['body'] = $_SESSION['body'];
                        $errmsg = $_SESSION['errmsg'];
                    }
                    require('./view/edit.php');
                } else {
                    require('./view/id_error.php');
                }
                break;
            default:
                require('./view/post.php');
                break;
        }
        break;
}
