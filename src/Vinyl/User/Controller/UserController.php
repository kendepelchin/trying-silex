<?php

namespace Vinyl\User\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Vinyl\Debug\Util\Debug;
use Vinyl\Core\Controller\CoreController;
use Vinyl\User\Entity\User;
use Vinyl\User\Repository\Model;

use Vinyl\User\Controller\RegisterForm;

class UserController extends CoreController {

	protected function getRoutes(ControllerCollection $controllers) {
		$controllers->get('/register', array($this, 'register'))->method('POST');
		$controllers->get('/register', array($this, 'register'))->bind('register');
		$controllers->get('/login', array($this, 'login'))->bind('login');
		$controllers->get('/logout', array($this, 'login'))->bind('logout');
		return $controllers;
	}

	public function register(Request $request) {
		$form = new RegisterForm($this->getFormFactory());
		// $form = $this->getFormFactory()->createBuilder('form')
		//     ->add('email', 'email', array(
		//         'constraints' => array(
		//         	new Assert\Email(),
		//         	new Assert\NotBlank()
	 //        	),
	 //        	'attr' => array('placeholder' => 'Your email')
		//     ))
		//     ->add('password', 'password', array(
		//         'constraints' => array(
		//             new Assert\NotBlank(),
		//             new Assert\Length(array('max' => 30))
		//         )
		//     ))->getForm();

	    if ('POST' == $request->getMethod()) {
	        $form->bind($request);

	        if ($form->isValid()) {
	            $data = $form->getData();

	            $user = array();
	            $user['username'] = $data['email'];
	            $user['roles'] = 'ROLE_USER';
	            $user['password'] = $this->app['security.encoder.digest']->encodePassword($data['password'], "");

	            $exists = $this->app['users']->findUser($data['email']);

	            if (!$exists) {
	            	$result = $this->app['users']->insert($user);
	            }

	            // redirect somewhere
	            // return $app->redirect('');
	        }
	    }

	    // display the form
	    return $this->render('User/Views/register.twig', array(
	    	'form' => $form->getForm()->createView())
    	);
	}

	public function login(Request $request) {
        return $this->getTwig()->render('Home/Views/login.twig', array(
            'error'         => $this->app['security.last_error']($request),
            'last_username' => $this->app['session']->get('_security.last_username'),
        ));
    }

    public function logout(Request $request) {
    	return $this->getTwig()->render('Home/Views/logout.twig', array(

		));
    }
}
