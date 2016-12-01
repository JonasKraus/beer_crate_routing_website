<?php
include ("php/databaseManager.php");

$db = new databaseManager();
$data = $db->getUsersProgress();

$dataArray = json_decode($data);

foreach ($dataArray as $key=>$record) {
    echo $key . " " . $record . " <br>";
}



