<?php

function loadClasses($classe)
{
  require './classes/' . $classe . '.php';
}

spl_autoload_register('loadClasses');

$db = new Database();
$result = $db->query("SELECT * FROM instances");
