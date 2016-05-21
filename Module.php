<?php
namespace JqGridBackend;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use JqGridBackend\Grid\View\Helper\Grid as GridHelper;
use JqGridBackend\Grid\View\Helper\ColModel as ColModel;

use JqGridBackend\Grid\View\Helper\ColModel\TextAdapter;
use JqGridBackend\Grid\View\Helper\ColModel\SelectAdapter;
use Zend\ServiceManager\ServiceLocatorInterface;
use JqGridBackend\Grid\View\Helper\ColModel\ColModelAdapterFactory;
use JqGridBackend\Grid\View\Helper\ColModel\ColModelAdapterFactoryInterface;
use JqGridBackend\Exception\InvalidArgumentException;

class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface
{
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
	
	public function getServiceConfig() {
		return [
			'factories' => [
                ColModelAdapterFactoryInterface::class => function($serviceManager) {
                    $config = $serviceManager->get('config');
                    if (array_key_exists('JqGridBackend', $config) == false) {
                        throw new InvalidArgumentException('missing config section JqGridBackend');
                    }
                    $ret = new ColModelAdapterFactory($serviceManager, $config['JqGridBackend']);
                    return $ret;
                },
                TextAdapter::class => function ($serviceManager) {
                    return new TextAdapter();
                },
                SelectAdapter::class => function ($serviceManager) {
                    return new SelectAdapter();
                },
            ]
		];
	}

}
