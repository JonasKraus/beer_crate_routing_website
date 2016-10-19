<?php

error_reporting(E_ALL);

include("consts.php");
include("cryptography.php");

$servername = consts::getSERVERNAME();
$username = consts::getUSERNAME();
$password = consts::getPASSWORD();
$dbname = consts::getDATABASENAME();

$responseStatus = '200 OK';
$responseText = '';
$pseudonym = null;

try {
// Create connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
// set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $progress = $_GET['pgr'];
    $getData = cryptography::unwrapProgress($progress);
    $progress = $getData["progress"];
    $getUser = $getData["pseudonym"];


    if (isset($_COOKIE["beercrate_routing_pseudonym"]) && $getUser != $_COOKIE["beercrate_routing_pseudonym"]) {
        header("Location: http://localhost:63343/untitled1/error.html");
        exit();
    }

    $pseudonym = $getUser;

    setcookie("beercrate_routing_pseudonym", $pseudonym, time() + (86400 * 7), ";path=/untitled1"); // Cookie for 7 days

    $sqlPrepared = null;

    $updatePart1 = "UPDATE subject SET progress = :progress";
    $updatePart2 = ", code = :code";
    $updateWhere = " WHERE pseudonym = :pseudonym AND progress = :whereProgress";

    if ($progress < 5) {
        $sqlPrepared = $conn->prepare($updatePart1 . $updateWhere);
    } else if ($progress == 5) {
        $sqlPrepared = $conn->prepare($updatePart1 . $updatePart2 . $updateWhere);
        $code = cryptography::wrapProgress($progress, $pseudonym, true);
        $sqlPrepared->bindParam(":code", $code);
    }

    $sqlPrepared->bindParam(":progress", $progress);
    $whereProgress = $progress - 1;
    $sqlPrepared->bindParam(":whereProgress", $whereProgress);
    $sqlPrepared->bindParam(":pseudonym", $pseudonym);

    $sqlPrepared->execute();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
