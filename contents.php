<?php

function get(){
    $apiUrl = 'https://mamot.fr/api/v1/timelines/public';
    $json = file_get_contents($apiUrl);
    $result = json_decode($json);
    return $result;
}

$pages = get();
