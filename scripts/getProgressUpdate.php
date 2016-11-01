<?php

include("../php/databaseManager.php");
include("../php/cryptography.php");

$responseStatus = '200 OK';
$responseText = '';

$pseudonym = null;

if (isset($_GET['pgr'])) {
    $pseudonym = $_GET['ps'];
} else if (isset($_POST['ps'])) {
    $pseudonym = $_POST['ps'];
} else if (isset($_COOKIE["beercrate_routing_pseudonym"])) {
    $pseudonym = $_COOKIE["beercrate_routing_pseudonym"];
}

// TODO check sft files

setcookie("beercrate_routing_pseudonym", $pseudonym, time() + (86400 * 7), ";path=/dijkstra-studie"); // Cookie for 7 days

$progress = null;

try {

    $database = new databaseManager();

    $link = $database->getProgressUpdate($pseudonym);

    echo $link;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    header("Location: ../error.html");
    exit();
}

header($_SERVER['SERVER_PROTOCOL'].' '.$responseStatus);
header('Content-type: text/html; charset=utf-8');
