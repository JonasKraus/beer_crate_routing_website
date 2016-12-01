<?php
include ("php/databaseManager.php");

$db = new databaseManager();
$data = $db->getUsersProgress();



