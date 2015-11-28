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
		$form = new SellForm($this->_getParam('id'));

		$form->setAction($this->_helper->url->url());

		if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {

			try {

				$auth = Zend_Auth::getInstance();
				$select = My_Model::get('Users')->select()->where('username = ?', $auth->getIdentity());
				$userId = My_Model::get('Users')->fetchRow($select)->id;

				$saleId = My_Model::get('Sales')->insert([
					'user_id' => $userId,
					'date' => (new \DateTime)->format('Y-m-d h:i:s')
				]);

				$product = My_Model::get('Products')->find($this->_getParam('id'))->current();

				My_Model::get('SalesProducts')->insert([
					'sales_id' => $saleId,
					'products_id' => $product->id,
					'amount' => $form->getValue('amount'),
					'unit_price' => $product->price
				]);

				My_Model::get('Products')->update(
					array('count' => $product->count - $form->getValue('amount')),
					array('id = ' . $product->id)
				);

			} catch (Exception $e) {

				$this->_helper->flashMessenger->addMessage('Chyba: ' . $e->getMessage());

			}

			$this->_helper->redirector->gotoRoute(array('controller' => 'receipt',
				'action' => 'index'),
				'default',
				true);

		}

		$this->view->title = 'Množství produktu';
		$this->view->form = $form;
	}

}
