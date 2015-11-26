<?php

class SellController extends Zend_Controller_Action {

	public function indexAction()
	{
		$user = My_Model::get('Users')->getUser();
		$this->view->title = 'Prodej';
		$this->view->products = $user->getProducts();
	}

	public function addAction()
	{
		$form = new SellForm();
		$this->view->title = 'Množství produktu';
		$this->view->form = $form;
	}

}
