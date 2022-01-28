<?php

include $_SERVER['DOCUMENT_ROOT'] . '/Api/Auth.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Api/Messages.php';

if (isset($_GET['method'])) {
    $a = $_GET['method'];
    switch ($a) {
        case 'auth':
            if (isset($_GET['submethod'])) {
                if ($_GET['submethod'] == 'log_in' && isset($_GET['username']) && isset($_GET['password'])) {
                    $username = $_GET['username'];
                    $password = $_GET['password'];
                    $result = logIn($username, $password);

                    if (isset($result['token'])) {
                        $reply = array(
                            'result' => 'true',
                            'text' => 'The login was completed',
                            'token' => $result['token']
                        );
                        echo json_encode($reply);
                    }

                    if ($result == 2) {
                        $reply = array(
                            'result' => 'false',
                            'text' => 'There is no user with this username'
                        );
                        echo json_encode($reply);
                    }

                    if ($result == 3) {
                        $reply = array(
                            'result' => 'false',
                            'text' => 'Incorrect password'
                        );
                        echo json_encode($reply);
                    }
                }

                if ($_GET['submethod'] == 'create_account' && isset($_GET['username']) && isset($_GET['password'])) {
                    $username = $_GET['username'];
                    $password = $_GET['password'];

                    $result = newUser($username, $password);

                    if (isset($result['token'])) {
                        $reply = array(
                            'result' => 'true',
                            'text' => 'A new account has been created',
                            'username' => $result['username'],
                            'password' => $result['password'],
                            'token' => $result['token']
                        );
                        echo json_encode($reply);
                    }

                    if ($result == 2) {
                        $reply = array(
                            'result' => 'false',
                            'text' => 'The username is already taken'
                        );
                        echo json_encode($reply);
                    }

                    if ($result == 3) {
                        $reply = array(
                            'result' => 'false',
                            'text' => 'The username cannot be longer than 24 characters'
                        );
                        echo json_encode($reply);
                    }
                }

                if ($_GET['submethod'] == 'remove_account' && isset($_GET['token'])) {
                    $token = $_GET['token'];
                    $result = removeUser($token);

                    if ($result == 1) {
                        $reply = array(
                            'result' => 'true',
                            'text' => 'Sorry, I do not know you'
                        );
                        echo json_encode($reply);
                    }

                    if ($result == 2) {
                        $reply = array(
                            'result' => 'false',
                            'text' => 'Invalid token'
                        );
                        echo json_encode($reply);
                    }
                }
            }
            break;
        case 'messages':
            if (isset($_GET['submethod'])) {
                if ($_GET['submethod'] == 'send_message' && isset($_GET['token']) && isset($_GET['text'])) {
                    $token = $_GET['token'];
                    $text = $_GET['text'];

                    $result = addMessage($token, $text);

                    if ($result == 2) {
                        $reply = array(
                            'result' => 'false',
                            'text' => 'Account information not found'
                        );
                        echo json_encode($reply);
                    }

                    if ($result == 3) {
                        $reply = array(
                            'result' => 'false',
                            'text' => 'The message length is more than 2048 characters or contains only spaces'
                        );
                        echo json_encode($reply);
                    }

                    if (isset($result['username']) && isset($result['text'])) {
                        $reply = array(
                            'result' => 'true',
                            'text' => 'The message has been sent'
                        );
                        echo json_encode($reply);
                    }
                }

                if ($_GET['submethod'] == 'get_messages' && isset($_GET['token']) && isset($_GET['count'])) {
                    $token = $_GET['token'];
                    $count = $_GET['count'];

                    $result = getMessages($token, $count);

                    if ($result == 3) {
                        $reply = array(
                            'result' => 'false',
                            'text' => 'You cannot get more than 2000 messages at a time'
                        );
                        echo json_encode($reply);
                    }

                    if ($result == 2) {
                        $reply = array(
                            'result' => 'false',
                            'text' => 'Invalid token'
                        );
                        echo json_encode($reply);
                    }

                    if ($result != 3 && $result != 2) {
                        $reply = array(
                            'messages' => json_encode($result)
                        );
                        echo json_encode($reply);
                    }
                }
            }
            break;
        default:
            break;
    }
}