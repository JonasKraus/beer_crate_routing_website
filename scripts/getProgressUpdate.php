<?php

include("../php/databaseManager.php");
include("../php/cryptography.php");

$responseStatus = '200 OK';
$responseText = '';

$pseudonym = null;
$versionFromRequest = null;
$method = null;


if (isset($_POST['ps']) && isset($_POST['vr']) && isset($_SERVER ['HTTP_USER_AGENT']) && $_SERVER ['HTTP_USER_AGENT'] == 'UnityPlayer/5.3.4f1 (UnityWebRequest/1.0, libcurl/7.38.0-DEV)') {

    $versionFromRequest = strtolower($_POST['vr']) == databaseConstants::getVERSIONSIMNAME()
        ? databaseConstants::getVERSIONSIM()
        : databaseConstants::getVERSIONCOMIC();

    $pseudonym = $_POST['ps'];

    writeLog("request: version->" . $versionFromRequest . " pseudonym->" . $pseudonym . " user-agent->" . $_SERVER ['HTTP_USER_AGENT']);

    setcookie("beercrate_routing_pseudonym", $pseudonym, time() + (86400 * 7), ";path=/dijkstra-studie"); // Cookie for 7 days

    $progress = null;

    try {

        $database = new databaseManager();

        $user = $database->getUser($pseudonym);
        $user = json_decode($user);

        $success = $database->getProgressUpdate($pseudonym, $user->version, $versionFromRequest);

        if (!$success) {
            $responseStatus = '200';
        }

    } catch (PDOException $e) {
        header($_SERVER['SERVER_PROTOCOL'].' '.$responseStatus);
        header('Content-type: text/html; charset=utf-8');
        exit();
    }

    header($_SERVER['SERVER_PROTOCOL'].' '.$responseStatus);
    header('Content-type: text/html; charset=utf-8');

} else {

    writeLog("request: version->" . $_POST['vr'] . " pseudonym->" . $_POST['ps'] . " user-agent->" . $_SERVER ['HTTP_USER_AGENT']);
    $responseStatus = '200';
    header($_SERVER['SERVER_PROTOCOL'].' '.$responseStatus);
    header('Content-type: text/html; charset=utf-8');
    exit();
}


function writeLog ($message, $fileLogging = true) {

    if ($fileLogging) {
        chmod("request_log.txt", 0777);
        $myfile = fopen("request_log.txt", "w+") or die("Unable to open file!");
        fwrite($myfile, $message);
    } else {
        echo $message;
    }

}
