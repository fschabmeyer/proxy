<?php
namespace proxy\http;

use proxy\app;

class ini
{
    private $ini = array();
    private $urls = array();

    private function getIniFile($filename) {
        return parse_ini_file($filename, true);
    }

    public function __construct($configfile) {
        $this->ini = $this->getIniFile($configfile);
    }

    public function getUrls($targets) {
        foreach ($targets as $value) {
            foreach ($this->ini[$value] as $targetsection) {
                foreach ($targetsection as $key => $url) {
                    $urls[$value.' '.$key] = $url;
                }
            }
        }
        app::$logger->log("URLS",print_r($urls, 1),'debug');
        return $urls;
    }
}