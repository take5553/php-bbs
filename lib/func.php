<?php

function h($str)
{
    return htmlentities($str, ENT_HTML5 | ENT_QUOTES, "UTF-8");
}
