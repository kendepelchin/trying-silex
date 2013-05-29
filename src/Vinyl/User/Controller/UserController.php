<?php

namespace Vinyl\User\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class UserController implements ControllerProviderInterface {

	public function connect(Application $app) {
		$controllers = $app['controllers_factory'];

		$controllers->get('/', array($this, 'overview'));
		$controllers->get('/{id}', array($this, 'detail'))->assert('id', '\d+');
		$controllers->get('/{id}/links', array($this, 'links'))->assert('id', '\d+');
		return $controllers;
	}
}
