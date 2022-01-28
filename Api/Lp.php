<?php

set_time_limit(0);
include 'Messages.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    if (getInfoByToken($token) == 2) {
        $reply = array(
            'result' => 'false',
            'text' => 'Invalid token'
        );
        echo json_encode($reply);
    } else {
        while (true) {
            $oldMessage = getMessages($token, 1);
            $oldHash = md5(json_encode($oldMessage));
            sleep(0.05);
            $newMessage = getMessages($token, 1);
            $newHash = md5(json_encode($newMessage));

            if ($newHash != $oldHash) {
                die(json_encode($newMessage));
            }
        }
    }
}
