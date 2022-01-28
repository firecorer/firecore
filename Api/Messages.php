<?php

include $_SERVER['DOCUMENT_ROOT'] . '/Api/Database.php';

function getInfoByToken($token)
{
    $val = array(
        'token' => $token,
    );

    $stmt = execQuery("SELECT * FROM `users` WHERE token = :token", $val);

    $result = $stmt->fetch();

    if ($result != null) {
        return $result;
    } else {
        return 2;
    }
}

function addMessage($token, $text)
{
    if (mb_strlen($text) <= 2048 && !empty(trim($text))) {
        $arrayInfo = getInfoByToken($token);

        if ($arrayInfo == 2) {
            return 2;
        } else {
            $username = $arrayInfo['username'];

            $val = array(
                'username' => $username,
                'text' => strip_tags($text, '<br>')
            );

            $stmt = execQuery("INSERT INTO `messages` VALUES (NULL, :username, :text)", $val);
            return $val;
        }
    } else {
        return 3;
    }
}

function getMessages($token, $count)
{
    if ($count <= 2000) {
        $arrayInfo = getInfoByToken($token);

        if ($arrayInfo != 2) {
            $config = getConfig();

            $db = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['database'], $config['user'], $config['password']);
            $stmt = $db->prepare("SELECT * FROM messages ORDER BY id DESC LIMIT :lastmsgs");
            $stmt->bindParam(":lastmsgs", $count, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return 2;
        }
    } else {
        return 3;
    }
}
