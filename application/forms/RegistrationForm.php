<?php

class RegistrationForm extends Zend_Form
{
	public function init()
	{
		$this->setMethod(self::METHOD_POST);
		$this->setAttrib('class', 'form');

		$user = $this->createElement('text', 'username');
		$user->setLabel('Uživatelské jméno');
		$user->addFilter('StringTrim');
		$user->setRequired(true);
		$user->setAttrib('class', 'form-control');
		$user->setAttrib('placeholder', 'Uživatelské jméno');
		$this->addElement($user);

		$pass = $this->createElement('password', 'password');
		$pass->setLabel('Heslo');
		$pass->setRequired(true);
		$pass->setAttrib('class', 'form-control');
		$pass->setAttrib('placeholder', 'Heslo');
		$this->addElement($pass);

		$confirmPass = $this->createElement('password', 'confirmPassword');
		$confirmPass->setLabel('Heslo znovu');
		$confirmPass->setRequired(true);
		$confirmPass->setAttrib('class', 'form-control');
		$confirmPass->setAttrib('placeholder', 'Heslo znovu');
		$this->addElement($confirmPass);

		$submit = $this->createElement('submit', 'Registrovat');
		$submit->setAttrib('class', 'btn btn-lg btn-primary btn-block');
		$this->addElement($submit);
	}
}
