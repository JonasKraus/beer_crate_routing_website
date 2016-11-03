<?php

class SFTPConnection
{

    /* @var $sftp Net_SFTP */
    private $sftp;
    /**
     * SFTPConnection constructor.
     */
    public function __construct($host)
    {
        $this->sftp = new Net_SFTP('chernobog.dd-dns.de');
    }

    public function login ($username, $password) {
        if (!$this->sftp->login($username, $password)) {
            exit('Login Failed');
        }
    }

    public function scanFilesystem ($username, $version) {
        $this->sftp->chdir('surveylog');
        $this->sftp->chdir($username);
        $this->sftp->chdir($version);
        $dirs = $this->sftp->rawlist();

        $fileNames = array();
        foreach ($dirs as $dir) {

            $fileNames[] = $dir['filename'];
        }
        return $fileNames;
    }
}