<?php
namespace proxy\http;

use proxy\app;

class httpclient
{

    private $GETKey = array();

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * httpclient constructor.
     */
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
        $this->GETKey = $_GET;
        app::$logger->log("GET",print_r($_GET, 1),'debug');
    }

    /**
     * @param $url
     * @param $keyvalues
     * @return array
     */
    public function post($url, $keyvalues) {
        $res = $this->client->request('POST', $url, ['multipart' => $keyvalues]);
        app::$logger->log("REQUEST_RETURN",print_r($res, 1),'debug');
        return [
            'state' => ($res->getStatusCode()==200) ? true : false,
            'header' => $res->getHeaders(),
            'statuscode' => $res->getStatusCode(),
            'url' => $url,
        ];
    }

    /**
     * @return array
     */
    public function parsePost() {
        $keyvalues = [];
        foreach ($_POST as $key => $value) {
            $keyvalues[] = ['name' => $key,
                'contents' => $value];
        }
        app::$logger->log("KEYVALUES",print_r($keyvalues, 1),'debug');
        return $keyvalues;
    }

    /**
     * @return array
     */
    public function parseTargets() {
        $targets = [];
        foreach ($this->GETKey['_TARGET'] as $value) {
            $targets[] = $value;
        }
        app::$logger->log("TARGET",print_r($targets, 1),'debug');
        return $targets;
    }
}