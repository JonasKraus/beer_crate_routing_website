<?php
error_reporting(E_ALL);
ini_set("log_errors", 1);
ini_set("error_log", "../log/php-error.log");
error_log( "Hello, errors!" );

include("../php/databaseManager.php");

$responseStatus = '200 OK';
$responseText = '';

$pseudonym = file_get_contents('php://input');

if(!isset($pseudonym)) {
    $responseStatus = '400 Bad Request';
    $responseText = 'Anfrage erhält keinen Nutzernamen';
} else {

    try {

        $database = new databaseManager();
        writeLog("script set user database:". ($database == null));
        if (!$database->setUser($pseudonym)) {
            $responseText = 'Error while creating User';
            writeLog("script set user " . $responseText);
        } else {
            writeLog("script set user successfull try set timestamp");
            $database->setProgressTimestamp($pseudonym, 0);
        }

    } catch (PDOException $e) {
        //echo "Error: " . $e->getMessage();
        header("Location: ../error.html");
        exit();
    }

}

header($_SERVER['SERVER_PROTOCOL'].' '.$responseStatus);
header('Content-type: text/html; charset=utf-8');

function writeLog ($message, $fileLogging = true) {

    if (databaseConstants::$DEBUG) {
        if ($fileLogging) {
            $file = '../log/request_log.txt';
            $current = file_get_contents($file);
            $current .= "\n" . $message;
            file_put_contents($file, $current);
        } else {
            echo $message;
        }
    }


}