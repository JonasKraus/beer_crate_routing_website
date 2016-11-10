<?php
include('../php/cryptography.php');
include('../php/databaseConstants.php');


$_POST = getRealPOST();

// Decode form data
$formData = base64_decode($_POST['fd']);
$formData = substr($formData, 7);
$formData = json_decode($formData);
$progress = base64_decode($formData[1]);
$name = base64_decode($formData[0]);

$link = cryptography::wrapProgress($progress, $name);

echo $link;


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



