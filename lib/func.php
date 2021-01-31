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

/**
 * https://www.softel.co.jp/blogs/tech/archives/58
 * ↑ここからコピー
 *
 * @param array $a
 * @param integer $c
 * @return int
 */
function array_depth($a, $c = 0)
{
    if (is_array($a) && count($a)) {
        ++$c;
        $_c = array($c);
        foreach ($a as $v) {
            if (is_array($v) && count($v)) {
                $_c[] = array_depth($v, $c);
            }
        }
        return max($_c);
    }
    return $c;
}
