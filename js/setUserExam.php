<?php

include ("consts.php");

$servername = consts::getSERVERNAME();
$username = consts::getUSERNAME();
$password = consts::getPASSWORD();
$dbname = consts::getDATABASENAME();


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
    $responseText = 'Anfrage erhält keinen Nutzernamen';
} else {

    try {
        // Create connection
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sqlPrepared = $conn->prepare("UPDATE subject SET " . $updateVarName . " = :" . $updateVarName . " WHERE pseudonym = :pseudonym");
        $sqlPrepared->bindParam(":" . $updateVarName, str_replace(",", ".", $updateVar));
        $sqlPrepared->bindParam(":pseudonym", $_COOKIE["beercrate_routing_pseudonym"]);

        if ($sqlPrepared->execute() === TRUE) {
            echo "Updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }


    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

}

header('Location: ../index.html');
exit;
