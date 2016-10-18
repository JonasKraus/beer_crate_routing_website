<?php

error_reporting(E_ALL);

include ("consts.php");
include ("cryptography.php");

$servername = consts::getSERVERNAME();
$username = consts::getUSERNAME();
$password = consts::getPASSWORD();
$dbname = consts::getDATABASENAME();

$responseStatus = '200 OK';
$responseText = '';

$request_body = file_get_contents('php://input');
$pseudonym = null;

/*
if(!isset($_COOKIE["beercrate_routing_pseudonym"])) {


    if ($pseudonym == 'undefined' || $pseudonym == null || $pseudonym = '') {
        $prompt_msg = 'Bitte geb dein Kürzel ein';


        var_dump("hier");

        echo("<script type='text/javascript'> var answer = prompt('".$prompt_msg."'); </script>");

        echo  "<script type='text/javascript'> document.cookie = \"beercrate_routing_pseudonym=\" + answer; </script>";

        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        header("Location: http://localhost:63343/untitled1/error.html");

        exit;
    }

} else {
*/

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

    $pseudonym = $_COOKIE["beercrate_routing_pseudonym"];

    if (isset($pseudonym) && $getUser != $pseudonym) {
        header("Location: http://localhost:63343/untitled1/error.html");
        exit();
    }

    $pseudonym = $getUser;

    setcookie("beercrate_routing_pseudonym", $pseudonym, time() + (86400 * 7)); // Cookie for 7 days

    $sql = "UPDATE subject SET progress=" . $progress . " WHERE pseudonym='" . $pseudonym . "' AND progress = " . ($progress -1);


    if ($progress == 5) {

        // add random string
        $hash = cryptography::wrapProgress($progress, $pseudonym);
        $insert = "UPDATE subject SET code='" . $hash . "' WHERE pseudonym = '" . $pseudonym . "' AND code = null AND progress = " . ($progress - 1);

        if ($conn->query($insert) === TRUE) {
            echo "Saved code successfully";
        } else {
            echo "Error: " . $insert . "<br>" . $conn->error;
        }

    }


    if ($conn->query($sql) === TRUE && $conn->affected_rows > 0) {
        echo "Updated record successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        header("Location: http://localhost:63343/untitled1/error.html");
        exit();
    }

    $conn->close();



header($_SERVER['SERVER_PROTOCOL'].' '.$responseStatus);
header('Content-type: text/html; charset=utf-8');
//header("Location: http://localhost:63343/untitled1/"); TODO
echo $responseText;

function generateRandomString($length = 3) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
