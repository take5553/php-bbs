<?php
class Validator
{
    public function IsDataIncorrect($data)
    {
        // idのチェック
        if (isset($data['id'])) {
            if ($data['id'] === '' or (! ctype_digit($data['id']))) {
                return true;
            }
        }

        // nameのチェック
        if ($data['name'] === '' or ((mb_strlen($data['name'])) > 100)) {
            return true;
        }

        // emailのチェック
        if ($data['email'] === '' or ((mb_strlen($data['email']) > 256))) {
            return true;
        }

        // bodyのチェック
        if ($data['body'] === '' or (mb_strlen($data['body']) > 5000)) {
            return true;
        }

        // passwordのチェック
        if ($data['password'] === '' or (mb_strlen($data['password']) > 50) or (mb_strlen($data['password']) < 4)) {
            return true;
        }

        return false;
    }
}
