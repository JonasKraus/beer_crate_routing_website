<?php
include ("php/databaseManager.php");

//echo "<script>date = new Date();date.setTime(date.getTime()+(7*24*60*60*1000));document.cookie = \"res=\" + window.innerWidth + \"x\" + window.innerHeight + \";expires=\" + date.toGMTString();</script>";

$db = new databaseManager();
$data = $db->getProgressData();

echo $data;


