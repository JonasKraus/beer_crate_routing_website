<?php
include("databaseConstants.php");
include("SFTPConnection.php");

error_reporting(E_ALL);
ini_set("log_errors", 1);
ini_set("error_log", "../log/php-error.log");
error_log( "Hello, errors!" );

class databaseManager extends databaseConstants {

    /* @var $conn PDO */
    private $conn;



    /**
     * Database_progress_timestamp constructor.
     */
    public function __construct()
    {
        $servername = databaseConstants::getSERVERNAME();
        $username = databaseConstants::getUSERNAME();
        $password = databaseConstants::getPASSWORD();
        $dbname = databaseConstants::getDATABASENAME();


        try {
            $this->writeLog("try to connect " + date());
            // Create connection
            $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $this->writeLog("conn " . ($this->conn == null));
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->writeLog("connection failed");
            $this->writeLog($e->getMessage());
        }

    }


    /**
     * sets a timestamp when a user is updated
     *
     * @param $pseudonym
     * @param $progress
     */
    public function setProgressTimestamp($pseudonym, $progress) {

        $sqlPrepared = $this->conn->prepare("INSERT INTO progress (pseudonym,progress) VALUES (:pseudonym,:progress)");
        $sqlPrepared->bindParam(":pseudonym", $pseudonym);
        $sqlPrepared->bindParam(":progress", $progress);

        if ($sqlPrepared->execute() === TRUE) {
            return true;
        } else {
            return false;
        }

    }


    /**
     * Updates a user
     *
     * @param $pseudonym
     * @param $progress
     */
    public function updateUser($pseudonym, $progress) {

        $this->writeLog("start updateUser");
        $sqlPrepared = null;

        $updatePart1 = "UPDATE subject SET progress = :progress";
        $updatePart2 = ", code = :code";
        $updateWhere = " WHERE pseudonym = :pseudonym AND progress = :whereProgress";

        self::writeLog("updateUser progress: " .$progress);

        if ($progress < 5) {
            $sqlPrepared = $this->conn->prepare($updatePart1 . $updateWhere);
        } else if ($progress == 5) {
            $sqlPrepared = $this->conn->prepare($updatePart1 . $updatePart2 . $updateWhere);
            $code = cryptography::wrapProgress($progress, $pseudonym, true);
            $sqlPrepared->bindParam(":code", $code);
        }

        $sqlPrepared->bindParam(":progress", $progress);
        $whereProgress = $progress - 1;
        $sqlPrepared->bindParam(":whereProgress", $whereProgress);
        $sqlPrepared->bindParam(":pseudonym", $pseudonym);

        if ($sqlPrepared->execute() === TRUE) {

            self::writeLog("updateUser execution: true ");
            return true;
        } else {

            self::writeLog("updateUser execution: failed ");
            return false;
        }
    }


    /**
     * Sets an user and the game version
     *
     * @param $pseudonym
     * @return bool
     */
    public function setUser($pseudonym) {

        $existingUser = $this->getUser($pseudonym);
        if ($existingUser != null && $existingUser != '') {
            return true;
        }
        $this->writeLog("start set user");
        $sqlPrepared = $this->conn->prepare("INSERT INTO subject (pseudonym) VALUES (:pseudonym)");
        $sqlPrepared->bindParam(":pseudonym", $pseudonym);

        if ($sqlPrepared->execute() === TRUE) {

            $this->writeLog("set user execution successful");

            $rowCount = $this->conn->query("SELECT COUNT(*) as countRows FROM subject");
            $rowCount = $rowCount->fetch();
            $rowCount = $rowCount["countRows"] % 2;

            $sqlVersion = $this->conn->prepare("UPDATE subject SET version= :rowCount WHERE pseudonym = :pseudonym");
            $sqlVersion->bindParam(":rowCount", $rowCount);
            $sqlVersion->bindParam(":pseudonym", $pseudonym);

            if ($sqlVersion->execute() === TRUE) {

                $cookieName = 'beercrate_routing_pseudonym';

                if(!isset($_COOKIE[$cookieName])) {
                    setcookie($cookieName, $pseudonym, time() + (86400 * 30) * 15, databaseConstants::$COOKIE_PATH); // TODO zeit anpassen
                } else if ($_COOKIE[$cookieName] != $pseudonym){
                    setcookie($cookieName, $pseudonym, time() + (86400 * 30) * 15, databaseConstants::$COOKIE_PATH); // TODO zeit anpassen
                }

                return true;
            } else {
                return false;
            }

        } else {

            $this->writeLog("set user execution failed");
            return false;
        }
    }


