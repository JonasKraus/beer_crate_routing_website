<?php
include('../php/cryptography.php');
include('../php/databaseConstants.php');

/*
$progress = 3;
$name = 'FW';
*/

$progress = $_POST['pr'];
$name = $_POST['ps'];

$link = cryptography::wrapProgress($progress, $name);


echo $link;

/*
echo "<b>Link: </b>" . $test . "<br>";

$test = cryptography::unwrapProgress($test);
echo "<b>Progress: </b>" . $test["progress"] . "</br>";
echo "<b>Pseudonym: </b>" . $test["pseudonym"] . "</br>";
*/





