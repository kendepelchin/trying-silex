<?php

namespace Vinyl\Admin\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Vinyl\Debug\Util\Debug;

class AdminController implements ControllerProviderInterface
{
    public function init(Request $request, Application $app) 
    {

    }

    public function connect(Application $app)
    {
        // Debug::dump($this->app);
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        // init
        // $controllers->get('/', array($this, 'index'))->before(array($this, 'init'));

        // routing 
        $controllers->get('/', array($this, 'admin'));

        return $controllers;
    }

    public function admin(Application $app)
    {
        $token = $app['security']->getToken();
        $user = $token->getUser();
        var_dump($user);
        echo 'admin moatjen';
    }
}
