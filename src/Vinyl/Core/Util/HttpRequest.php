<?php

namespace Vinyl\Core\Util;

class HttpRequest {

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    public function __construct($url, $method = self::METHOD_GET, $params = array()) {
        $this->_url = $url;
        $this->_method = $method;
        $this->_params = $params;
    }

    public function execute() {
        $ch = curl_init();
        var_dump($this->_url);
        curl_setopt($ch, CURLOPT_URL, $this->_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $this->_content = curl_exec($ch);

        var_dump($this->_content);
        curl_close($ch);
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // curl_setopt($ch, CURLOPT_ENCODING, "");
        // curl_setopt($ch, CURLOPT_REFERER, "http://bot.engagor.com");
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->_options['timeout']);
        // curl_setopt($ch, CURLOPT_TIMEOUT, $this->_options['timeout']);

        // if ($this->_options['includeHeader'] === true) {
        //     curl_setopt($ch, CURLOPT_HEADER, true);
        // }
        // if ($this->_options['userpwd']) {
        //     curl_setopt($ch, CURLOPT_USERPWD, $this->_options['userpwd']);
        // }
        // curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        // $header[] = "Accept-Language: en";

        // if ($this->_options['customRequest']) {
        //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->_options['customRequest']);
        // }

        // // method specific options
        // if ($this->_method === 'PUT') {
        //     $post = (is_array($this->_params)) ? http_build_query($this->_params) : $this->_params;
        //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        //     $header[] = 'Content-Length: ' . strlen($post);
        //     curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        //     curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        // }
        // elseif ($this->_method === 'DELETE') {
        //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        // }
        // elseif ($this->_method === 'GET') {
        //     // do nothing
        // }
        // elseif ($this->_method === 'POST') {
        //     curl_setopt($ch, CURLOPT_POST, 1);
        //     if (is_string($this->_params)) {
        //         $header[] = 'Content-Length: ' . strlen($this->_params);
        //     }
        //     if (!empty($this->_params)) {
        //         curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_params);
        //     }
        // }

        // $this->_options['headers'] = $header;
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // $this->_startTime = microtime(true);
        // $this->_responseHeaders = curl_getinfo($ch);
        // $this->_curlError = curl_error($ch);
        // $this->_endTime = microtime(true);
    }
}
