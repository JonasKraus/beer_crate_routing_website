<?php

include("../php/databaseManager.php");
include("../php/cryptography.php");

error_reporting(E_ALL);
ini_set("log_errors", 1);
ini_set("error_log", "../log/php-error.log");
error_log( "Hello, errors!" );

$responseStatus = '200 OK';
$responseText = '';

$pseudonym = null;
$versionFromRequest = null;
$method = null;

writeLog("hallo world");

// UnityWebRequest/1.0, libcurl/7.38.0-DEV
if (isset($_POST['ps']) && isset($_POST['vr']) && isset($_SERVER ['HTTP_USER_AGENT'])/* && strpos($_SERVER ['HTTP_USER_AGENT'], "Unity") !== false TODO*/) {

    $versionFromRequest = strtolower($_POST['vr']) == databaseConstants::getVERSIONSIMNAME()
        ? databaseConstants::getVERSIONSIM()
        : databaseConstants::getVERSIONCOMIC();

    $pseudonym = $_POST['ps'];

    writeLog("request: version->" . $versionFromRequest . " pseudonym->" . $pseudonym . " user-agent->" . $_SERVER ['HTTP_USER_AGENT']);

    //setcookie("beercrate_routing_pseudonym", $pseudonym, time() + (86400 * 7), ";path=/dijkstra-studie"); // Cookie for 7 days

    $progress = null;

    try {

        writeLog("try to get database");

        $database = new databaseManager();

        $user = $database->getUser($pseudonym);
        $user = json_decode($user);

        $success = $database->getProgressUpdate($pseudonym, $user->version, $versionFromRequest);

        if (!$success) {
            $responseStatus = '500';
        }

    } catch (PDOException $e) {
        header($_SERVER['SERVER_PROTOCOL'].' '.$responseStatus);
        header('Content-type: text/html; charset=utf-8');
        exit();
    }

    header($_SERVER['SERVER_PROTOCOL'].' '.$responseStatus);
    header('Content-type: text/html; charset=utf-8');

} else {

    writeLog("request failed: version->" . $_POST['vr'] . " pseudonym->" . $_POST['ps'] . " user-agent->" . $_SERVER ['HTTP_USER_AGENT']);
    $responseStatus = '500';
    header($_SERVER['SERVER_PROTOCOL'].' '.$responseStatus);
    exit();
}


function writeLog ($message, $fileLogging = true) {
    if (databaseConstants::$DEBUG) {
        if ($fileLogging) {
            //chmod("../log/request_log.txt", 0777);

            $file = '../log/request_log.txt';
            $current = file_get_contents($file);
            $current .= "\n" . $message;
            file_put_contents($file, $current);
        } else {
            echo $message;
        }
    }


}
