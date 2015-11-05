<?php

/**
 * Application bootstrap.
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initAutoload()
	{
		Zend_Loader::loadClass("Zend_Loader_Autoloader");
		$loader = Zend_Loader_Autoloader::getInstance();
		$loader->registerNamespace('My_');
		$loader->setFallbackAutoloader(true);
		$resourceLoader = new Zend_Loader_Autoloader_Resource(array(
			'basePath' => APPLICATION_PATH,
			'namespace' => '',
			'resourceTypes' => array(
				'acl' => array(
					'path' => 'acls/',
					'namespace' => 'Acl',
				),
				'form' => array(
					'path' => 'forms/',
					'namespace' => 'Form',
				),
				'model' => array(
					'path' => 'models/',
					'namespace' => 'Model'
				),
				'plugin' => array(
					'path' => 'plugins/',
					'namespace' => 'Application_Plugin',
				),
			),
		));
		$loader->pushAutoloader($resourceLoader);
		return $loader;
	}

	protected function _initIncludePath()
	{
		$rootDir = dirname(__DIR__);
		set_include_path(get_include_path()
			. PATH_SEPARATOR . $rootDir . '/application/models'
			. PATH_SEPARATOR . $rootDir . '/application/forms'
		);
	}

	protected function _initDoctype()
	{
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->doctype('HTML5');
	}

	protected function _initHelpers()
	{
		$view = $this->getResource('view');
		$prefix = 'My_View_Helper';
		$dir = APPLICATION_PATH . '/../library/My/View/Helper';
		$view->addHelperPath($dir, $prefix);
	}

	protected function _initRouter(array $options = [])
	{
		$this->bootstrap('FrontController');
		$frontController = $this->getResource('FrontController');
		$router = $frontController->getRouter();

		$router->addRoute(
			'login',
			new Zend_Controller_Router_Route('login', ['controller' => 'index', 'action' => 'default'])
		);

		$router->addRoute(
			'home',
			new Zend_Controller_Router_Route('home', ['controller' => 'home', 'action' => 'index'])
		);

		$router->addRoute(
			'logout',
			new Zend_Controller_Router_Route('logout', ['controller' => 'index', 'action' => 'logout'])
		);
	}

	protected function _initTranslate()
	{
		$translations = [
			'isEmpty' => 'Hodnota je povinná a nemůže být prázdná',
			'A record with the supplied identity could not be found.' => 'Neplatné uživatelské jméno',
			'Supplied credential is invalid.' => 'Neplatné heslo',
			'Please fill the login form' => 'Prosím vyplňte jméno a heslo',
			'Access Denied' => 'Přístup odepřen'
		];
		$translate = new Zend_Translate('array', $translations, 'cs');
		Zend_Registry::set('Zend_Translate', $translate);
	}
}
