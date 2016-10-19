<?php
include('cryptography.php');
include ('consts.php');

$progress = 5;
$name = 'zwei';

$test = cryptography::wrapProgress($progress, $name);
echo "<b>Link: </b>" . $test . "<br>";

$test = cryptography::unwrapProgress($test);
echo "<b>Progress: </b>" . $test["progress"] . "</br>";
echo "<b>Pseudonym: </b>" . $test["pseudonym"] . "</br>";





