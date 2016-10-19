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

    try {
        // Create connection
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sqlPrepared = $conn->prepare("INSERT INTO subject (pseudonym) VALUES (:pseudonym)");
        $sqlPrepared->bindParam(":pseudonym", $request_body);

        if ($sqlPrepared->execute() === TRUE) {
            echo "New record created successfully Prepared";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $responseText = "created successfully";

        $rowCount = $conn->query("SELECT COUNT(*) as countRows FROM subject");
        $rowCount = $rowCount->fetch();
        $rowCount = $rowCount["countRows"] % 2;

        $sqlVersion = $conn->prepare("UPDATE subject SET version= :rowCount WHERE pseudonym = :pseudonym");
        $sqlVersion->bindParam(":rowCount", $rowCount);
        $sqlVersion->bindParam(":pseudonym", $request_body);

        $sqlVersion->execute();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

}

header($_SERVER['SERVER_PROTOCOL'].' '.$responseStatus);
header('Content-type: text/html; charset=utf-8');
echo $responseText;