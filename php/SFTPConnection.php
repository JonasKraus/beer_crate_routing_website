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
        $this->sftp = new Net_SFTP('127.0.0.1');
        $this->writeLog("construct connection");
    }

    public function login ($username, $password) {
        $this->writeLog("start login");
        if (!$this->sftp->login($username, $password)) {
            $this->writeLog('Login Failed');
        }
        $this->writeLog("logged in");
    }

    public function scanFilesForCompletion ($username, $version) {

        $this->sftp->chdir('surveylog');
        $this->sftp->chdir($username);
        $this->sftp->chdir($version);
        $dirs = $this->sftp->rawlist();
        $countTutorialCompletions=0;
        $countLevelCompletions=0;
        $fileNames = array();

        foreach ($dirs as $dir) {
            $fileNames[] = $dir['filename'];
        }

        foreach ($fileNames as $file) {

            if (0 !== strpos($file, '.')) {

                $path=''.$this->sftp->pwd.'/'.$file;

                $content = $this->sftp->get($path);

                if(mb_strpos($file, 'Tutorial') && mb_strpos($content,'Level Finished;')){
                    $countTutorialCompletions++;
                }

                if(mb_strpos($file, 'Level') && mb_strpos($content,'Level Finished;')){
                    $countLevelCompletions++;
                }
            }
        }

        $retval=($countTutorialCompletions+$countLevelCompletions)>=2;

        return $retval;
    }

    public function scanFiles ($username, $version) {
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

        $containsTut = false;
        $containsLevel = false;

        foreach ($fileNames as $fileName) {

            $this->writeLog($fileName);

            if (strpos(strtoupper($fileName), 'LEVEL 1') !== false) {
                $containsLevel = true;

            } else if (strpos(strtoupper($fileName), 'TUTORIAL') !== false) {
                $containsTut = true;
            }
        }

        if (!$containsTut || !$containsLevel) {

            return false;
        }

        return true;
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