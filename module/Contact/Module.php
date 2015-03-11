<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Contact;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

use Contact\Model\Contact;
use Contact\Model\ContactTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface{
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

	public function getServiceConfig()
	{
		return array(
			'factories' => array(
				'Contact\Model\ContactTable' => function($sm) {
					$tableGateway = $sm->get('ContactTableGateway');
					$table = new ContactTable($tableGateway);
					return $table;
				},
				'ContactTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Contact());
					return new TableGateway('contact', $dbAdapter, null, $resultSetPrototype);
				},
			),
		);
	}

	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}

}