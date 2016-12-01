<?php
include ("php/databaseManager.php");

$db = new databaseManager();
$data = $db->getUsersProgress();


foreach ($dataArray as $key=>$record) {
    echo $key . " " . $record . " <br>";
}



