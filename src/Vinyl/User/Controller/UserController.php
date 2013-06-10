<?php

namespace Vinyl\User\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

use Vinyl\Core\Controller\CoreController;
use Vinyl\Debug\Util\Debug;
use Vinyl\User\Entity\User;
use Vinyl\User\Repository\UserModel;
use Vinyl\User\Controller\RegisterForm;
use Vinyl\Services\Util\LastFmService;

/**
 * Routing for our user controller
 *
 * @author  Ken Depelchin <ken.depelchin@gmail.com>
 */
class UserController extends CoreController {
	public function init(Request $request) {}

	protected function getRoutes(ControllerCollection $controllers) {
		$controllers->get('/register', array($this, 'register'))->method('POST');
		$controllers->get('/register', array($this, 'register'))->bind('register');
		$controllers->get('/login', array($this, 'login'))->bind('login');
		$controllers->get('/logout', array($this, 'logout'))->bind('logout');
		$controllers->get('/lastfm', array($this, 'lastfm'))->bind('lastfm');
		return $controllers;
	}

	public function register(Request $request) {
		$form = new RegisterForm($this->getFormFactory());

	    if ('POST' == $request->getMethod()) {
	        $form->bind($request);

	        if ($form->isValid()) {
	            $data = $form->getData();

	            $user = array();
	            $user['username'] = $data['email'];
	            $user['dateadd'] = date('Y-m-d H:i:s');
	            $user['roles'] = 'ROLE_USER';
	            $user['password'] = $this->app['security.encoder.digest']->encodePassword($data['password'], "");

	            $exists = $this->app['users']->findUser($data['email']);

	            if (!$exists) {
	            	$result = $this->app['users']->insert($user);

	            	if ($result) {
	            		$user = $this->app['users']->findUser($user['username']);
	            	}
	            	echo 'user created!';
	            }

	            else {
	            	echo 'user already exists.';
	            }

	            // redirect somewhere
	            return $this->app->redirect('login');
	        }
	    }

	    // display the form
	    return $this->render('user/register.twig', array(
	    	'form' => $form->getForm()->createView())
    	);
	}

	public function login(Request $request) {
        return $this->getTwig()->render('user/login.twig', array(
            'error'         => $this->app['security.last_error']($request),
            'last_username' => $this->app['session']->get('_security.last_username'),
        ));
    }

    public function logout(Request $request) {
    	return $this->getTwig()->render('home/logout.twig', array(

		));
    }

    public function lastfm(Request $request) {
		$service = new LastFmService($this->getUser(), $this->getServiceConfig('api.lastfm'), $this->app['debug']);

    	if ($request->get('token')) {
    		// do the call to get all the shanigans
    		Debug::dump($request->get('token'));
    		var_dump($this->getUser());
    		$service->call('auth.getSession');
    	}
    	else {
    		return $this->app->redirect($service->getRedirectUrl());
    	}

    	return $this->getTwig()->render('user/lastfm.twig', array());
    }
}
