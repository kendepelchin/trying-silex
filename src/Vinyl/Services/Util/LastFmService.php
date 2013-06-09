<?php

namespace Vinyl\Services\Util;

use Vinyl\Services\Util\ServicesInterface;
use Vinyl\User\Entity\User;

class LastFmService implements ServicesInterface {
// 	$signature = 'api_key' . APIKEY . 'method' . $method . 'token' . $token . SECRETKEY;
// 	$hashedSig = md5($signature);
	public function __construct(User $user) {

	}
}
