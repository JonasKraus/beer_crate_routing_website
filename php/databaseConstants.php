<?php

class databaseConstants {

    private static $SERVER_NAME = "127.0.0.1";
    private static $USER_NAME = "root";
    private static $PASSWORD = "";
    private static $DATABASE_NAME = "beercrate_routing";
    private static $KEY = "Testverschlüsselung";

    private static $VERSION_SIM = 0;
    private static $VERSION_COMIC = 1;
    private static $VERSION_SIM_NAME = 'sim';
    private static $VERSION_COMIC_NAME = 'comic';
    public static $DEBUG = false;


    /**
     * @return string
     */
    public static function getVERSIONSIMNAME()
    {
        return self::$VERSION_SIM_NAME;
    }

    /**
     * @return string
     */
    public static function getVERSIONCOMICNAME()
    {
        return self::$VERSION_COMIC_NAME;
    }


    /**
     * @return int
     */
    public static function getVERSIONSIM()
    {
        return self::$VERSION_SIM;
    }

    /**
     * @return int
     */
    public static function getVERSIONCOMIC()
    {
        return self::$VERSION_COMIC;
    }


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