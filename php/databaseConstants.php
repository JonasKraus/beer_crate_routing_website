<?php

class databaseConstants {

    private static $SERVER_NAME = "127.0.0.1";
    private static $USER_NAME = "root";
    private static $PASSWORD = "";
    private static $DATABASE_NAME = "beercrate_routing";
    private static $KEY = "Testverschlüsselung";

    /**
     * @return string
     */
    public static function getKEY()
    {
        return self::$KEY;
    }

    /**
     * @return string
     */
    public static function getSERVERNAME()
    {
        return self::$SERVER_NAME;
    }

    /**
     * @return string
     */
    public static function getUSERNAME()
    {
        return self::$USER_NAME;
    }

    /**
     * @return string
     */
    public static function getPASSWORD()
    {
        return self::$PASSWORD;
    }

    /**
     * @return string
     */
    public static function getDATABASENAME()
    {
        return self::$DATABASE_NAME;
    }


}