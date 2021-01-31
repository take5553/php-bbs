<?php

function h($str)
{
    return htmlentities($str, ENT_HTML5 | ENT_QUOTES, "UTF-8");
}

function GetParam()
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