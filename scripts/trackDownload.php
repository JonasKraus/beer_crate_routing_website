<?php
/**
 * Created by PhpStorm.
 * User: jonas-uni
 * Date: 20.02.2017
 * Time: 22:22
 */
include("../php/databaseManager.php");

error_reporting(E_ALL);

$version = $_GET['version'];
$requestTime = $_SERVER['REQUEST_TIME'];
$referrer = $_SERVER['HTTP_REFERER'];
$location = getLocationInfoByIp();
$userAgent= $_POST['HTTP_USER_AGENT'];

$fileName = "game.zip";
$file = "../download/" . $version . "/game.zip";


// Check which version
switch ($version) {

    case 'zuox02z0weq':

        $version = 'sim';
        break;

    case 'zuox02z1weq':

        $version = 'comic';
        break;
}

try {

    var_dump("heri");die;
    $database = new databaseManager();

    $database->trackDownload($version, $location, $userAgent, $referrer, $requestTime);

} catch (PDOException $e) {

    header("Location: error.html");
    exit();
}

if(!file_exists($file)) die("file doesn't exist.");

$type = filetype($file);
// Get a date and timestamp
$today = date("F j, Y, g:i a");
$time = time();
// Send file headers
header("Content-type: $type");
header("Content-Disposition: attachment;filename=". $fileName);
header("Content-Transfer-Encoding: binary");
header('Pragma: no-cache');
header('Expires: 0');
// Send the file contents.
set_time_limit(0);
readfile($file);

function getLocationInfoByIp(){
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = @$_SERVER['REMOTE_ADDR'];
    $result  = array('country'=>'', 'city'=>'');
    if(filter_var($client, FILTER_VALIDATE_IP)){
        $ip = $client;
    }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
        $ip = $forward;
    }else{
        $ip = $remote;
    }
    $ip_data = @json_decode
    (file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
    if($ip_data && $ip_data->geoplugin_countryName != null){
        $result['country'] = $ip_data->geoplugin_countryCode;
        $result['city'] = $ip_data->geoplugin_city;
    }
    return $result;
}