<?php

class WarehouseController extends Zend_Controller_Action {

	public function indexAction()
	{
		$this->view->title = 'Sklad';
		$this->view->products = My_Model::get('Products')->findStored();
	}

	public function editAction()
	{
		$product = null;

		$productId = $this->_request->getParam('id');
		if (!empty($productId))
		{
			$product = My_Model::get('Products')->findById($productId);
			if ($product === null || !$product->getStored()) {
				$this->_helper->redirector->gotoRoute(array('controller' => 'warehouse',
						'action' => 'index'),
						'default',
						true);
			}
		} else {
			$this->_helper->redirector->gotoRoute(array('controller' => 'warehouse',
					'action' => 'index'),
					'default',
					true);
		}

		$form = new WarehouseForm();
		$form->setAction($this->_helper->url->url());

		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				$formValues = $form->getValues();
				$count = $formValues['count'];

				if (!$this->is_digits($count)) {
					$this->_helper->flashMessenger->addMessage('Neplatná hodnota');

					$this->_helper->redirector->gotoRoute(array(
							'controller' => 'warehouse',
							'action' => 'edit',
							'id' => $productId,
					),
							'default',
							true);
				}

				$product->updateFromArray($formValues);

				$this->_helper->flashMessenger->addMessage('Změny byly uloženy.');

				$this->_helper->redirector->gotoRoute(array('controller' => 'warehouse',
						'action' => 'index'),
						'default',
						true);
			}
		} else {
			$form->populate($product->toArray());
		}

		$this->view->title = 'Úprava množství';
		$this->view->form = $form;
	}

	private function is_digits($element) {
		return !preg_match ("/[^0-9]/", $element);
	}

}
