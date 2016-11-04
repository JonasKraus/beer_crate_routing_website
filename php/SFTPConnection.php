<?php
set_include_path('../phpseclib');

include('../phpseclib/Net/SSH2.php');
include('../phpseclib/Net/SFTP.php');
class SFTPConnection
{

    /* @var $sftp Net_SFTP */
    private $sftp;
    /**
     * SFTPConnection constructor.
     */
    public function __construct($host)
    {
        $this->writeLog("construct sftp");
        $this->sftp = new Net_SFTP('chernobog.dd-dns.de');
        $this->writeLog("construct connection");
    }

    public function login ($username, $password) {
        $this->writeLog("start login");
        if (!$this->sftp->login($username, $password)) {
            $this->writeLog('Login Failed');
        }
        $this->writeLog("logged in");
    }

    public function scanFilesystem ($username, $version) {
        $this->writeLog("start scan");
        $this->sftp->chdir('surveylog');
        $this->writeLog("chdir 1");
        $this->sftp->chdir($username);
        $this->writeLog("chdir 2");
        $this->sftp->chdir($version);
        $this->writeLog("chdir 3");
        $dirs = $this->sftp->rawlist();
        $this->writeLog("rawlist");

        $fileNames = array();
        foreach ($dirs as $dir) {
            $this->writeLog('scan filename: ' . $dir['filename']);
            $fileNames[] = $dir['filename'];
        }
        $this->writeLog("filenames read. now return ");
        return $fileNames;
    }

    public function writeLog ($message, $fileLogging = true) {

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