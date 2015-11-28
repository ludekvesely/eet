<?php

class ProductController extends Zend_Controller_Action {

	public function indexAction()
	{
		$user = My_Model::get('Users')->getUser();
		$this->view->title = 'Seznam produktů';
		$this->view->products = $user->getProducts();
	}

	public function editAction()
	{
		$product = null;

		$productId = $this->_request->getParam('id');
		if (!empty($productId))
		{
			$product = My_Model::get('Products')->findByIdfindById($productId);
			if ($product === null) {
				$this->_helper->redirector->gotoRoute(array('controller' => 'product',
					'action' => 'index'),
					'default',
					true);
			}
		}

		$this->view->productId = $productId;

		$form = new ProductForm();
		$form->setAction($this->_helper->url->url());

		if ($product === null) {
			$this->view->title = 'Přidání produktu';

		} else {
			$this->view->title = 'Úprava produktu';
			$form->setModifyMode();
		}

		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				$formValues = $form->getValues();

				if ($product === null) {
					$product = My_Model::get('Products')->createRow();
					$product->setUserId(My_Model::get('Users')->getUser()->getId());
				}

				if (!$formValues['stored']) {
					$formValues['count'] = 0;
				}

				$product->updateFromArray($formValues);

				$this->_helper->flashMessenger->addMessage("Změny byly uloženy.");

				$this->_helper->redirector->gotoRoute(array('controller' => 'product',
					'action' => 'index'),
					'default',
					true);
			}
		} else {
			if ($product !== null) {
				$form->populate($product->toArray());
			}
		}

		$this->view->form = $form;
	}

	public function deleteAction()
	{
		if ($this->_request->isPost()) {
			$productId = $this->_getParam('id');

			if (!empty($productId)) {
				$product = My_Model::get('Products')->getById($productId);

				if ($product) {
					$product->setArchivated(true);
					$product->save();
					$this->_helper->flashMessenger->addMessage("Produkt byl odstraněn.");
				}
			}

			$this->_helper->redirector->gotoRoute(array('controller' => 'product',
				'action' => 'index'),
				'default',
				true);
		}
	}
}
