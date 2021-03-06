<?php

namespace Album;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

use Album\Model\Album;
use Album\Model\AlbumTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;
use Album\Utility\AclService;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\ModuleEvent;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\I18n\Translator;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface {

// 	public function init(ModuleManager $mm) {
// 		$eventManager = $mm->getEventManager();
// 		$eventManager->attach('loadModules.post', array($this, 'postInit'), -1000);
// 	}
	
// 	public function postInit(ModuleEvent $e) {
// 		var_dump($e);
// 		exit;
// 	}
	
// 	public function onBootstrap(MvcEvent $mvcEvent) {
// 		$aclTest = new AlbumController();
		
// 		$eventManager = $mvcEvent->getApplication()->getEventManager();
// 		$eventManager->attach(MvcEvent::EVENT_ROUTE);
// 	}

public function onBootstrap(MvcEvent $e)
{
    $translator = $e->getApplication()->getServiceManager()->get('translator');
    $translator
        ->setLocale(\Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        ->setFallbackLocale('en_US');
}
	
	public function getAutoloaderConfig()
	{
		return array(
				'Zend\Loader\ClassMapAutoloader' => array(
						__DIR__ . '/autoload_classmap.php',
				),
				'Zend\Loader\StandardAutoloader' => array(
						'namespaces' => array(
								__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
						),
				),
		);
	}

	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}
	
	public function getServiceConfig()
	{
		return array(
				'factories' => array(
						'Album\Model\AlbumTable' =>  function($sm) {
							$tableGateway = $sm->get('AlbumTableGateway');
							$table = new AlbumTable($tableGateway);
							return $table;
						},
						'AlbumTableGateway' => function ($sm) {
							$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
							$resultSetPrototype = new ResultSet();
							$resultSetPrototype->setArrayObjectPrototype(new Album());
							return new TableGateway('album', $dbAdapter, null, $resultSetPrototype);
						},
						'AlbumAuth' => function($sm) {
							$auth = new AuthenticationService();
							$auth->setStorage(new Session());
							return $auth;
						},
						'AclService' => function ($sm) {
							$acl = new AclService();
							return $acl;
						},
				),
		);
	}
	
}