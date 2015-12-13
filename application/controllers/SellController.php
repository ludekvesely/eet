<?php

class SellController extends Zend_Controller_Action {

	public function indexAction()
	{
		$user = My_Model::get('Users')->getUser();
		$this->view->title = 'Prodej';
		$this->view->products = $user->getProducts();
		$this->view->saleId = $this->_getParam('saleId');
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

				if ($this->_getParam('saleId')) {
					$saleId = $this->_getParam('saleId');

				} else {


					$sale = My_Model::get('Sales')->fetchAll([
						'active = ?' => true,
						'user_id = ?' => My_Model::get('Users')->getUser()->getId(),
					]);

					if (count($sale) !== 0) {
						$saleId = $sale[0]->getId();

					} else {
						$saleId = My_Model::get('Sales')->insert([
								'user_id' => $userId,
								'date' => (new \DateTime)->format('Y-m-d h:i:s')
						]);
					}
				}

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

			$this->_helper->redirector->gotoRoute(
				array('controller' => 'sell', 'action' => 'receipt', 'saleId' => $saleId),
				'default',
				true);

		}

		$this->view->title = 'Množství produktu';
		$this->view->form = $form;
	}

	public function receiptAction()
	{
		$this->view->title = 'Prodej';
		$this->view->products = My_Model::get('Sales')->getProducts($this->_getParam('saleId'));
		$this->view->saleId = $this->_getParam('saleId');
	}

	public function removeAction()
	{
		$saleProduct = My_Model::get('SalesProducts')->fetchAll([
			'id = ?' => $this->_getParam('salesProductsId'),
		])[0];

		$product = My_Model::get('Products')->fetchAll([
			'id = ?' => $saleProduct->getProductsId(),
		])[0];

		$product->setCount($product->getCount() + $saleProduct->getAmount());
		$product->save();

		$table = My_Model::get('SalesProducts');
		$where = $table->getAdapter()->quoteInto('id = ?', $this->_getParam('salesProductsId'));
		$table->delete($where);

		$saleProducts = My_Model::get('SalesProducts')->fetchAll([
			'sales_id = ?' => $this->_getParam('saleId'),
		]);

		if (count($saleProducts) === 0) {
			$this->_helper->flashMessenger->addMessage('Na účtence není žádný produkt. Zvolte prosím jiný.');
			$this->_helper->redirector->gotoRoute(
				array('controller' => 'sell', 'action' => 'index', 'saleId' => $this->_getParam('saleId')),
				'default',
				true);
		}

		$this->_helper->redirector->gotoRoute(
			array('controller' => 'sell', 'action' => 'receipt', 'saleId' => $this->_getParam('saleId')),
			'default',
			true);
	}

	public function cancelAction()
	{
		$saleProducts = My_Model::get('SalesProducts')->fetchAll([
			'sales_id = ?' => $this->_getParam('saleId'),
		]);

		foreach ($saleProducts as $saleProduct) {
			$product = My_Model::get('Products')->fetchAll([
				'id = ?' => $saleProduct->getProductsId(),
			])[0];
			$product->setCount($product->getCount() + $saleProduct->getAmount());
			$product->save();

			$table = My_Model::get('SalesProducts');
			$where = $table->getAdapter()->quoteInto('id = ?', $saleProduct->getId());
			$table->delete($where);
		}

		$table = My_Model::get('Sales');
		$where = $table->getAdapter()->quoteInto('id = ?', $this->_getParam('saleId'));
		$table->delete($where);

		$this->_helper->flashMessenger->addMessage('Prodej byl zrušen');
		$this->_helper->redirector->gotoRoute(
			array('controller' => 'sell', 'action' => 'index'),
			'default',
			true);
	}

	public function doneAction()
	{
		$this->view->title = 'Paragon';

		$sale = My_Model::get('Sales')->fetchAll([
			'id = ?' => $this->_getParam('saleId'),
		])[0];
		$sale->setActive(0);
		$sale->save();

		$this->view->products = My_Model::get('Sales')->getProducts($this->_getParam('saleId'));
	}

	public function soldListAction()
	{
		$user = My_Model::get('Users')->getUser();
		$this->view->title = 'Vystavené paragony';
		$this->view->sales = My_Model::get('Sales')->fetchAll([
			'user_id = ?' => $user->getId(),
			'active = ?' => false,
		]);

	}

	public function soldAction()
	{
		$sale = null;
		$saleId = $this->_request->getParam('id');

		if (!empty($saleId))
		{
			$sale = My_Model::get('Sales')->findById($saleId);
			if ($sale === null) {
				$this->_helper->redirector->gotoRoute(array('controller' => 'sell',
						'action' => 'sold-list'),
						'default',
						true);
			}
		}
		else {
			$this->_helper->redirector->gotoRoute(array('controller' => 'sell',
					'action' => 'sold-list'),
					'default',
					true);
		}
		$this->view->title = 'Vystavený paragon';
		$this->view->sale = $sale;
		$this->view->products = My_Model::get('Sales')->getProducts($saleId);
		$this->view->store = My_Model::get('Stores')->fetchAll([
			'user_id = ?' => My_Model::get('Users')->getUser()->getId(),
		])[0];
	}

}