    /**
     * Returns the user data by the given pseudonym
     *
     * @param $pseudonym
     * @return null|string
     */
    public function getUser($pseudonym) {

        $this->writeLog("start getUser");

        $sqlPrepared = $this->conn->prepare("SELECT * FROM subject WHERE pseudonym = :pseudonym");
        $sqlPrepared->bindParam(":pseudonym", $pseudonym);

        $sqlPrepared->execute();
        $results = $sqlPrepared->fetchAll();

        $user = null;

        if (count($results) == 1) {
            // output data of each row
            foreach ($results as $result) {
                $user = '{"pseudonym":"' . $result["pseudonym"] . '","progress":' . $result["progress"] . ',"version":' . $result["version"] . ',"code":"' . $result["code"] . '"' . ',"exam":"' . $result["exam"] . '"' . ',"exercise":"' . $result["exercise"] . '"' .  "}";
            }
        } else {
            self::writeLog("getUser resultset length: " . count($results));

        }

        if ($user != null) {
            $cookieName = 'beercrate_routing_pseudonym';

            if(!isset($_COOKIE[$cookieName])) {
                setcookie($cookieName, $pseudonym, time() + (86400 * 30) * 15, databaseConstants::$COOKIE_PATH); // TODO zeit anpassen
            } else if ($_COOKIE[$cookieName] != $pseudonym){
                setcookie($cookieName, $pseudonym, time() + (86400 * 30) * 15, databaseConstants::$COOKIE_PATH); // TODO zeit anpassen
            }
        }

        self::writeLog("getUser user found: " . $user);



        return $user;
    }


