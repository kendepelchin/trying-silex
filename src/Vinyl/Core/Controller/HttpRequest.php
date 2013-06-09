<?php

class Utils_HttpRequestException extends Exception {
}

class Utils_HttpRequest {

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    const RESPONSE_CONTENT_TYPE_JSON = 'json';

    private $_url;
    private $_method;
    private $_params;
    private $_options;

    private $_startTime;
    private $_endTime;
    private $_debugInfo = array();
    private $_responseHeaders;
    private $_result;
    private $_curlError;
    private $_headers = false;

    private static $_defaultOptions = array(
        'timeout' => 20,
        'headers' => array(),
        'includeHeader' => false,
        'customRequest' => null,
        'paramsAsString' => true,
        'userAgent' => "Mozilla/5.0 (Windows; U; Windows NT 6.1; en; rv:1.9.1.5) Gecko/20091102 Firefox/3.5.5",
        'responseContentType' => null,
    );

    public function __construct($url, $method = self::METHOD_GET, $params = array(), array $options = array()) {
        $this->_url = $url;
        $this->_method = $method;
        $this->_params = $params;
        $this->_options = Utils_Array::extend($options, self::$_defaultOptions);
    }

    public function setResponseContentType($value) {
        $this->_options['responseContentType'] = $value;
    }

    private function _executeCurl() {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_USERAGENT, $this->_options['userAgent']);

        curl_setopt($ch, CURLOPT_URL, $this->_url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_REFERER, "http://bot.engagor.com");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->_options['timeout']);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->_options['timeout']);

        if ($this->_options['includeHeader'] === true) {
            curl_setopt($ch, CURLOPT_HEADER, true);
        }
        if ($this->_options['userpwd']) {
            curl_setopt($ch, CURLOPT_USERPWD, $this->_options['userpwd']);
        }
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        $header[] = "Accept-Language: en";

        if ($this->_options['customRequest']) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->_options['customRequest']);
        }

        // method specific options
        if ($this->_method === 'PUT') {
            $post = (is_array($this->_params)) ? http_build_query($this->_params) : $this->_params;
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            $header[] = 'Content-Length: ' . strlen($post);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        elseif ($this->_method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }
        elseif ($this->_method === 'GET') {
            // do nothing
        }
        elseif ($this->_method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            if (is_string($this->_params)) {
                $header[] = 'Content-Length: ' . strlen($this->_params);
            }
            if (!empty($this->_params)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_params);
            }
        }

        $this->_options['headers'] = $header;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $this->_startTime = microtime(true);
        $this->_content = curl_exec($ch);
        $this->_responseHeaders = curl_getinfo($ch);
        $this->_curlError = curl_error($ch);
        curl_close($ch);
        $this->_endTime = microtime(true);
    }

    private function _processResponse() {
        // check result
        if ($this->_curlError) {
            throw new Exception("Curl Error: " . $this->_curlError, 500);
        } else {
            if ($this->_options['includeHeader']) {
                $header = mb_substr($this->_content, 0, $this->_responseHeaders['header_size']);
                $this->_content = mb_substr($this->_content, $this->_responseHeaders['header_size']);
            }
            $ret = array(
                "error" => $this->_responseHeaders['http_code'] != 200,
                "status" => $this->_responseHeaders['http_code'],
                "content" => $this->_content,
                "response" => $this->_responseHeaders
            );
            if ($this->_options['includeHeader']) {
                $header = explode("\n", $header);
                $newHeader = array();
                foreach($header as $str) {
                    $str = trim($str);
                    if ($str) {
                        list($k, $v) = explode(':', $str, 2);
                        if ($k && $v)
                            $newHeader[trim($k)] = trim($v);
                    }
                }
                $ret['header'] = $newHeader;
                $this->_headers = $newHeader;
            }
        }

        if ($this->_options['responseContentType'] === self::RESPONSE_CONTENT_TYPE_JSON) {
            $this->_content = Utils_Json::decode($this->_content, true);
        }

        // debugging
        if (Debug::isWebDebug()) {
            $debug = array();
            $debug['response']['headers'] = $this->_responseHeaders;
            $debug['response']['content'] = $this->_content;
            $debug['request'] = array(
                'url' => $this->_url,
                'method' => $this->_method,
                'params' => $this->_params,
            );
            $debug['options'] = $this->_options;
            Debug::info($this->_method . " " . $this->_url . " (" . round($this->_endTime - $this->_startTime, 3) . "s)", $debug, 'url', 'fetch');
        }

        // done
        $this->_result = $ret;
    }

    public function execute() {
        $this->_executeCurl();
        $this->_processResponse();
        return $this->_content;
    }

    public function delete() {
        $this->_method = self::METHOD_DELETE;
        return $this->execute();
    }

    public function put() {
        $this->_method = self::METHOD_PUT;
        return $this->execute();
    }

    public function get() {
        $this->_method = self::METHOD_GET;
        return $this->execute();
    }

    public function post($params = array()) {
        $this->_method = self::METHOD_POST;
        $this->_params = $params;
        return $this->execute();
    }

    public function getResponseHeaders() {
        return $this->_responseHeaders;
    }

    public function getHeaders() {
        return $this->_headers;
    }

    public function getContent() {
        return $this->_content;
    }

    public function getResponseHttpStatusCode() {
        return $this->_responseHeaders['http_code'];
    }

    public function getCurlError() {
        return $this->_curlError;
    }

    public static function getHttpCodeText($code) {
        $code = (int) $code;
        switch ($code) {
            case 0: $text = "Network Error"; break;
            case 100: $text = 'Continue'; break;
            case 101: $text = 'Switching Protocols'; break;
            case 200: $text = 'OK'; break;
            case 201: $text = 'Created'; break;
            case 202: $text = 'Accepted'; break;
            case 203: $text = 'Non-Authoritative Information'; break;
            case 204: $text = 'No Content'; break;
            case 205: $text = 'Reset Content'; break;
            case 206: $text = 'Partial Content'; break;
            case 300: $text = 'Multiple Choices'; break;
            case 301: $text = 'Moved Permanently'; break;
            case 302: $text = 'Moved Temporarily'; break;
            case 303: $text = 'See Other'; break;
            case 304: $text = 'Not Modified'; break;
            case 305: $text = 'Use Proxy'; break;
            case 400: $text = 'Bad Request'; break;
            case 401: $text = 'Unauthorized'; break;
            case 402: $text = 'Payment Required'; break;
            case 403: $text = 'Forbidden'; break;
            case 404: $text = 'Not Found'; break;
            case 405: $text = 'Method Not Allowed'; break;
            case 406: $text = 'Not Acceptable'; break;
            case 407: $text = 'Proxy Authentication Required'; break;
            case 408: $text = 'Request Time-out'; break;
            case 409: $text = 'Conflict'; break;
            case 410: $text = 'Gone'; break;
            case 411: $text = 'Length Required'; break;
            case 412: $text = 'Precondition Failed'; break;
            case 413: $text = 'Request Entity Too Large'; break;
            case 414: $text = 'Request-URI Too Large'; break;
            case 415: $text = 'Unsupported Media Type'; break;
            case 500: $text = 'Internal Server Error'; break;
            case 501: $text = 'Not Implemented'; break;
            case 502: $text = 'Bad Gateway'; break;
            case 503: $text = 'Service Unavailable'; break;
            case 504: $text = 'Gateway Time-out'; break;
            case 505: $text = 'HTTP Version not supported'; break;
            default:
                $text = 'Unknown Error';
            break;
        }
        return $text;
    }
}
