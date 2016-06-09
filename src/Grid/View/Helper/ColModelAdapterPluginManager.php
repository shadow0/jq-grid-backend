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

class ColModelAdapterPluginManager extends AbstractPluginManager implements ColModelAdapterPluginManagerInterface
{
    const CONFIG_KEY = 'jqgrid_adapter_manager';
    /**
     * @var array
     */
    protected $adapterMapConfig;

    public function __construct($configOrContainerInstance = null, array $v3config = [])
    {
        parent::__construct($configOrContainerInstance, $v3config);
    }

//    /**
//     * @param FormElement $element
//     * @return ColModel\ColModelAdapter
//     * @thrown Exception\OutOfBoundsException
//     */
//    public function getAdapter(FormElement $element)
//    {
//        $adapterName = $this->getAdapterName($element);
//        $sm = $this->getServiceLocator();
//        /** @var ColModel\ColModelAdapter $ret */
//        $ret = $sm->get($adapterName);
//        return $ret;
//    }
//
//    /**
//     * @param FormElement $element
//     * @return string
//     * @thrown Exception\OutOfBoundsException
//     */
//    public function getAdapterName(FormElement $element)
//    {
//        $className = get_class($element);
//        $alias = null;
//        foreach ($this->getCanonicalNames() as $name => $cName) {
//            if (is_a($element, $name) == true) {
//                $alias = $name;
//            }
//        }
//        if ()
//
//
//        $adapterName = null;
//        foreach ($this->adapterMapConfig as $k => $v) {
//            if (is_a($element, $k) == true) {
//                $adapterName = $v;
//            }
//        }
//        if (!$adapterName) {
//            throw new JqGridBackendException\OutOfBoundsException("missing ColModelAdapter for class = ". get_class($element));
//        }
//        return $adapterName;
//    }

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