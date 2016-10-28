<?php

include("databaseManager.php");

$responseStatus = '200 OK';
$responseText = '';

$updateVar = null;
$updateVarName = '';

if(isset($_GET['exam']) != ""){
    $updateVar = $_GET['exam'];
    $updateVarName = 'exam';
} else if(isset($_GET['exercise']) != ""){
    $updateVar = $_GET['exercise'];
    $updateVarName = 'exercise';
}

if($updateVarName == '') {
    $responseStatus = '400 Bad Request';
    $responseText = 'Anfrage erhält keinen gültigen Daten';
} else {

    try {
        $pseudonym = $_COOKIE["beercrate_routing_pseudonym"];
        $database = new databaseManager();
        $database->setUserCredit($pseudonym, $updateVarName, $updateVar);

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

}

header('Location: ../index.html');
exit;
