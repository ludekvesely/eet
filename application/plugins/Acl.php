<?php

/**
 * ACL plugin.
 */
class Application_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
	/**
	 * @param Zend_Controller_Request_Abstract $request
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$options = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getApplication()->getOptions();
		$config = new Zend_Config($options);
		$acl = new My_Acl($config);
		$role = 'guest';
		if (Zend_Auth::getInstance()->hasIdentity()) {
			$role = 'admin';
		}
		$controller = $request->getControllerName();
		$action = $request->getActionName();
		$resource = $controller;
		$privilege = $action;
		if (!$acl->has($resource)) {
			$resource = null;
		}
		if (is_null($privilege)) {
			$privilege = 'index';
		}
		if (!$acl->isAllowed($role, $resource, $privilege)) {
			$flash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
			$flash->clearMessages();
			$flash->addMessage('Access Denied');
			$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
			$redirector->gotoSimpleAndExit('index', 'index');
		}
	}
}