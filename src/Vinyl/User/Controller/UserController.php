<?php

namespace Vinyl\User\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Vinyl\Debug\Util\Debug;
use Vinyl\Core\Controller\CoreController;

class UserController extends CoreController {

	protected function getRoutes(ControllerCollection $controllers) {
		$controllers->get('/register', array($this, 'register'))->method('POST');
		$controllers->get('/register', array($this, 'register'));
		return $controllers;
	}

	public function register(Request $request) {
		$form = $this->getFormFactory()->createBuilder('form')
		    ->add('email', 'email', array(
		        'constraints' => array(
		        	new Assert\Email(),
		        	new Assert\NotBlank()
	        	),
	        	'attr' => array('placeholder' => 'Your email')
		    ))
		    ->add('password', 'password', array(
		        'constraints' => array(
		            new Assert\NotBlank(),
		            new Assert\Length(array('max' => 30))
		        )
		    ))->getForm();

		         // 'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))

	    if ('POST' == $request->getMethod()) {
	        $form->bind($request);
	        Debug::dump($form->getData());
	        die();

	        if ($form->isValid()) {
	            $data = $form->getData();
	            // do something with the data

	            // redirect somewhere
	            // return $app->redirect('...');
	        }
	    }

	    // display the form
	    return $this->render('User/Views/register.twig', array(
	    	'form' => $form->createView())
    	);
	}
}
