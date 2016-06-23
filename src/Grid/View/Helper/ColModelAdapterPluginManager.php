<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 26.05.16
 * Time: 23:06
 */

namespace JqGridBackend\Grid\View\Helper;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;
use Zend\Form\Element as FormElement;
use JqGridBackend\Exception as JqGridBackendException;
use Zend\ServiceManager\ServiceLocatorInterface;

class ColModelAdapterPluginManager extends AbstractPluginManager implements ColModelAdapterPluginManagerInterface
{
    const CONFIG_KEY = 'jqgrid_adapter_manager';
    /**
     * @var array
     */
    protected $adapterMapConfig;

    public function __construct(
        ServiceLocatorInterface $serviceManager,
        $configOrContainerInstance = null,
        array $v3config = [])
    {
        $this->setServiceLocator($serviceManager);
        parent::__construct($configOrContainerInstance, $v3config);
    }

    /**
     * Validate the plugin
     *
     * Checks that the filter loaded is either a valid callback or an instance
     * of FilterInterface.
     *
     * @param  mixed                      $plugin
     * @return void
     * @throws Exception\RuntimeException if invalid
     */
    public function validatePlugin($plugin) {
        if ($plugin instanceof ColModel\ColModelAdapter) {
            return;
        }

        throw new Exception\RuntimeException(sprintf(
            'Plugin of type %s is invalid; must be %s',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            ColModel\ColModelAdapter::class
        ));
    }
}