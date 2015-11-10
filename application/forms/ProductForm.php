<?php

class ProductForm extends Zend_Form {

	public function init() {
		$this->setMethod(self::METHOD_POST);
		$this->setAttrib('class', 'form-signin');

		$name = $this->createElement('text', 'name');
		$name->setLabel('Název produktu');
		$name->addFilter('StringTrim');
		$name->setRequired(true);
		$name->setAttrib('class', 'form-control');
		$name->setAttrib('placeholder', 'Název produktu');
		$this->addElement($name);

		$unit = $this->createElement('text', 'unit');
		$unit->setLabel('Jednotka');
		$unit->addFilter('StringTrim');
		$unit->setRequired(true);
		$unit->setAttrib('class', 'form-control');
		$unit->setAttrib('placeholder', 'Jednotka');
		$this->addElement($unit);

		$price = $this->createElement('text', 'price');
		$price->setLabel('Cena za jednotku');
		$price->addFilter('StringTrim');
		$price->setRequired(true);
		$price->setAttrib('class', 'form-control');
		$price->setAttrib('placeholder', 'Cena za jednotku');
		$this->addElement($price);

		$this->addElement('submit', 'add', array(
			'ignore' => true,
			'class' => 'submit btn btn-lg btn-success btn-block',
			'label' => 'Přidat'
		));

	}

	public function setModifyMode() {
		$this->getElement('add')->setLabel('Upravit');
	}

}
