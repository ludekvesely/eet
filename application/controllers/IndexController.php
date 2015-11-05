<?php

/**
 * Page with login form.
 */
class IndexController extends Zend_Controller_Action
{
	/**
	 * Show login form.
	 */
	public function indexAction()
	{
		$this->view->title = 'Přihlášení do EET';
		$this->view->loginform = new LoginForm;
		$this->view->signin = TRUE;
	}

	/**
	 * Logout and redirect to login form.
	 */
	public function logoutAction()
	{
		Zend_Auth::getInstance()->clearIdentity();
		$flash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
		$flash->clearMessages();
		$flash->addMessage('Byli jste úspěšně odhlášeni.');
		$this->_helper->redirector->gotoSimpleAndExit('index', 'index');
	}
}
