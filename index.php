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
        $action->SaveDBPostData($_POST);
        require('./view/post.php');
        break;
    
    default:
        require('./view/post.php');
        break;
}
