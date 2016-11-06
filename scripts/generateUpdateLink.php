<?php
include('../php/cryptography.php');
include('../php/databaseConstants.php');

/*
$progress = 5;
$name = 'jonas';
*/

$progress = $_POST['pr'];
$name = $_POST['ps'];

var_dump($progress);var_dump($name);die;

$test = cryptography::wrapProgress($progress, $name);

echo $test;

/*
echo "<b>Link: </b>" . $test . "<br>";

$test = cryptography::unwrapProgress($test);
echo "<b>Progress: </b>" . $test["progress"] . "</br>";
echo "<b>Pseudonym: </b>" . $test["pseudonym"] . "</br>";
*/





