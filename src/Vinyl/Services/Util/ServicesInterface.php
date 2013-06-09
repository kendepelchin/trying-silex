<?php

namespace Vinyl\Services\Util;

/**
 * Interface which all services should implement
 *
 * @author  Ken Depelchin <ken.depelchin@gmail.com>
 */
interface ServicesInterface {
	public function connect();
	public function authenticate();
}
