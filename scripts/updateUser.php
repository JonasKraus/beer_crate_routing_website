<?php


include("../php/databaseManager.php");
include("../php/cryptography.php");

$responseStatus = '200 OK';
$responseText = '';

$progress = urlencode($_GET['pgr']);

$getData = cryptography::unwrapProgress($progress);
$progress = $getData["progress"];
$pseudonym = $getData["pseudonym"];


if (isset($_COOKIE["beercrate_routing_pseudonym"]) && $pseudonym != $_COOKIE["beercrate_routing_pseudonym"]) {
    header("Location: ../error.html");
    exit();
}

setcookie("beercrate_routing_pseudonym", $pseudonym, time() + (86400 * 7), ";path=/dijkstra-studie"); // Cookie for 7 days

try {

    $database = new databaseManager();

    if ($database->updateUser($pseudonym, $progress) ){
        $database->setProgressTimestamp($pseudonym, $progress);
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    header("Location: ../error.html");
    exit();
}

header($_SERVER['SERVER_PROTOCOL'].' '.$responseStatus);
header('Content-type: text/html; charset=utf-8');
header("Location: ../index.html");