    /**
     * sets the credits of either the exam or the exercise
     *
     * @param $pseudonym
     * @param $creditName
     * @param $credit
     * @return bool
     */
    public function setUserCredit($pseudonym, $creditName, $credit) {

        $sqlPrepared = $this->conn->prepare("UPDATE subject SET " . $creditName . " = :" . $creditName . " WHERE pseudonym = :pseudonym");
        $sqlPrepared->bindParam(":" . $creditName, str_replace(",", ".", $credit));
        $sqlPrepared->bindParam(":pseudonym", $_COOKIE["beercrate_routing_pseudonym"]);

        if ($sqlPrepared->execute() === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function getProgressData ($progress = null, $onlyData = false) {

        $sqlPrepared = null;
        $chartLabel = "Fortschritt: " . $sqlPrepared;
        $query = "SELECT * FROM progress ";
        $orderBy = " ORDER BY dateCreated";
        if (isset($progress)) {
            $query .= " WHERE progress = :progress";
            $sqlPrepared = $this->conn->prepare($query . $orderBy);
            $sqlPrepared->bindParam(":progress", $progress);
        } else {

            $sqlPrepared = $this->conn->prepare($query . $orderBy);
        }

        $sqlPrepared->execute();

        $data = $sqlPrepared->fetchAll();

        //return $result;


        if (count($data) < 2 ) {
            array(
                "success" => false,
                "message" => "Zu wenig Daten vorhanen"
            );
        } else {

            $res = explode("x", $_COOKIE["res"]);
            $width = $res[0] + 0;
            $height = $res[1] + 0;
            $chartRes = 10;

            $dateStart = strtotime($data[0]["dateCreated"]);
            $dateEnd = strtotime($data[count($data)-1]["dateCreated"]);
            $totalTime = $dateEnd - $dateStart;

            $timeInterval = $totalTime / $chartRes;

            //echo $timeInterval;

            $lineData = array();
            $tmpTime = $dateStart;
            $dataCounter = 0;
            $lastValue = 0;
            $labels = array();
            $values = array();
            $labels[] = $data[0]['dateCreated'];

            foreach ($data as $key=>$dataPoint) {

                /*echo "muss kleiner sein " . strtotime($dataPoint['dateCreated']) . "<br>";
                echo "als " . ($tmpTime + $timeInterval) . "<br>";
                echo $dataCounter . "<br>";
                */

                if (strtotime($dataPoint['dateCreated']) < ($tmpTime + $timeInterval)) {
                    //echo "weiter <br>" ;
                    $dataCounter ++;
                } else {
                    //echo "neues interval<br>";
                    $lineData[] = array(
                        "time" => $dataPoint['dateCreated'],
                        "value" => $dataCounter
                    );
                    $labels[] = $dataPoint['dateCreated'];
                    $values[] = $dataCounter;
                    $dataCounter = 0;
                    $tmpTime = strtotime($dataPoint['dateCreated']);
                }

                if ($key == count($data)- 1) {
                    $lineData[] = array(
                        "time" => $dataPoint['dateCreated'],
                        "value" => $dataCounter
                    );
                    $values[] = $dataCounter;
                    $dataCounter = 0;
                    $tmpTime = strtotime($dataPoint['dateCreated']);
                }



                //echo strtotime($dataPoint['dateCreated']) . "<br>";
                //echo $dataCounter . "<br>";
            }

            if (!$onlyData) {
                return self::buildProgressDataResponse($labels, $values, $chartLabel);
            }

        }
        return null;
    }

    public function getAllProgressData () {
        $values = array();
        for ($i = 0; $i < 6; $i++) {

            $values[] = self::getProgressData($i, true);
        }

        //self::buildProgressDataResponse()
    }

    private function buildProgressDataResponse ($labels, $values, $chartLabel) {

        $debug = false;
        if (!$debug) {
            $response = '{
                "labels": ' . json_encode($labels) . ',
                "datasets": [
                  {
                    "label": "' . $chartLabel . '",
                    "fill":true,
                    "backgroundColor":"#0c4d4d",
                    "hoverBackgroundColor": "#FF6384",
                    "data": ' . json_encode($values) . '
                  }
                ]
              }';

        } else {
            $response = array(
                "labels" => $labels,
                "data" => $values
            );
            $response = json_encode($response);
        }

        header('Content-Type: application/json');
        return $response;
        //echo json_encode($lineData);
    }

    public function getProgress($pseudonym) {

        $sqlPrepared = $this->conn->prepare("SELECT progress FROM subject WHERE pseudonym = :pseudonym");
        $sqlPrepared->bindParam(":pseudonym", $pseudonym);

        $sqlPrepared->execute();
        $results = $sqlPrepared->fetchAll();

        $progress = null;

        if (count($results) == 1) {
            // output data of each row
            foreach ($results as $result) {
                $progress = '{"progress":' . $result["progress"]."}";
            }
        }

        return $progress;
    }

    public function getUsersProgress () {
        $sqlPrepared = $this->conn->prepare("SELECT DISTINCT(select count(progress) from subject where progress = 0) AS p0, (select count(progress) from subject where progress = 1) AS p1, (select count(progress) from subject where progress = 2) AS p2, (select count(progress) from subject where progress = 3) AS p3, (select count(progress) from subject where progress = 4) AS p4, (select count(progress) from subject where progress = 5) AS p5, (select count(*) from subject) AS Teilnehmer_Gesamt FROM subject;");

        $sqlPrepared->execute();
        $results = $sqlPrepared->fetchAll();

        $str = "";
        foreach ($results[0] as $key=>$record) {
            $str .= '<span>' . $key . " " . $record . "</span><br>";
        }


        echo $str;
        //return json_encode($results);
    }

    public function getProgressUpdate($pseudonym, $version, $versionFromRequest) {

        $this->writeLog("start getProgressUpdate");
        $nextProgress = null;

        self::writeLog("start sftp");

        try {
            $sft = new SFTPConnection(databaseConstants::$SERVER_NAME_SFTP);
            self::writeLog("databaseManager ->start login");
            $sft->login(databaseConstants::$USER_NAME_SFTP, databaseConstants::$USER_PASSWORD_SFTP);
            $versionName = $version == databaseConstants::getVERSIONCOMIC()
                ? databaseConstants::getVERSIONCOMICNAME()
                : databaseConstants::getVERSIONSIMNAME();

            $isCompleted = $sft->scanFilesForCompletion($pseudonym, $versionName);

            if (!$isCompleted) {
                return array(
                    "success:" => false
                );
            }

            self::writeLog("getProgressUpdate is completed: " . $isCompleted);

            self::writeLog("version db: " . $version . " version request: " . $versionFromRequest);

            // Check to which step to update
            if ($version == $versionFromRequest) {
                $nextProgress = 2;
            } else {
                $nextProgress = 4;
            }

            return $this->updateUser($pseudonym, $nextProgress);

        } catch (Exception $exception) {
            self::writeLog("getProgressUpdate error sftp: " . $exception->getMessage());
        }
        return array(
            "success:" => false
        );

    }

    public function writeLog ($message, $fileLogging = true) {

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

}
