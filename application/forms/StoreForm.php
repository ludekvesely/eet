<?php

class StoreForm extends Zend_Form {

	public function init() {
		$this->setMethod(self::METHOD_POST);
		$this->setAttrib('class', 'form');

		$name = $this->createElement('text', 'name');
		$name->setLabel('Název');
		$name->addFilter('StringTrim');
		$name->setRequired(true);
		$name->setAttrib('class', 'form-control');
		$name->setAttrib('placeholder', 'Název');
		$this->addElement($name);

		$address = $this->createElement('text', 'address');
		$address->setLabel('Adresa');
		$address->addFilter('StringTrim');
		$address->setRequired(true);
		$address->setAttrib('class', 'form-control');
		$address->setAttrib('placeholder', 'Adresa');
		$this->addElement($address);

		$identificationNumber = $this->createElement('text', 'identification_number');
		$identificationNumber->setLabel('IČO');
		$identificationNumber->addFilter('StringTrim');
		$identificationNumber->setRequired(true);
		$identificationNumber->setAttrib('class', 'form-control');
		$identificationNumber->setAttrib('placeholder', 'IČO');
		$this->addElement($identificationNumber);

		$this->addElement('submit', 'add', array(
			'ignore' => true,
			'class' => 'submit btn btn-lg btn-success btn-block',
			'label' => 'Upravit'
		));

	}

}
