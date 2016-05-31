<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 28.04.16
 * Time: 1:11
 */
namespace JqGridBackend\Grid\View\Helper\ColModel;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use JqGridBackend\Exception;
use Zend\Form\Element as FormElement;

/**
 * Class ColModelAdapterFactory
 * @depricated земенен на плагин менеджер
 * @package JqGridBackend\Grid\View\Helper\ColModel
 */
//class ColModelAdapterFactory// implements ServiceLocatorAwareInterface
//{
//    protected $adapterMapConfig;
//
//    use ServiceLocatorAwareTrait;
//
//    /**
//     * @param ServiceLocatorInterface $serviceManager
//     * @param $config
//     */
//    public function __construct(ServiceLocatorInterface $serviceManager, $config)
//    {
//        if (array_key_exists('adapterMap', $config) == false) {
//          throw new Exception\InvalidArgumentException('missing "adapterMap" section');
//        }
//        $this->adapterMapConfig = $config['adapterMap'];
//        $this->setServiceLocator($serviceManager);
//    }
//
//    /**
//     * @param FormElement $element
//     * @return ColModelAdapter
//     * @thrown Exception\OutOfBoundsException
//     */
//    public function getAdapter(FormElement $element)
//    {
//        $adapterName = $this->getAdapterName($element);
//        $sm = $this->getServiceLocator();
//        /** @var ColModelAdapter $ret */
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
//        $adapterName = null;
//        foreach ($this->adapterMapConfig as $k => $v) {
//            if (is_a($element, $k) == true) {
//                $adapterName = $v;
//            }
//        }
//        if (!$adapterName) {
//            throw new Exception\OutOfBoundsException("missing ColModelAdapter for class = ". get_class($element));
//        }
//        return $adapterName;
//    }
//}