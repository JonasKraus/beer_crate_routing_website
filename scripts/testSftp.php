<?php
set_include_path('../phpseclib');

include('../phpseclib/Net/SSH2.php');
include('../phpseclib/Net/SFTP.php');

$sftp = new Net_SFTP('chernobog.dd-dns.de');
if (!$sftp->login('beerrouting', 'WaTrX5NF')) {
    exit('Login Failed');
}


$sftp->chdir('surveylog');
$sftp->chdir('FW');
$sftp->chdir('sim');
$dirs = $sftp->rawlist();
//print_r($sftp->nlist()); // == $sftp->nlist('.')

$fileNames = array();
foreach ($dirs as $dir) {

    $fileNames[] = $dir['filename'];
}

var_dump($fileNames);