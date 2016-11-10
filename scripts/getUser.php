<?php
include("../php/databaseManager.php");


$responseStatus = '200 OK';
$responseText = '';
$pseudonym = null;

$_POST = getRealPOST();

if (isset($_POST['pseudonym'])) {
    $pseudonym = $_POST['pseudonym'];
}

if(!isset($pseudonym)) {
    $responseStatus = '400 Bad Request';
    $responseText = 'Anfrage erhält keinen Nutzernamen';
} else {

    try {

        $database = new databaseManager();
        $user = $database->getUser($pseudonym);

        echo $user;

    } catch (PDOException $e) {
        //echo "Error: " . $e->getMessage();
    }

}

header($_SERVER['SERVER_PROTOCOL'].' '.$responseStatus);
header('Content-type: text/html; charset=utf-8');

function getRealPOST() {
    $pairs = explode("&", file_get_contents("php://input"));
    $vars = array();
    foreach ($pairs as $pair) {
        $nv = explode("=", $pair);
        $name = urldecode($nv[0]);
        $value = urldecode($nv[1]);
        $vars[$name] = $value;
    }
    return $vars;
}
