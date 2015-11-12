<?php

class UserController extends Zend_Controller_Action
{

	public function editAction()
	{
		$this->view->title = 'Nastavení osobních údajů';

		$form = new UserDetailsForm();
		$form->setAction($this->_helper->url->url());

		$user = My_Model::get('Users')->getUser();

		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				$formValues = $form->getValues();

				$user->updateFromArray($formValues);

				$this->_helper->flashMessenger->addMessage("Změny byly uloženy.");

				$this->_helper->redirector->gotoRoute(array('controller' => 'user',
					'action' => 'edit'),
					'default',
					true);
			}
		} else {
			$form->populate($user->toArray());
		}

		$this->view->form = $form;
	}

}
