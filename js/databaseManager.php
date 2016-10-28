<?php
include ("consts.php");

class databaseManager extends consts {

    /* @var $conn PDO */
    private $conn;


    /**
     * Database_progress_timestamp constructor.
     */
    public function __construct()
    {
        $servername = consts::getSERVERNAME();
        $username = consts::getUSERNAME();
        $password = consts::getPASSWORD();
        $dbname = consts::getDATABASENAME();

        // Create connection
        $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
        $sqlPrepared = null;

        $updatePart1 = "UPDATE subject SET progress = :progress";
        $updatePart2 = ", code = :code";
        $updateWhere = " WHERE pseudonym = :pseudonym AND progress = :whereProgress";

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
            return true;
        } else {
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
        $sqlPrepared = $this->conn->prepare("INSERT INTO subject (pseudonym) VALUES (:pseudonym)");
        $sqlPrepared->bindParam(":pseudonym", $pseudonym);

        if ($sqlPrepared->execute() === TRUE) {

            $rowCount = $this->conn->query("SELECT COUNT(*) as countRows FROM subject");
            $rowCount = $rowCount->fetch();
            $rowCount = $rowCount["countRows"] % 2;

            $sqlVersion = $this->conn->prepare("UPDATE subject SET version= :rowCount WHERE pseudonym = :pseudonym");
            $sqlVersion->bindParam(":rowCount", $rowCount);
            $sqlVersion->bindParam(":pseudonym", $pseudonym);

            if ($sqlVersion->execute() === TRUE) {
                return true;
            } else {
                return false;
            }

        } else {
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
        }

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

}
