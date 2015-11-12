<?php

class RegistrationController extends Zend_Controller_Action
{

	public function init()
	{
		$flash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
		$flash->clearMessages();
		$auth = Zend_Auth::getInstance();
		if($auth->hasIdentity()) {
			$this->_helper->redirector->gotoRoute(array('controller' => 'home',
				'action' => 'index'),
				'default',
				true);
		}
	}

	public function indexAction()
	{
		$this->view->title = 'Registrace do EET';

		$form = new RegistrationForm();
		$form->setAction($this->_helper->url->url());

		if ($this->_request->isPost()) {
			if ($form->isValid($this->_request->getPost())) {
				$formValues = $form->getValues();

				if($formValues['password'] != $formValues['confirmPassword']){
					$this->_helper->flashMessenger->addMessage('Zadaná hesla se neshodují.');
					$this->_helper->redirector->gotoRoute(array('controller' => 'registration',
						'action' => 'index'),
						'default',
						true);
				}

				$users = My_Model::get('Users');
				$username = $formValues['username'];

				if ($users->fetchRow([
						sprintf('username = "%s"', $username),
					]) !== null) {
					$this->_helper->flashMessenger->addMessage(sprintf('Uživatelské jméno "%s" je již zabrané.', $username));
					$this->_helper->redirector->gotoRoute(array('controller' => 'registration',
						'action' => 'index'),
						'default',
						true);
				}

				$password = sha1(sprintf('%s%s', $formValues['password'], Application_Plugin_DbAuth::SALT));

				$users->insert([
					'username' => $username,
					'password' => $password,
				]);

				$this->_helper->flashMessenger->addMessage('Registrace proběhla v pořádku. Nyní se můžete přihlásit.');

				$this->_helper->redirector->gotoRoute(array('controller' => 'index',
					'action' => 'index'),
					'default',
					true);
			}
		}

		$this->view->form = $form;
	}
}
