<?php
include ("consts.php");

$servername = consts::getSERVERNAME();
$username = consts::getUSERNAME();
$password = consts::getPASSWORD();
$dbname = consts::getDATABASENAME();


$responseStatus = '200 OK';
$responseText = '';

$request_body = file_get_contents('php://input');

if(!isset($request_body)) {
    $responseStatus = '400 Bad Request';
    $responseText = 'Anfrage erhält keinen Nutzernamen';
} else {

    // Create connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sqlPrepared = $conn->prepare("SELECT * FROM subject WHERE pseudonym = :pseudonym");
    $sqlPrepared->bindParam(":pseudonym", $request_body);

    $sqlPrepared->execute();
    $results = $sqlPrepared->fetchAll();

    if (count($results) == 1) {
        // output data of each row
        foreach ($results as $result) {
            //echo "id: " . $row["pseudonym"]. " - Progress: " . $row["progress"]. " - Version:" . $row["version"];
            $user = '{"pseudonym":"' . $result["pseudonym"] . '","progress":' . $result["progress"] . ',"version":' . $result["version"] . ',"code":"' . $result["code"] . '"' . ',"exam":"' . $result["exam"] . '"' . ',"exercise":"' . $result["exercise"] . '"' .  "}";
            echo $user;
        }
    } else {
        echo count($results) . " results";
    }

}

header($_SERVER['SERVER_PROTOCOL'].' '.$responseStatus);
header('Content-type: text/html; charset=utf-8');
echo $responseText;