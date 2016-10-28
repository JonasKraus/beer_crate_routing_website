<?php
include('../php/cryptography.php');
include('../php/databaseConstants.php');

$progress = 1;
$name = 'jonas';

$test = cryptography::wrapProgress($progress, $name);
echo "<b>Link: </b>" . $test . "<br>";

$test = cryptography::unwrapProgress($test);
echo "<b>Progress: </b>" . $test["progress"] . "</br>";
echo "<b>Pseudonym: </b>" . $test["pseudonym"] . "</br>";





