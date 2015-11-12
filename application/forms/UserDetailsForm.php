<?php

class UserDetailsForm extends Zend_Form {

	public function init() {
		$this->setMethod(self::METHOD_POST);
		$this->setAttrib('class', 'form');

		$firstname = $this->createElement('text', 'firstname');
		$firstname->setLabel('Jméno');
		$firstname->setAttrib('class', 'form-control');
		$firstname->setAttrib('placeholder', 'Jméno');
		$this->addElement($firstname);

		$surname = $this->createElement('text', 'lastname');
		$surname->setLabel('Příjmení');
		$surname->setAttrib('class', 'form-control');
		$surname->setAttrib('placeholder', 'Příjmení');
		$this->addElement($surname);

		$address = $this->createElement('text', 'address');
		$address->setLabel('Adresa');
		$address->addFilter('StringTrim');
		$address->setAttrib('class', 'form-control');
		$address->setAttrib('placeholder', 'Adresa');
		$this->addElement($address);

		$children = $this->createElement('text', 'children');
		$children->setLabel('Počet dětí');
		$children->addFilter('StringTrim');
		$children->setAttrib('class', 'form-control');
		$children->setAttrib('placeholder', 'Počet dětí');
		$this->addElement($children);

		$this->addElement('select', 'costs', array(
			'label' => 'Náklady',
			'class' => 'form-control',
			'filters' => array('StringTrim'),
			'multiOptions' => [
				'Přímé',
				'Paušální 30%',
				'Paušální 40%',
				'Paušální 60%',
				'Paušální 80%',
			],
		));

		$this->addElement('radio', 'tax_payer', array(
			'label' => 'Plátce DPH',
			'class' => 'form-control',
			'multiOptions' => [
        		'1' => 'Ano',
        		'0' => ' Ne',
    		],
		));


		$this->addElement('submit', 'add', array(
			'ignore' => true,
			'class' => 'submit btn btn-lg btn-success btn-block',
			'label' => 'Upravit'
		));

	}

}
