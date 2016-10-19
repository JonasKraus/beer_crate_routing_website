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

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

$sql = "UPDATE subject SET progress=" . $progress . " WHERE pseudonym='" . $pseudonym . "' AND progress = " . ($progress - 1);


if ($progress == 5) {

    // add random string
    $hash = cryptography::wrapProgress($progress, $pseudonym, true);
    $insert = "UPDATE subject SET code='" . $hash . "' WHERE pseudonym = '" . $pseudonym . "' AND code = null AND progress = " . ($progress - 1);

    if ($conn->query($insert) === TRUE) {
        echo "Saved code successfully";
    } else {
        echo "Error: " . $insert . "<br>" . $conn->error;
    }

}


if ($conn->query($sql) === TRUE && $conn->affected_rows > 0) {
    echo "Updated record successfully";
    die;
    exit; // TODO
    //header($_SERVER['SERVER_PROTOCOL'].' '.$responseStatus);
    //header('Content-type: text/html; charset=utf-8');
    header("Location: http://localhost:63343/untitled1/");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    header("Location: http://localhost:63343/untitled1/error.html");
    exit();
}

$conn->close();
