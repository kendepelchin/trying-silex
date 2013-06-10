<?php

namespace Vinyl\Services\Util;

use Vinyl\User\Entity\User;

/**
 * The services base class
 *
 * @author  Ken Depelchin <ken.depelchin@gmail.com>
 */
class Service {
	protected $_config = array();
	protected $_debug = false;

	public function __construct(User $user, array $config, $debug = false) {

		// todo only get the correct configs based on debug
		$this->_config = $config;
		$this->_debug = $debug;
	}
}
