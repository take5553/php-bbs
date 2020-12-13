<?php

/**
 * This is just a function that echos comment passed as a parameter.
 *
 * @param string $comment
 * @return void
 */
function hogehoge(string $comment)
{
    echo $comment;
    // TODO: Delete it!
    $comments = '';
    for ($i=0; $i < 5; $i++) {
        $comments = $comments . $comment;
        for ($j=0; $j < 5; $j++) {
            if ($j == 3) {
                // FIXME: Multibyte characters should not be used.
                $comments = $comments . 'さぁん！';
            } else {
                $comments = $comments . (string)$j;
            }
        }
    }
}
