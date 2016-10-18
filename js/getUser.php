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
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM subject WHERE pseudonym = '" . $request_body . "'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            //echo "id: " . $row["pseudonym"]. " - Progress: " . $row["progress"]. " - Version:" . $row["version"];
            $user = '{"pseudonym":"' . $row["pseudonym"] . '","progress":' . $row["progress"] . ',"version":' . $row["version"] . ',"code":"' . $row["code"] . '"' . "}";
            echo $user;
        }
    } else {
        echo "0 results";
    }

    $conn->close();

}

header($_SERVER['SERVER_PROTOCOL'].' '.$responseStatus);
header('Content-type: text/html; charset=utf-8');
echo $responseText;