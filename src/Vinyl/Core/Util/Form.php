<?php

namespace Vinyl\Core\Util;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactory;

abstract class Form {

	protected $formFactory;

	protected $form;

	public function __construct(FormFactory $formFactory) {
		$this->formFactory = $formFactory;
		$this->build();
	}

	public function bind($request) {
		return $this->form->bind($request);
	}

	public function isValid() {
		return $this->form->isValid();
	}

	public function getData() {
		return $this->form->getData();
	}

	public function getForm() {
		return $this->form;
	}
}
