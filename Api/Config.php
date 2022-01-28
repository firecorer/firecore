<?php

function getConfig ()
{
    $configJson = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/config.json');
    return json_decode($configJson, true);
}
