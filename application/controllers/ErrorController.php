<?php

/**
 * Handle errors.
 */
class ErrorController extends Zend_Controller_Action
{
	/**
	 * @throws
	 * @throws Zend_Controller_Response_Exception
	 */
	public function errorAction()
	{
		$this->view->signin = TRUE;
		$errors = $this->_getParam('error_handler');
		if (1 || APPLICATION_ENV == 'development') {
			throw $errors->exception;
		} else {
			switch ($errors->type) {
				case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
				case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
					$this->getResponse()->setHttpResponseCode(404);
					$this->render('404');
					break;
				default:
					$this->getResponse()->setHttpResponseCode(500);
					$this->render('500');
					break;
			}
		}
	}
}
