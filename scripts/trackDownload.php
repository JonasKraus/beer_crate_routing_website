<?php
/**
 * Created by PhpStorm.
 * User: jonas-uni
 * Date: 20.02.2017
 * Time: 22:22
 */

var_dump($_POST);
var_dump($_SERVER);die;

$fileName = "game.zip";
$file = "../download/zuox02z0weq/game.zip";

if(!file_exists($file)) die("I'm sorry, the file doesn't seem to exist.");

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