<?php

namespace Vinyl\Services\Util;

use Vinyl\Services\Util\ServicesInterface;
use Vinyl\User\Entity\User;
use Vinyl\Services\Util\Service;
use Vinyl\Core\Util\HttpRequest;

class LastFmService extends Service implements ServicesInterface {
// 	$signature = 'api_key' . APIKEY . 'method' . $method . 'token' . $token . SECRETKEY;
// 	$hashedSig = md5($signature);

	public function __construct(User $user, array $config, $debug = false) {
		parent::__construct($user, $config, $debug);
	}

	public function connect() {

	}
	public function authenticate() {

	}

	public function buildSignature($url, $method) {
		return 'api_key' . $this->_config['debug']['consumer_key'] . 'method' . $method . 'token' . $token . $this->_config['debug']['secret_key'];
	}

	public function call($url, $method = 'GET') {
		$url = $this->_config['base'] . '?' . $url;
		$httpRequest = new HttpRequest($url);
		$result = $httpRequest->execute();
		var_dump($result);
	}

	public function getRedirectUrl() {
		return $this->_config['base'] . '?api_key=' . ($this->_debug ? $this->_config['debug']['consumer_key'] : $this->_config['app']['consumer_key']) . '&cb=' . $this->_config['redirect_url'] ;
	}
}
