<?php

function get(){
  $url = 'https://mamot.fr/api/v1/timelines/public?local=true';
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json'
    ]);
  $response = json_decode(curl_exec($curl));
  curl_close($curl);
  return $response;
}

$pages = get();
