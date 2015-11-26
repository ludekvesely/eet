<?php

class WarehouseForm extends Zend_Form {

	public function init() {
		$this->setMethod(self::METHOD_POST);
		$this->setAttrib('class', 'form');

		$count = $this->createElement('text', 'count');
		$count->setLabel('Počet položek ve skladu');
		$count->setAttrib('class', 'form-control');
		$this->addElement($count);

		$this->addElement('submit', 'add', array(
			'ignore' => true,
			'class' => 'submit btn btn-lg btn-success btn-block',
			'label' => 'Upravit'
		));

	}
}
