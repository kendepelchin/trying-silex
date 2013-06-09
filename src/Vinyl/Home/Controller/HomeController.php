<?php

namespace Vinyl\Home\Controller;

use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;

use Vinyl\Core\Controller\CoreController;
use Vinyl\Debug\Util\Debug;

/**
 * Routing for our home controller
 *
 * @author  Ken Depelchin <ken.depelchin@gmail.com>
 */
class HomeController extends CoreController {
    public function init(Request $request) {}

    protected function getRoutes(ControllerCollection $controllers) {
        $controllers->get('/', array($this, 'index'))->before(array($this, 'init'));
        $controllers->get('/', array($this, 'index'))->bind('homepage');
        $controllers->get('/login', array($this, 'login'))->bind('login');
        return $controllers;
    }

    public function index() {
        $user = $this->getUser();

        return $this->getTwig()->render(
            'home/index.twig'
        );
    }
}
