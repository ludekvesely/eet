<?php

/**
 * Auth plugin.
 *
 * @see Zend_Auth_Adapter_DbTable
 * @see application.ini
 */
class Application_Plugin_DbAuth extends Zend_Controller_Plugin_Abstract
{
	/** @var string Password salt. */
	const SALT = 'daasnDQLd81iuhadi3SLQP5kniasCI6ISMdijaa4ijIJIma2kiJI271';

	/** @var array */
	private $_options;

	/** @var Zend_Controller_Action_Helper_Abstract */
	private $redirector;

	/** @var Zend_Auth */
	private $auth;

	public function __construct()
	{
		$this->redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
		$this->auth = Zend_Auth::getInstance();
	}

	/**
	 * Get value from config or throw exception.
	 *
	 * @param string $key
	 *
	 * @return mixed
	 *
	 * @throws Zend_Controller_Exception
	 */
	private function _getParam($key)
	{
		if (is_null($this->_options)) {
			$this->_options = Zend_Controller_Front::getInstance()
				->getParam('bootstrap')
				->getApplication()
				->getOptions();
		}
		if (!array_key_exists($key, $this->_options['auth'])) {
			throw new Zend_Controller_Exception("Param {auth.$key} not found in application.ini");
		} else {
			return $this->_options['auth'][$key];
		}
	}

	/**
	 * Get config value using _getParam method.
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function __get($key)
	{
		return $this->_getParam($key);
	}

	/**
	 * Parsre request, authorize and redirect.
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		if ($request->getParam('logout')) {
			$this->logout();
		} elseif ($request->getPost('login')) {
			$this->login($request);
		}
	}

	/**
	 * Log out.
	 */
	private function logout()
	{
		$this->auth->clearIdentity();
		$this->redirector->gotoSimpleAndExit($this->failedAction, $this->failedController);
	}

	/**
	 * Perform login.
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 */
	private function login(Zend_Controller_Request_Abstract $request)
	{
		$adapter = new Zend_Auth_Adapter_DbTable(
			$this->getDb(), $this->tableName, $this->identityColumn, $this->credentialColumn, $this->treatment
		);
		$form = new LoginForm;
		if (!$form->isValid($request->getPost())) {
			$this->redirectFailed(['Please fill the login form']);
		}
		$username = $form->getValue($this->loginField);
		$password = $form->getValue($this->passwordField) . self::SALT;
		$adapter->setIdentity($username);
		$adapter->setCredential($password);
		$result = $this->auth->authenticate($adapter);
		if ($this->auth->hasIdentity()) {
			$this->logLogin($request);
			$this->redirectSuccess();
		} else {
			$this->redirectFailed($result->getMessages());
		}
	}

	/**
	 * Login succeed -> redirect.
	 */
	private function redirectSuccess()
	{
		$this->redirector->gotoSimpleAndExit($this->successAction, $this->successController);
	}

	/**
	 * Login failed -> flash message + redirect.
	 *
	 * @param string[] $messages
	 */
	private function redirectFailed($messages)
	{
		$flash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
		$flash->clearMessages();
		foreach ($messages as $msg) {
			$flash->addMessage($msg);
		}
		$this->redirector->gotoSimpleAndExit($this->failedAction, $this->failedController, null, array('login-failed' => 1));
	}

	/**
	 * Store last login ifo into database.
	 *
	 * @param $request
	 *
	 * @throws Zend_Db_Adapter_Exception
	 */
	private function logLogin($request)
	{
		$identity = $this->auth->getIdentity();
		$this->getDb()->update($this->tableName, [
			'lognum' => new Zend_Db_Expr('lognum + 1'),
			'ip' => $request->getServer('REMOTE_ADDR'),
			'last_login' => new Zend_Db_Expr('NOW()'),
			'browser' => $request->getServer('HTTP_USER_AGENT')
		],
		$this->identityColumn . " = '$identity'");
	}

	/**
	 * @return Zend_Db_Adapter_Abstract
	 */
	private function getDb()
	{
		return Zend_Db_Table::getDefaultAdapter();
	}

}
