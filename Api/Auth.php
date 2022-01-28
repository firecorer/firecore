<?php

include $_SERVER['DOCUMENT_ROOT'] . '/Api/Random.php';

function ifUserExists($username)
{
    $val = array(
        'username' => $username
    );

    $stmt = execQuery("SELECT username FROM `users` WHERE username = :username", $val);
    $result = $stmt->fetch();

    if ($result != null) {
        return true;
    } else {
        return false;
    }
}

function newUser($username, $password)
{
    if (mb_strlen($username, 'utf-8') <= 24) {
        if (ifUserExists($username) == false) {

            global $permitted_chars;

            $random_str = random_str($permitted_chars, 32);

            $val = array(
                'username' => $username,
                'password' => md5(sha1($password)),
                'token' => $random_str
            );

            $stmt = execQuery("INSERT INTO `users` VALUES (NULL, :username, :password, :token)", $val);

            return $val;
        }

        if (ifUserExists($username) == true) {
            return 2;
        }
    }

    if (mb_strlen($username, 'utf-8') > 24) {
        return 3;
    }
}

function removeUser($token)
{
    $val = array(
        'token' => $token
    );

    $stmt = execQuery("SELECT token FROM `users` WHERE token = :token", $val);

    $result = $stmt->fetch();

    if ($result != null) {
        $stmt = execQuery("DELETE FROM `users` WHERE token = :token", $val);
        $result = $stmt->fetch();
        return 1;
    }

    if ($result == null) {
        return 2;
    }
}

function logIn($username, $password)
{
    if (ifUserExists($username) == true) {
        $val = array(
            'username' => $username,
            'password' => md5(sha1($password))
        );

        $stmt = execQuery("SELECT token FROM `users` WHERE username = :username AND password = :password", $val);
        $result = $stmt->fetch();

        if ($result != null) {
            return $result;
        } else {
            return 3;
        }
    } else {
        return 2;
    }
}