<?php

/**
 * Default page when is user logged in.
 */
class HomeController extends Zend_Controller_Action
{

	public function indexAction()
	{
		$this->view->title = sprintf('Vítej uživateli %s', Zend_Auth::getInstance()->getIdentity());
	}
}
