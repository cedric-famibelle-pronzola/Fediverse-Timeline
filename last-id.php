<?php

require_once('./connect.php');

$request = file_get_contents('php://input');

echo $db->lastInsert();
