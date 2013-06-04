<?php

namespace Vinyl\User\Controller;

use Vinyl\Core\Controller\Form;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterForm extends Form {
	public function __construct($formFactory) {
		parent::__construct($formFactory);
	}

	public function build() {
		$this->form = $this->formFactory->createBuilder('form')
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
	}
}
