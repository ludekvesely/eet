<?php

/**
 * Form with username and password.
 */
class LoginForm extends Zend_Form
{
	public function init()
	{
		$this->setMethod(self::METHOD_POST);
		$this->setAttrib('class', 'form-signin');

		$user = $this->createElement('text', 'username');
		$user->setLabel('Login');
		$user->addFilter('StringTrim');
		$user->setRequired(true);
		$user->setAttrib('class', 'form-control');
		$user->setAttrib('placeholder', 'Uživatelské jméno');
		$user->setDecorators([
			['ViewHelper', ['helper' => 'formText']], ['Label', ['class' => 'sr-only']]
		]);
		$this->addElement($user);

		$pass = $this->createElement('password', 'password');
		$pass->setLabel('Heslo');
		$pass->setRequired(true);
		$pass->setAttrib('class', 'form-control');
		$pass->setAttrib('placeholder', 'Heslo');
		$pass->setDecorators([
				['ViewHelper', ['helper' => 'formText']], ['Label', ['class' => 'sr-only']]
		]);
		$this->addElement($pass);

		$this->addElement('hidden', 'login', ['value' => 1]);

		$submit = $this->createElement('submit', 'Přihlásit');
		$submit->setAttrib('class', 'btn btn-lg btn-primary btn-block');
		$this->addElement($submit);
	}
}
