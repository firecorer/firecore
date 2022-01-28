<?php

include $_SERVER['DOCUMENT_ROOT'] . '/Api/Config.php';

function execQuery($query, $val)
{
    try {
        $config = getConfig();
        $db = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['database'], $config['user'], $config['password']);
        $stmt = $db->prepare($query);
        $stmt->execute($val);
        return $stmt;
    } catch (PDOException $ex) {
        echo '<pre>Check database log please</pre>' . '<br>';
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/database.log', $ex, FILE_APPEND);
    }
}
