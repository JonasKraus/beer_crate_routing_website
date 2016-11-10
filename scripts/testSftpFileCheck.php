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
        //echo("construct sftp<br/>");
        $this->sftp = new Net_SFTP('127.0.0.1');
        //echo("construct connection<br/>");
    }
    public function login ($username, $password) {
        //echo("start login<br/>");
        if (!$this->sftp->login($username, $password)) {
            //echo('Login Failed<br/>');
        }
        //echo("logged in<br/>");
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
        //echo($fileNames);
        //echo("filenames read. now return <br/><br/>listing files: <br/>");
        foreach ($fileNames as $file) {
            if (0 !== strpos($file, '.')) {
                //echo("file=".$file." | ". $this->sftp->pwd."<br/>");
                $path=''.$this->sftp->pwd.'/'.$file;
                //echo("path=".$path."<br/>");
                $content = $this->sftp->get($path);
                //echo("content=".$content);
                //echo("<br/><br/>");
                if(mb_strpos($file, 'Tutorial') && mb_strpos($content,'Level Finished;')){
                    $countTutorialCompletions++;
                }
                if(mb_strpos($file, 'Level') && mb_strpos($content,'Level Finished;')){
                    $countLevelCompletions++;
                }
            }
        }
        //echo("countTutComplete=".$countTutorialCompletions."<br/>");
        //echo("countLvlComplete=".$countLevelCompletions."<br/>");
        $retval=($countTutorialCompletions+$countLevelCompletions)>=2;
        echo("returning retval=");
        var_dump($retval);
        return $retval;
    }
}
$con=new SFTPConnection("localhost");
$con->login("beerrouting","WaTrX5NF");
$con->scanFilesForCompletion("jonas", "comic");