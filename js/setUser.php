<?php
include("databaseManager.php");

$responseStatus = '200 OK';
$responseText = '';

$pseudonym = file_get_contents('php://input');

if(!isset($pseudonym)) {
    $responseStatus = '400 Bad Request';
    $responseText = 'Anfrage erhält keinen Nutzernamen';
} else {

    try {

        $database = new databaseManager();
        if (!$database->setUser($pseudonym)) {
            $responseText = 'Errpr while creating User';
        } else {
            $database->setProgressTimestamp($pseudonym, 0);
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

}

header($_SERVER['SERVER_PROTOCOL'].' '.$responseStatus);
header('Content-type: text/html; charset=utf-8');
echo $responseText;