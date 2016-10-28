<?php

include("../php/databaseManager.php");
include("../php/cryptography.php");

$responseStatus = '200 OK';
$responseText = '';


$progress = $_GET['pgr'];
$getData = cryptography::unwrapProgress($progress);
$progress = $getData["progress"];
$pseudonym = $getData["pseudonym"];


if (isset($_COOKIE["beercrate_routing_pseudonym"]) && $pseudonym != $_COOKIE["beercrate_routing_pseudonym"]) {
    header("Location: http://localhost:63343/dijkstra-studie/error.html");
    exit();
}

setcookie("beercrate_routing_pseudonym", $pseudonym, time() + (86400 * 7), ";path=/dijkstra-studie"); // Cookie for 7 days

try {

    $database = new databaseManager();

    if ($database->updateUser($pseudonym, $progress) ){
        $database->setProgressTimestamp($pseudonym, $progress);
        echo "successfull";
    } else {
        echo "error";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

