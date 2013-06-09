<?php

namespace Vinyl\Admin\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Vinyl\Core\Controller\CoreController;
use Vinyl\Debug\Util\Debug;

class AdminController extends CoreController
{
     protected function getRoutes(ControllerCollection $controllers)
    {
        $controllers->get('/', array($this, 'admin'))->bind('admin');
        return $controllers;
    }

    public function admin()
    {
        $user = $this->getUser();
        var_dump($user);
        return $this->getTwig()->render('admin/admin.twig', array(

        ));
    }
}
