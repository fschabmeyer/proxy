<?php

namespace proxy\logger;

class Logger {

    private $filename;
    private $loglevels = [];

    public function __construct(array $loglevels,$file='proxy.log') {
        $this->filename= $file;
        $this->loglevels =$loglevels;
    }

    public function log($tag, $text, $loglevel='info'){
        if (array_search($loglevel,$this->loglevels)) {
            $line = date("d.m.Y H:i:s") . ' TAG:' . $tag . ' MESSAGE:' . $text . "\n";
            file_put_contents($this->filename, $line, FILE_APPEND);
    }
    }
}