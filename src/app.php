<?php

namespace proxy;
use proxy\http;
use proxy\logger\Logger;

class app {

    /**
     * @var $logger Logger;
     */
    public static $logger;
    /**
     * @var array
     */
    private $loglevels;
    /**
     * @var string
     */
    private $configfile;

    /**
     * @param mixed $configfile
     */
    public function setConfigfile($configfile)
    {
        $this->configfile = $configfile;
    }

    /**
     * @param mixed $loglevels
     */
    public function setLoglevels($loglevels)
    {
        $this->loglevels = $loglevels;
    }

    public function __construct()
    {
    }

    public function checkAppSettings(){
        if (!count($this->loglevels)) {
            throw new \Exception("No loglevels selected.");
        }
        if (!$this->configfile) {
            throw new \Exception("No configfile selected.");
        }
    }

    public function run()
    {
        $this->checkAppSettings();
        self::$logger = new Logger($this->loglevels);
        $httpclient = new http\httpclient();
        $ini = new http\ini($this->configfile);

        self::$logger->log('POST',print_r($_POST, 1),'debug');
        self::$logger->log('GET',print_r($_GET, 1),'debug');
        self::$logger->log('HEADER',print_r(getallheaders(), 1),'debug');
        self::$logger->log('SERVER',print_r($_SERVER, 1),'debug');

        $targets = $httpclient->parseTargets();
        if (!count($targets)) {
            self::$logger->log('NoTargets',"No target selected.");
            throw new \Exception("No target selected.");
        }

        $targets_urls = $ini->getUrls($targets);
        if (!count($targets_urls)) {
            echo "No URL found.";
            self::$logger->log('NoURL',"No URL found.");
            throw new \Exception("No URL found.");
        }

        $postValues = $httpclient->parsePost();

        self::$logger->log('POSTVALUES', print_r($postValues, 1),'debug');
        $result = [];
        foreach ($targets_urls as $target => $url) {
            $result[$target] = $httpclient->post($url, $postValues);
        }

        self::$logger->log('RESULT', print_r($result, 1));
    }
}