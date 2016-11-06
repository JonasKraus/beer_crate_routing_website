<?php

include_once ('../php/cryptography.php');
include_once ('../php/databaseConstants.php');


$progress = 3;
$name = 'FW';

/*
$progress = $_POST['pr'];
$name = $_POST['ps'];
*/

$test = cryptography::wrapProgress($progress, $name);

$ary[] = "ASCII";
$ary[] = "JIS";
$ary[] = "EUC-JP";

//echo mb_detect_encoding($test, $ary);


echo "<b>Link: </b>" . $test . "<br>";
$test = cryptography::unwrapProgress($test);
echo "<b>Progress: </b>" . $test["progress"] . "</br>";
echo "<b>Pseudonym: </b>" . $test["pseudonym"] . "</br>";
