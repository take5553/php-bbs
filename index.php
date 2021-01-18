<?php
require_once('./config/properties.php');
require_once('./model/GetFormAction.php');

$action = new GetFormAction();

$eventId = null;

// イベントIDを取得

if (isset($_POST['eventId'])) {
    $eventId = $_POST['eventId'];
}

switch ($eventId) {
    case 'save':
        $saveResult = $action->SaveDBPostData($_POST);
        require('./view/post.php');
        break;
    case 'update':
        $updateResult = $action->UpdateDBPostData($_POST);
        require('./view/post.php');
        break;
    case 'delete':
        $deleteResult = $action->DeleteDBPostData((int)$_POST['id'], $_POST['password']);
        require('./view/post.php');
        break;
    default:
        $params = $action->GetParam();
        switch ($params['mode']) {
            case 'edit':
                $edit_data = $action->GetDBOnePostData($params['id']);
                if (is_array($edit_data)) {
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
