<?php

class StoreController extends Zend_Controller_Action
{

	public function indexAction()
	{
		$this->view->title = 'Nastavení prodejního místa';

		$form = new StoreForm();
		$form->setAction($this->_helper->url->url());

		$store = My_Model::get('Users')->getUser()->getStore();

		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				$formValues = $form->getValues();

				if (!$store) {
					$store = My_Model::get('Stores')->createRow();
					$store->setUserId(My_Model::get('Users')->getUser()->getId());
				}
				$store->updateFromArray($formValues);

				$this->_helper->flashMessenger->addMessage("Změny byly uloženy.");

				$this->_helper->redirector->gotoRoute(array('controller' => 'store',
					'action' => 'index'),
					'default',
					true);
			}
		} else {
			if ($store) {
				$form->populate($store->toArray());
			}
		}

		$this->view->form = $form;
	}

}
