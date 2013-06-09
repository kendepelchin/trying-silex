<?php

namespace Vinyl\Home\Controller;

use Silex\ControllerCollection;
use Vinyl\Debug\Util\Debug;
use Vinyl\Core\Controller\CoreController;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends CoreController
{
    public function init(Request $request)
    {
        echo 'before';
    }

    protected function getRoutes(ControllerCollection $controllers)
    {
         // init
        $controllers->get('/', array($this, 'index'))->before(array($this, 'init'));

        $controllers->get('/', array($this, 'index'))->bind('homepage');
        $controllers->get('/login', array($this, 'login'))->bind('login');
        return $controllers;
    }

    public function index()
    {
        // $token = $this->getSecurity()->getToken();
        // Debug::dump($token);
        // var_dump($this->security);
        $user = $this->getUser();

        return $this->getTwig()->render(
            'home/index.twig'
        );
    }

    public function discogs($name) {
        Debug::dump($name);
        return $this->getTwig()->render(
            'home/index.twig'
        );
        // $service = new \Discogs\Service();
        // $label = $service->getArtist('DaftPunk');
        // va::dump($app);
        // var_dump($app->redirect('/'));
        // return $service;
    }
}
